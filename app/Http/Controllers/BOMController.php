<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\ResolvesActiveProject;
use Illuminate\Http\Request;
use App\Http\Requests\StoreBomRequest;
use App\Models\BqDocument;
use App\Models\BqLevel;
use App\Models\Bom;
use App\Models\BomLabour;
use App\Models\BomItem;
use App\Models\Section;
use App\Models\BqSection;
use App\Models\Material;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Project;
use App\Models\Requisition;
use App\Models\Item;
use App\Models\ItemMaterial;
use Illuminate\Support\Facades\Auth;

class BOMController extends Controller
{
    use ResolvesActiveProject;

    public function index()
    {
        $project = $this->resolveActiveProject();
        if (! $project) {
            return redirect()
                ->route('dashboard')
                ->with('warning', __('No project is selected. Please choose a project first.'));
        }

        $sections = Section::orderBy('id', 'asc')->get();

        $projectId = project_id();

        $labourTotalsByDocument = BomLabour::where('project_id', $projectId)
            ->with('bqSection')
            ->get()
            ->groupBy(function (BomLabour $labour) {
                return $labour->bq_document_id
                    ?: optional($labour->bqSection)->bq_document_id
                    ?: 'unassigned';
            })
            ->map(function ($items) {
                return (float) $items->sum('amount');
            });

        $subDocuments = BqDocument::where('project_id', $project->id)
            ->whereNotNull('parent_id')
            ->orderBy('created_at')
            ->with(['levels' => function ($levelQuery) {
                $levelQuery->with(['sections' => function ($query) {
                    $query->orderBy('section_id')
                        ->with([
                            'section',
                            'bomItems.item_material',
                            'bomItems.product',
                        ]);
                }]);
            }])
            ->get()
            ->map(fn (BqDocument $document) => $this->transformDocumentForBom($document, $labourTotalsByDocument));

        $totalAmount = $subDocuments->sum('materials_total');
        $totalLabour = $subDocuments->sum('labour_total');

        return view('boms.index', [
            'project' => $project,
            'sections' => $sections,
            'totalAmount' => $totalAmount,
            'totalLabour' => $totalLabour,
            'subDocuments' => $subDocuments,
        ]);
    }

    public function create()
    {
        $bqDocuments = BqDocument::all();
        return view('boms.create', compact('bqDocuments'));
    }

    public function store(StoreBomRequest $request)
    {
        $bom = Bom::create([
            'bq_document_id' => $request->bq_document_id,
            'bom_name' => $request->bom_name,
        ]);

        foreach ($request->items as $item) {
            // Fetch the rate from the products table based on product_id
            $product = Product::find($item['product_id']);
            $rate = optional($product)->rate ?? 0;
            $quantity = $item['quantity'];
            $amount = $rate * $quantity;

            BomItem::create([
                'bom_id' => $bom->id,
                'item_description' => $item['description'],
                'quantity' => $quantity,
                'unit' => $item['unit'],
                'rate' => $rate,
                'amount' => $amount,
            ]);
        }

        return redirect()->route('boms.index');
    }

    public function show($id)
    {
        $bqSection = Section::find($id);

        if (! $bqSection) {
            abort(404);
        }

        $rawItems = BomItem::with(['product', 'item_material'])
            ->whereProjectId(project_id())
            ->where('section_id', $id)
            ->get();

        $items = collect();
        $section_name = $bqSection->name;

        $primaryBqSection = BqSection::where('section_id', $id)
            ->whereProjectId(project_id())
            ->with('bqDocument')
            ->first();

        $bqDocumentForSection = $primaryBqSection?->bqDocument;

        $groupedItems = $rawItems->groupBy(function (BomItem $item) {
            if ($item->product_id) {
                return 'product:' . $item->product_id;
            }

            $name = strtolower(trim((string) ($item->item_description ?? 'manual')));
            $unit = strtolower(trim((string) ($item->unit ?? 'unit')));

            return 'manual:' . $name . '|' . $unit;
        });

        foreach ($groupedItems as $groupKey => $group) {
            $sampleItem = $group->first();
            $totalQty = (float) $group->sum('quantity');
            $totalAmt = (float) $group->sum('amount');

            $product = $sampleItem?->product_id ? Product::find($sampleItem->product_id) : null;

            $displayName = $product?->name
                ?? $sampleItem->item_description
                ?? __('Unknown Material');
            $displayUnit = $product?->unit
                ?? $sampleItem->unit
                ?? 'N/A';

            $sampleItem->total_quantity = $totalQty;
            $sampleItem->total_amount = $totalAmt;
            $sampleItem->rate = $totalQty > 0 ? $totalAmt / $totalQty : (float) ($sampleItem->rate ?? 0);
            $sampleItem->display_name = $displayName;
            $sampleItem->display_unit = $displayUnit;
            $sampleItem->unit = $displayUnit;

            // Push for BoM table
            $items->push($sampleItem);
        }

        $labours = BomLabour::whereProjectId(project_id())
            ->where('section_id', $id)
            ->get();

        return view('boms.show', compact('bqSection', 'items', 'labours', 'section_name', 'bqDocumentForSection'));
    }

