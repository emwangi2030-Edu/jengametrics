<?php

namespace App\Http\Controllers;

use App\Models\BqDocument;
use App\Models\BqSection;
use App\Models\Element;
use App\Models\Item;
use Illuminate\Http\Request;

class BqDocumentController extends Controller
{
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

        return view('bq_documents.index', [
            'project' => $project,
            'masterDocument' => $masterDocument,
            'subDocuments' => $subDocuments,
            'overallTotal' => $overallTotal,
        ]);
    }

    /**
     * Show the form for creating a new BQ document.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $project = get_project();
        $masterDocument = $this->ensureMasterDocument($project->id, $project->name);

        return view('bq_documents.create', compact('project', 'masterDocument'));
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

        if ($bqDocument->project_id !== $project->id) {
            abort(404);
        }

        if (is_null($bqDocument->parent_id)) {
            return redirect()->route('bq_documents.index');
        }

        $sections = $bqDocument->sections()
            ->with(['section'])
            ->orderBy('section_id')
            ->get();

        $sectionGroups = $sections->groupBy('section_id')->map(function ($group) {
            $aggregatedItems = $group
                ->groupBy(function ($item) {
                    if ($item->item_id) {
                        return 'item:' . $item->item_id;
                    }

                    $key = implode('|', [
                        strtolower(trim((string) $item->item_name)),
                        strtolower(trim((string) $item->units)),
                        number_format((float) ($item->rate ?? 0), 6, '.', ''),
                    ]);

                    return 'fallback:' . $key;
                })
                ->map(function ($items) {
                    $first = $items->first();
                    $quantity = $items->sum(function ($item) {
                        return (float) ($item->quantity ?? 0);
                    });
                    $amount = $items->sum(function ($item) {
                        return (float) ($item->amount ?? 0);
                    });

                    $rate = $quantity > 0
                        ? $amount / $quantity
                        : (float) ($first->rate ?? 0);

                    return (object) [
                        'item_name' => $first->item_name ?? __('Unnamed Item'),
                        'units' => $first->units ?? 'N/A',
                        'quantity' => $this->normalizeNumeric($quantity),
                        'rate' => $rate,
                        'amount' => $amount,
                    ];
                })
                ->values();

            return [
                'section' => $group->first()->section,
                'items' => $aggregatedItems,
                'total' => $aggregatedItems->sum('amount'),
            ];
        });

        $totalAmount = $sectionGroups->sum('total');

        return view('bq_documents.show', [
            'project' => $project,
            'bqDocument' => $bqDocument,
            'sectionGroups' => $sectionGroups,
            'totalAmount' => $totalAmount,
        ]);
    }

    public function destroy(BqDocument $bqDocument)
    {
        $project = get_project();

        if ($bqDocument->project_id !== $project->id || is_null($bqDocument->parent_id)) {
            abort(404);
        }

        $bqDocument->delete();

        return redirect()
            ->route('bq_documents.index')
            ->with('success', __('Sub BoQ deleted successfully.'));
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
}
