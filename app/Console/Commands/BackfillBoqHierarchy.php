<?php

namespace App\Console\Commands;

use App\Models\BqDocument;
use App\Models\BqSection;
use App\Models\BomItem;
use App\Models\BomLabour;
use App\Models\Project;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class BackfillBoqHierarchy extends Command
{
    protected $signature = 'boq:backfill-hierarchy {--dry-run : Preview actions without persisting changes}';

    protected $description = 'Populate new BoQ hierarchy columns for existing data.';

    public function handle(): int
    {
        $dryRun = (bool) $this->option('dry-run');

        if ($dryRun) {
            $this->info('Running in dry-run mode. All changes will be rolled back.');
        }

        $projectIds = BqSection::query()
            ->whereNotNull('project_id')
            ->distinct()
            ->pluck('project_id');

        if ($projectIds->isEmpty()) {
            $this->warn('No BQ sections found. Nothing to backfill.');
            return self::SUCCESS;
        }

        foreach ($projectIds as $rawProjectId) {
            $project = Project::find($rawProjectId);

            if (! $project) {
                $this->warn("Skipping unknown project ID {$rawProjectId} referenced by BQ sections.");
                continue;
            }

            if ($dryRun) {
                DB::beginTransaction();
            }

            try {
                $this->backfillProject($project, $dryRun);

                if ($dryRun) {
                    DB::rollBack();
                }
            } catch (\Throwable $exception) {
                if ($dryRun && DB::transactionLevel() > 0) {
                    DB::rollBack();
                }

                throw $exception;
            }
        }

        $this->info('Backfill complete.');

        return self::SUCCESS;
    }

    protected function backfillProject(Project $project, bool $dryRun): void
    {
        $this->line("Processing project [{$project->id}] {$project->name}");

        $ownerUserId = $this->resolveOwnerUserId($project);

        if (! $ownerUserId) {
            $this->error("No user found to associate with BoQ documents for project {$project->id}. Skipping.");
            return;
        }

        $master = BqDocument::query()
            ->where('project_id', $project->id)
            ->whereNull('parent_id')
            ->first();

        if (! $master) {
            $master = BqDocument::create([
                'title' => $this->determineMasterTitle($project),
                'description' => 'Auto-generated master BoQ document for aggregated totals.',
                'user_id' => $ownerUserId,
                'project_id' => $project->id,
            ]);

            $this->info("Created master BoQ document #{$master->id} for project {$project->id}.");
        } else {
            $this->updateModel($master, [
                'project_id' => $project->id,
                'user_id' => $master->user_id ?: $ownerUserId,
            ], $dryRun, "Master BoQ document #{$master->id}");
        }

        $legacyBoq = BqDocument::firstOrCreate(
            [
                'project_id' => $project->id,
                'parent_id' => $master->id,
                'title' => 'Legacy BoQ',
            ],
            [
                'description' => 'Auto-generated sub BoQ containing legacy items.',
                'user_id' => $ownerUserId,
            ]
        );

        $sectionDocIds = BqSection::query()
            ->where('project_id', $project->id)
            ->whereNotNull('bq_document_id')
            ->pluck('bq_document_id')
            ->unique();

        foreach ($sectionDocIds as $documentId) {
            $document = BqDocument::find($documentId);

            if (! $document) {
                $this->warn("Encountered missing BoQ document #{$documentId} referenced by sections. Skipping.");
                continue;
            }

            $this->updateModel($document, [
                'project_id' => $project->id,
                'parent_id' => $document->parent_id ?: $master->id,
                'user_id' => $document->user_id ?: $ownerUserId,
            ], $dryRun, "BqDocument #{$document->id}");
        }

        $sectionsWithoutDocument = BqSection::query()
            ->where('project_id', $project->id)
            ->whereNull('bq_document_id')
            ->get();

        foreach ($sectionsWithoutDocument as $section) {
            $this->updateModel($section, ['bq_document_id' => $legacyBoq->id], $dryRun, "BqSection #{$section->id}");
        }

        $sectionDocumentMap = BqSection::query()
            ->where('project_id', $project->id)
            ->pluck('bq_document_id', 'id');

        BomItem::query()
            ->where('project_id', $project->id)
            ->chunkById(200, function ($items) use ($sectionDocumentMap, $dryRun) {
                foreach ($items as $item) {
                    $documentId = $sectionDocumentMap[$item->bq_section_id] ?? null;

                    if (! $documentId) {
                        continue;
                    }

                    $this->updateModel($item, ['bq_document_id' => $documentId], $dryRun, "BomItem #{$item->id}");
                }
            });

        BomLabour::query()
            ->where('project_id', $project->id)
            ->chunkById(200, function ($labours) use ($sectionDocumentMap, $dryRun) {
                foreach ($labours as $labour) {
                    $documentId = $sectionDocumentMap[$labour->bq_section_id] ?? null;

                    if (! $documentId) {
                        continue;
                    }

                    $this->updateModel($labour, ['bq_document_id' => $documentId], $dryRun, "BomLabour #{$labour->id}");
                }
            });
    }

    protected function resolveOwnerUserId(Project $project): ?int
    {
        if ($project->user_id && User::whereKey($project->user_id)->exists()) {
            return $project->user_id;
        }

        $userId = User::query()
            ->where('project_id', $project->id)
            ->value('id');

        if ($userId) {
            return $userId;
        }

        return User::query()->value('id');
    }

    protected function updateModel(Model $model, array $attributes, bool $dryRun, string $label): void
    {
        $changes = [];

        foreach ($attributes as $key => $value) {
            if ($model->getAttribute($key) != $value) {
                $changes[$key] = $value;
            }
        }

        if (empty($changes)) {
            return;
        }

        $summary = collect($changes)
            ->map(fn ($value, $key) => sprintf('%s => %s', $key, $value === null ? 'null' : $value))
            ->implode(', ');

        if ($dryRun) {
            $this->line("[DRY RUN] {$label}: {$summary}");
            return;
        }

        $this->line("Updating {$label}: {$summary}");
        $model->fill($changes);
        $model->save();
    }

    protected function determineMasterTitle(Project $project): string
    {
        return trim(($project->name ?: 'Project') . ' Master BoQ');
    }
}
