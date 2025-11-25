<?php

namespace App\Http\Controllers;

use App\Models\BqDocument;
use App\Models\BqLevel;
use Illuminate\Http\Request;

class BqLevelController extends Controller
{
    public function store(BqDocument $bqDocument, Request $request)
    {
        $this->assertDocumentAccess($bqDocument);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $position = ($bqDocument->levels()->max('position') ?? 0) + 1;

        $bqDocument->levels()->create([
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'project_id' => $bqDocument->project_id,
            'position' => $position,
        ]);

        return redirect()
            ->route('bq_documents.show', $bqDocument)
            ->with('success', __('Level created successfully.'));
    }

    public function update(BqDocument $bqDocument, BqLevel $bqLevel, Request $request)
    {
        $level = $this->ensureLevel($bqDocument, $bqLevel);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $level->update($data);

        return redirect()
            ->route('bq_documents.show', $bqDocument)
            ->with('success', __('Level updated successfully.'));
    }

    public function destroy(BqDocument $bqDocument, BqLevel $bqLevel)
    {
        $level = $this->ensureLevel($bqDocument, $bqLevel);

        if ($level->sections()->exists()) {
            return redirect()
                ->route('bq_documents.show', $bqDocument)
                ->with('error', __('Cannot delete a level that still has items.'));
        }

        $level->delete();

        return redirect()
            ->route('bq_documents.show', $bqDocument)
            ->with('success', __('Level deleted successfully.'));
    }

    protected function ensureLevel(BqDocument $bqDocument, BqLevel $bqLevel): BqLevel
    {
        $this->assertDocumentAccess($bqDocument);

        if ((int) $bqLevel->bq_document_id !== (int) $bqDocument->id) {
            \Log::warning('BqLevel access blocked (level/doc mismatch)', [
                'user_id' => auth()->id(),
                'user_project_id' => project_id(),
                'bq_level_id' => $bqLevel->id,
                'bq_level_document_id' => $bqLevel->bq_document_id,
                'bq_document_id' => $bqDocument->id,
                'bq_document_project_id' => $bqDocument->project_id,
                'route' => request()->fullUrl(),
            ]);
            abort(404);
        }

        return $bqLevel;
    }

    protected function assertDocumentAccess(BqDocument $bqDocument): void
    {
        if (is_null($bqDocument->project_id)) {
            $bqDocument->update(['project_id' => project_id()]);
        }

        if ((int) $bqDocument->project_id !== (int) project_id() || is_null($bqDocument->parent_id)) {
            \Log::warning('BqLevel access blocked', [
                'user_id' => auth()->id(),
                'user_project_id' => project_id(),
                'bq_document_id' => $bqDocument->id,
                'bq_document_project_id' => $bqDocument->project_id,
                'bq_document_parent_id' => $bqDocument->parent_id,
                'route' => request()->fullUrl(),
            ]);
            abort(404);
        }
    }
}