    public function destroy($id)
    {
        // Find the BOM by its ID
        $bom = Bom::findOrFail($id);

        // Optionally, delete related items (if applicable)
        $bom->items()->delete();

        // Delete the BOM
        $bom->delete();

        // Redirect back to the index page with a success message
        return redirect()->route('boms.index')->with('success', 'BOM deleted successfully.');
    }

    public function showDocument(BqDocument $bqDocument)
    {
        $project = get_project();

        $bqDocument->load(['levels' => function ($levelQuery) {
            $levelQuery->with(['sections' => function ($query) {
                $query->orderBy('section_id')
                    ->with([
                        'section',
                        'bomItems.item_material',
                        'bomItems.product',
                    ]);
            }]);
        }]);

        $labourTotal = BomLabour::where('project_id', project_id())
            ->where('bq_document_id', $bqDocument->id)
            ->sum('amount');

        $document = $this->transformDocumentForBom($bqDocument, collect([$bqDocument->id => $labourTotal]));

        return view('boms.document', [
            'project' => $project,
            'document' => $document,
        ]);
    }

    public function report()
    {
        // Calculate the total estimated cost across all sections
       $project = $this->resolveActiveProject();
       if (! $project) {
           return redirect()
               ->route('dashboard')
               ->with('warning', __('No project is selected. Please choose a project first.'));
       }

       $projectId = (int) $project->id;

       $totalEstimatedCost = BomItem::whereProjectId($projectId)
            ->selectRaw('SUM(quantity * rate) as total')
            ->value('total');

        $totalEstimatedCost_labour = BomLabour::whereProjectId($projectId)->sum('amount');

        $materials = Material::whereProjectId($projectId)->get();

        $payments = Payment::with(['worker' => fn ($q) => $q->withTrashed()])
            ->whereProjectId($projectId)
            ->orderByDesc('payment_date')
            ->get();

        // Calculate total cost of all materials and labour
        $total_actual_cost = $materials->sum(function ($material) {
            return $material->unit_price * $material->quantity_purchased;
        });

        $total_actual_payments = $payments->sum('amount');
        
        return view('report.report', compact(
            'totalEstimatedCost',
            'totalEstimatedCost_labour',
            'total_actual_cost',
            'total_actual_payments',
            'project',
            'payments'
        ));
    }

