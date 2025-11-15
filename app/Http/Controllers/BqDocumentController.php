<?php

namespace App\Http\Controllers;

use App\Models\BqDocument;
use App\Models\BqLevel;
use App\Models\BqSection;
use App\Models\BomItem;
use App\Models\BomLabour;
use App\Models\Element;
use App\Models\Item;
use App\Models\Library;
use App\Models\LibraryItem;
use App\Models\Section;
use App\Services\BqItemCreator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class BqDocumentController extends Controller
{
    protected array $levelReplicationCache = [];
    /**
     * Display a listing of the BQ documents.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $project = get_project();

        $masterDocument = $this->ensureMasterDocument($project->id, $project->name);

        $subDocuments = $masterDocument->children()
            ->with(['sections'])
            ->orderBy('created_at')
            ->get()
            ->map(function (BqDocument $document) {
                $aggregated = $document->sections
                    ->groupBy(function (BqSection $section) {
                        if ($section->item_id) {
                            return 'item:' . $section->item_id;
                        }

                        $name = strtolower(trim((string) $section->item_name));
                        $unit = strtolower(trim((string) $section->units));
                        $rate = number_format((float) ($section->rate ?? 0), 6, '.', '');

                        return implode('|', ['fallback', $name, $unit, $rate]);
                    })
                    ->map(function ($sections) {
                        $quantity = $sections->sum(fn ($section) => (float) ($section->quantity ?? 0));
                        $amount = $sections->sum(fn ($section) => (float) ($section->amount ?? 0));

                        return collect([
                            'quantity' => $quantity,
                            'amount' => $amount,
                        ]);
                    });

                $document->unique_items_count = $aggregated->count();
                $document->aggregated_amount = $aggregated->sum('amount');

                return $document;
            });

        $overallTotal = BqSection::where('project_id', $project->id)->get()
            ->groupBy(function (BqSection $section) {
                if ($section->item_id) {
                    return 'item:' . $section->item_id;
                }

                $name = strtolower(trim((string) $section->item_name));
                $unit = strtolower(trim((string) $section->units));
                $rate = number_format((float) ($section->rate ?? 0), 6, '.', '');

                return implode('|', ['fallback', $name, $unit, $rate]);
            })
            ->map(function ($sections) {
                return $sections->sum(fn ($section) => (float) ($section->amount ?? 0));
            })
            ->sum();

        $libraries = auth()->user()
            ?->libraries()
            ->withCount('items')
            ->latest()
            ->get() ?? collect();

        $sections = Section::orderBy('name')->get();

        return view('bq_documents.index', [
            'project' => $project,
            'masterDocument' => $masterDocument,
            'subDocuments' => $subDocuments,
            'overallTotal' => $overallTotal,
            'libraries' => $libraries,
            'sections' => $sections,
        ]);
    }

    /**
     * Show the form for creating a new BQ document.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return redirect()
            ->route('bq_documents.index')
            ->with('info', __('Use the Create BoQ button to open the modal.'));
    }



    // Method to get elements based on the selected section
    public function getElements(Request $request)
    {
        $elements = Element::where('section_id', $request->section_id)->pluck('name', 'id');
        return response()->json($elements);
    }

    public function getItems(Request $request)
    {
        $items = Item::where('element_id', $request->element_id)->pluck('name', 'id');
        return response()->json($items);
    }

    public function importLibrary(Request $request, BqDocument $bqDocument, BqItemCreator $bqItemCreator)
    {
        $projectId = (int) ($bqDocument->project_id ?? project_id());

        $this->assertSubDocumentAccess($bqDocument, $projectId);

        $validated = $request->validate([
            'library_id' => 'required|exists:libraries,id',
            'bq_level_id' => [
                'required',
                Rule::exists('bq_levels', 'id')->where(fn ($query) => $query->where('bq_document_id', $bqDocument->id)),
            ],
            'items' => 'required|array|min:1',
            'items.*.quantity' => 'required|numeric|min:0.0001',
            'items.*.rate' => 'required|numeric|min:0',
        ]);

        $library = Library::where('id', $validated['library_id'])
            ->where('user_id', auth()->id())
            ->first();

        if (! $library) {
            return redirect()
                ->route('bq_documents.show', $bqDocument)
                ->with('danger', __('You are not authorized to use the selected library.'));
        }

        $targetLevel = BqLevel::where('id', $validated['bq_level_id'])
            ->where('bq_document_id', $bqDocument->id)
            ->firstOrFail();

        $itemIds = array_keys($validated['items']);

        $libraryItems = LibraryItem::where('library_id', $library->id)
            ->whereIn('id', $itemIds)
            ->with(['section', 'element', 'item'])
            ->get();

        if ($libraryItems->count() !== count($itemIds)) {
            return redirect()
                ->route('bq_documents.show', $bqDocument)
                ->with('danger', __('Some selected library items were not found.'));
        }

        foreach ($libraryItems as $libraryItem) {
            if (! $libraryItem->section || ! $libraryItem->element || ! $libraryItem->item) {
                return redirect()
                    ->route('bq_documents.show', $bqDocument)
                    ->with('danger', __('One or more library items are missing linked records and cannot be imported.'));
            }
        }

        DB::transaction(function () use ($libraryItems, $validated, $bqDocument, $bqItemCreator, $projectId, $targetLevel) {
            foreach ($libraryItems as $libraryItem) {
                $payload = $validated['items'][$libraryItem->id];

                $quantity = (float) $payload['quantity'];
                $rate = (float) $payload['rate'];

                $bqItemCreator->create(
                    $bqDocument,
                    $libraryItem->section,
                    $targetLevel,
                    $libraryItem->element,
                    $libraryItem->item,
                    $quantity,
                    $rate,
                    $projectId
                );
            }
        });

        return redirect()
            ->route('bq_levels.show', [$bqDocument, $targetLevel])
            ->with('success', __('Library items imported successfully.'));
    }

    public function copyForm(BqDocument $bqDocument)
    {
        $project = get_project();

        $this->assertSubDocumentAccess($bqDocument, $project->id);

        $sections = $bqDocument->sections()
            ->with(['section', 'item'])
            ->orderBy('section_id')
            ->orderBy('id')
            ->get();

        $groupedSections = $sections->groupBy('section_id')->map(function ($items) {
            return [
                'section' => $items->first()->section,
                'items' => $items,
            ];
        });

        return view('bq_documents.copy', [
            'project' => $project,
            'sourceDocument' => $bqDocument,
            'groupedSections' => $groupedSections,
            'suggestedTitle' => trim($bqDocument->title . ' Copy'),
        ]);
    }

    public function copyStore(Request $request, BqDocument $bqDocument)
    {
        $project = get_project();

        $this->assertSubDocumentAccess($bqDocument, $project->id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*' => [
                'integer',
                Rule::exists('bq_sections', 'id')->where('bq_document_id', $bqDocument->id),
            ],
        ]);

        $selectedItemIds = collect($validated['items'])->map(fn ($id) => (int) $id)->unique()->values();

        $sections = BqSection::query()
            ->where('bq_document_id', $bqDocument->id)
            ->whereIn('id', $selectedItemIds)
            ->get();

        if ($sections->count() !== $selectedItemIds->count()) {
            return back()
                ->withInput()
                ->withErrors(['items' => __('One or more selected items could not be found.')]);
        }

        $masterDocument = $this->ensureMasterDocument($project->id, $project->name);

        $newDocument = null;

        DB::transaction(function () use (
            &$newDocument,
            $bqDocument,
            $sections,
            $project,
            $validated,
            $masterDocument
        ) {
            $newDocument = BqDocument::create([
                'title' => $validated['title'],
                'description' => $validated['description'] ?? null,
                'user_id' => auth()->id() ?: $bqDocument->user_id,
                'project_id' => $project->id,
                'parent_id' => $masterDocument->id,
            ]);

            foreach ($sections as $section) {
                $this->duplicateSectionWithBom($section, $newDocument);
            }
        });

        return redirect()
            ->route('bq_documents.show', $newDocument)
            ->with('success', __('BoQ copied successfully.'));
    }
    
    /**
     * Store a newly created BQ document in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validate incoming request data
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $project = get_project();
        $masterDocument = $this->ensureMasterDocument($project->id, $project->name);

        // Create a new sub BoQ document
        $document = BqDocument::create([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'user_id' => auth()->id(),
            'project_id' => $project->id,
            'parent_id' => $masterDocument->id,
        ]);

        return redirect()->route('bq_documents.show', $document)
            ->with('success', 'BoQ created successfully.');
    }

    /**
     * Display the specified BQ document.
     *
     * @param \App\Models\BqDocument $bqDocument
     * @return \Illuminate\View\View
     */
    public function show(BqDocument $bqDocument)
    {
        $project = get_project();

        if (is_null($bqDocument->parent_id)) {
            return redirect()->route('bq_documents.index');
        }

        $this->assertSubDocumentAccess($bqDocument, $project->id);

        $levels = $bqDocument->levels()
            ->with(['sections' => function ($query) {
                $query->with('section')
                    ->orderByDesc('created_at');
            }])
            ->orderBy('position')
            ->get()
            ->map(function (BqLevel $level) {
                $items = $level->sections->map(function (BqSection $section) {
                    return (object) [
                        'id' => $section->id,
                        'section_name' => optional($section->section)->name ?? __('Unassigned Section'),
                        'item_name' => $section->item_name ?? __('Unnamed Item'),
                        'units' => $section->units ?? 'N/A',
                        'quantity' => $this->normalizeNumeric((float) ($section->quantity ?? 0)),
                        'amount' => (float) ($section->amount ?? 0),
                    ];
                });

                return [
                    'level' => $level,
                    'items' => $items,
                    'items_count' => $items->count(),
                    'total' => $items->sum('amount'),
                ];
            });

        $totalAmount = $levels->sum('total');

        $libraries = auth()->user()
            ?->libraries()
            ->withCount('items')
            ->latest()
            ->get() ?? collect();

        return view('bq_documents.show', [
            'project' => $project,
            'bqDocument' => $bqDocument,
            'levels' => $levels,
            'totalAmount' => $totalAmount,
            'libraries' => $libraries,
        ]);
    }

    public function destroy(BqDocument $bqDocument)
    {
        $project = get_project();

        if ($bqDocument->project_id !== $project->id || is_null($bqDocument->parent_id)) {
            abort(404);
        }

        DB::transaction(function () use ($bqDocument) {
            $sectionIds = BqSection::query()
                ->where('bq_document_id', $bqDocument->id)
                ->pluck('id');

            if ($sectionIds->isNotEmpty()) {
                BomItem::whereIn('bq_section_id', $sectionIds)->delete();
                BomLabour::whereIn('bq_section_id', $sectionIds)->delete();
            }

            BomItem::where('bq_document_id', $bqDocument->id)->delete();
            BomLabour::where('bq_document_id', $bqDocument->id)->delete();

            $bqDocument->delete();
        });

        return redirect()
            ->route('bq_documents.index')
            ->with('success', __('Sub BoQ deleted successfully.'));
    }

    public function edit(BqDocument $bqDocument)
    {
        $project = get_project();

        $this->assertSubDocumentAccess($bqDocument, $project->id);

        return redirect()
            ->route('bq_documents.index')
            ->with('info', __('Use the inline modal to edit sub BoQs.'));
    }

    public function update(Request $request, BqDocument $bqDocument)
    {
        $project = get_project();

        $this->assertSubDocumentAccess($bqDocument, $project->id);

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $bqDocument->update($data);

        return redirect()
            ->route('bq_documents.index', $bqDocument)
            ->with('success', __('Sub BoQ updated successfully.'));
    }

    protected function normalizeNumeric(float $value)
    {
        $rounded = round($value, 4);

        if (abs($rounded - round($rounded)) < 0.0001) {
            return (int) round($rounded);
        }

        return (float) $rounded;
    }

    protected function ensureMasterDocument(int $projectId, ?string $projectName = null): BqDocument
    {
        $defaultTitle = trim(($projectName ?: 'Project') . ' Master BoQ');

        $document = BqDocument::query()
            ->where('project_id', $projectId)
            ->whereNull('parent_id')
            ->first();

        if ($document) {
            return $document;
        }

        return BqDocument::create([
            'title' => $defaultTitle,
            'description' => 'Automatically generated master BoQ for aggregated totals.',
            'user_id' => auth()->id(),
            'project_id' => $projectId,
            'parent_id' => null,
        ]);
    }

    protected function assertSubDocumentAccess(BqDocument $bqDocument, int $projectId): void
    {
        if (is_null($bqDocument->project_id)) {
            $bqDocument->update(['project_id' => $projectId]);
        }

        if ((int) $bqDocument->project_id !== (int) $projectId || is_null($bqDocument->parent_id)) {
            abort(404);
        }
    }

    protected function duplicateSectionWithBom(BqSection $section, BqDocument $targetDocument): void
    {
        $newSection = $section->replicate();
        $newSection->bq_document_id = $targetDocument->id;
        $newSection->project_id = $targetDocument->project_id;
        $newSection->bq_level_id = $this->replicateLevel($section->level, $targetDocument);
        $newSection->save();

        $bomItems = BomItem::query()
            ->where('bq_section_id', $section->id)
            ->get();

        foreach ($bomItems as $bomItem) {
            $newBomItem = $bomItem->replicate();
            $newBomItem->bq_section_id = $newSection->id;
            $newBomItem->bq_document_id = $targetDocument->id;
            $newBomItem->project_id = $targetDocument->project_id;
            $newBomItem->save();
        }

        $bomLabours = BomLabour::query()
            ->where('bq_section_id', $section->id)
            ->get();

        foreach ($bomLabours as $bomLabour) {
            $newBomLabour = $bomLabour->replicate();
            $newBomLabour->bq_section_id = $newSection->id;
            $newBomLabour->bq_document_id = $targetDocument->id;
            $newBomLabour->project_id = $targetDocument->project_id;
            $newBomLabour->save();
        }
    }

    protected function replicateLevel(?BqLevel $sourceLevel, BqDocument $targetDocument): ?int
    {
        if (! $sourceLevel) {
            return null;
        }

        $cacheKey = $sourceLevel->id . ':' . $targetDocument->id;
        if (array_key_exists($cacheKey, $this->levelReplicationCache)) {
            return $this->levelReplicationCache[$cacheKey];
        }

        $position = ($targetDocument->levels()->max('position') ?? 0) + 1;

        $newLevel = BqLevel::firstOrCreate(
            [
                'bq_document_id' => $targetDocument->id,
                'name' => $sourceLevel->name,
            ],
            [
                'project_id' => $targetDocument->project_id,
                'description' => $sourceLevel->description,
                'position' => $position,
            ]
        );

        return $this->levelReplicationCache[$cacheKey] = $newLevel->id;
    }
}