    protected function transformDocumentForBom(BqDocument $document, $labourTotalsByDocument)
    {
        $levels = $document->levels ?? collect();

        if ($levels->isEmpty()) {
            $sections = $document->sections ?? collect();

            if ($sections->isNotEmpty()) {
                $fallbackLevel = new BqLevel(['name' => __('Ungrouped')]);
                $fallbackLevel->setRelation('sections', $sections);
                $levels = collect([$fallbackLevel]);
            }
        }

        $processedSections = $levels->flatMap(function (BqLevel $level) {
            return $level->sections->map(function (BqSection $section) use ($level) {
                $materials = $section->bomItems->map(function (BomItem $item) use ($section) {
                    $product = optional($item->product);
                    $itemMaterial = optional($item->item_material);

                    $name = $itemMaterial->name
                        ?? $product->name
                        ?? $item->item_description
                        ?? $section->item_name
                        ?? __('Unknown Material');

                    $unit = $itemMaterial->unit_of_measurement
                        ?? $product->unit
                        ?? $item->unit
                        ?? $section->units
                        ?? 'N/A';

                    $quantity = (float) ($item->quantity ?? 0);
                    $rate = (float) ($item->rate ?? $product->rate ?? 0);
                    $amount = (float) ($item->amount ?? ($quantity * $rate));

                    return (object) [
                        'product_id' => $item->product_id,
                        'name' => $name,
                        'unit' => $unit,
                        'quantity' => $this->normalizeNumeric($quantity),
                        'rate' => $rate,
                        'amount' => $amount,
                    ];
                });

                $sectionModel = optional($section->section);
                $key = $sectionModel?->id ?? ('bq:' . $section->id);

                return collect([
                    'group_key' => $key,
                    'level_id' => $level->id,
                    'level_name' => $level->name,
                    'section_id' => $sectionModel?->id,
                    'section_name' => $sectionModel?->name ?? ($section->item_name ?: __('Unassigned Section')),
                    'units' => $section->units,
                    'quantity' => (float) ($section->quantity ?? 0),
                    'amount' => (float) ($section->amount ?? 0),
                    'materials' => $materials,
                ]);
            });
        });

        $aggregatedSections = $processedSections
            ->groupBy('group_key')
            ->map(function ($group) {
                $first = $group->first();

                $materialsCollection = $group->pluck('materials')->flatten(1);

                $withProduct = $materialsCollection
                    ->filter(fn ($material) => ! is_null($material->product_id))
                    ->groupBy('product_id')
                    ->map(function ($materials) {
                        $sample = $materials->first();
                        $totalQuantity = $materials->sum(fn ($material) => (float) ($material->quantity ?? 0));
                        $totalAmount = $materials->sum(fn ($material) => (float) ($material->amount ?? 0));

                        $rate = $totalQuantity > 0
                            ? $totalAmount / $totalQuantity
                            : (float) ($sample->rate ?? 0);

                        return (object) [
                            'product_id' => $sample->product_id,
                            'name' => $sample->name ?? __('Unknown Material'),
                            'unit' => $sample->unit ?? 'N/A',
                            'quantity' => $this->normalizeNumeric($totalQuantity),
                            'rate' => $rate,
                            'amount' => $totalAmount,
                        ];
                    })
                    ->values();

        $withoutProduct = $materialsCollection
            ->filter(fn ($material) => is_null($material->product_id));

        if ($withoutProduct->isNotEmpty()) {
            $grouped = $withoutProduct->groupBy(function ($material) {
                $name = $material->name ?? __('Unassigned Materials');
                $unit = $material->unit ?? 'N/A';
                return $name . '|' . $unit;
            });

            foreach ($grouped as $key => $materials) {
                $sample = $materials->first();
                $name = $sample->name ?? __('Unassigned Materials');
                $unit = $sample->unit ?? 'N/A';
                $totalQuantity = $materials->sum(fn ($material) => (float) ($material->quantity ?? 0));
                $totalAmount = $materials->sum(fn ($material) => (float) ($material->amount ?? 0));
                $rate = $totalQuantity > 0 ? $totalAmount / $totalQuantity : 0;

                $withProduct->push((object) [
                    'product_id' => null,
                    'name' => $name,
                    'unit' => $unit,
                    'quantity' => $this->normalizeNumeric($totalQuantity),
                    'rate' => $rate,
                    'amount' => $totalAmount,
                ]);
            }
        }

        $materials = $withProduct;

        return (object) [
            'level_id' => $first->get('level_id'),
            'level_name' => $first->get('level_name'),
            'section_id' => $first->get('section_id'),
            'section_name' => $first->get('section_name'),
            'units' => $first->get('units') ?? 'N/A',
            'quantity' => $this->normalizeNumeric($group->sum(fn ($section) => (float) ($section->get('quantity') ?? 0))),
            'amount' => $group->sum(fn ($section) => (float) ($section->get('amount') ?? 0)),
            'materials' => $materials,
            'material_total' => $materials->sum('amount'),
            'item_count' => $group->count(),
        ];
            })
            ->values();

        $document->sections = $aggregatedSections;
        $document->level_summaries = $aggregatedSections
            ->groupBy('level_id')
            ->map(function ($sections, $levelId) use ($levels) {
                $level = $levels->firstWhere('id', $levelId);

                return (object) [
                    'level_id' => $levelId,
                    'level_name' => optional($level)->name ?? __('Ungrouped'),
                    'material_total' => $sections->sum('material_total'),
                    'section_count' => $sections->count(),
                ];
            })
            ->values();

        $labourTotal = $labourTotalsByDocument[$document->id] ?? 0;

        $document->materials_total = (float) $document->sections->sum('material_total');
        $document->labour_total = (float) $labourTotal;
        $document->combined_total = $document->materials_total + $document->labour_total;

        return $document;
    }

    protected function normalizeNumeric(float $value)
    {
        $rounded = round($value, 4);

        if (abs($rounded - round($rounded)) < 0.0001) {
            return (int) round($rounded);
        }

        return (float) $rounded;
    }
}
