<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\ResolvesActiveProject;
use Illuminate\Http\Request;
use App\Http\Requests\StoreMaterialRequest;
use App\Models\Material;
use App\Models\ItemMaterial;
use App\Models\Supplier;
use App\Models\BomItem;
use App\Models\Project;
use App\Models\Requisition;
use App\Models\StockUsage;
use App\Models\Section;
use App\Models\BqDocument;
use App\Models\UnitOfMeasurement;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Support\DateRangeQueries;

class MaterialController extends Controller
{
    use ResolvesActiveProject;

    public function index(Request $request)
    {
        $project = $this->resolveActiveProject();
        if (! $project) {
            return redirect()
                ->route('dashboard')
                ->with('warning', __('No project is selected. Please choose a project first.'));
        }

        $projectId = (int) $project->id;
        $year = $request->input('year', now()->year);

        // Build materials query
        $materialsQuery = Material::with(['supplier', 'requisition'])
            ->where('project_id', $projectId);

        // Apply time filters
        if ($request->filter === 'week') {
            $materialsQuery->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
        } elseif ($request->filter === 'month') {
            $materialsQuery->whereMonth('created_at', now()->month)
                        ->whereYear('created_at', now()->year);
        }

        // Apply year filter
        $materialsQuery->whereYear('created_at', $year);
        $materials = $materialsQuery->get();

        // Inventory (not filtered by time)
        $inventory = Material::select('product_id', 'name', 'unit_of_measure')
            ->selectRaw('SUM(quantity_in_stock) as total_stock')
            ->where('project_id', $projectId)
            ->groupBy('product_id', 'name', 'unit_of_measure')
            ->get();

        $sections = Section::all();

        // Stock usage history
        $stockUsageQuery = StockUsage::with(['material', 'section'])
            ->whereHas('material', function ($query) use ($projectId) {
                $query->where('project_id', $projectId);
            });

        if ($request->filter === 'week') {
            $stockUsageQuery->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
        } elseif ($request->filter === 'month') {
            $stockUsageQuery->whereMonth('created_at', now()->month)
                            ->whereYear('created_at', now()->year);
        }

        if ($request->section_id) {
            $stockUsageQuery->where('section_id', $request->section_id);
        }

        $stockUsageQuery->whereYear('created_at', $year);
        $stockUsages = $stockUsageQuery->orderBy('created_at', 'desc')->get();

        $rawItems = BomItem::whereProjectId($projectId)
            ->with(['item_material', 'product', 'bqDocument', 'bqSection'])
            ->get();

        $groupedItems = $rawItems->groupBy(function (BomItem $item) {
            return $item->product_id
                ? 'product:' . $item->product_id
                : 'manual:' . $item->id;
        });

        $requisitionableItems = collect();

        foreach ($groupedItems as $key => $group) {
            $sampleItem = $group->first();
            $totalQty = $group->sum('quantity');

            $displayName = optional($sampleItem->item_material)->name
                ?? optional($sampleItem->product)->name
                ?? $sampleItem->item_description
                ?? optional($sampleItem->bqSection)->item_name
                ?? __('Unassigned Material');

            $displayUnit = optional($sampleItem->item_material)->unit_of_measurement
                ?? optional($sampleItem->product)->unit
                ?? $sampleItem->unit
                ?? optional($sampleItem->bqSection)->units
                ?? 'unit';

            $sampleItem->display_name = $displayName;
            $sampleItem->display_unit = $displayUnit;
            $sampleItem->unit = $displayUnit;
            $sampleItem->total_quantity = $totalQty;

            $requisitionedQty = Requisition::whereIn('bom_item_id', $group->pluck('id'))
                ->whereIn('status', ['pending', 'approved'])
                ->sum('quantity_requested');

            $remaining = max(0, $totalQty - $requisitionedQty);
            $sampleItem->remaining_quantity = $remaining;

            if ($remaining >= 1) {
                $requisitionableItems->push(clone $sampleItem);
            }
        }

        // Get available years
        $availableYears = Material::selectRaw('DISTINCT ' . DateRangeQueries::yearColumn('created_at') . ' as year')
            ->orderBy('year', 'desc')
            ->pluck('year');

        $project = Project::find($projectId);

        return view('materials.index', compact(
            'materials',
            'project',
            'inventory',
            'sections',
            'stockUsages',
            'availableYears',
            'year',
            'requisitionableItems'
        ));
    }

    public function materialsDelivered(Request $request)
    {
        $project = $this->resolveActiveProject();
        if (! $project) {
            return redirect()
                ->route('dashboard')
                ->with('warning', __('No project is selected. Please choose a project first.'));
        }

        $projectId = (int) $project->id;
        $year = $request->input('year', now()->year);

        $materialsQuery = Material::with(['supplier', 'requisition'])
            ->where('project_id', $projectId);

        if ($request->filter === 'week') {
            $materialsQuery->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
        } elseif ($request->filter === 'month') {
            $materialsQuery->whereMonth('created_at', now()->month)
                        ->whereYear('created_at', now()->year);
        }

        if ($request->filled('supplier_id')) {
            $materialsQuery->where('supplier_id', $request->input('supplier_id'));
        }

        $materials = $materialsQuery
            ->whereYear('created_at', $year)
            ->orderBy('created_at', 'desc')
            ->get();

        $availableYears = Material::selectRaw('DISTINCT ' . DateRangeQueries::yearColumn('created_at') . ' as year')
            ->orderBy('year', 'desc')
            ->pluck('year');

        $project = Project::find($projectId);

        $rawItems = BomItem::whereProjectId($projectId)
            ->with(['item_material', 'product', 'bqDocument', 'bqSection'])
            ->get();

        $groupedItems = $rawItems->groupBy(function (BomItem $item) {
            return $item->product_id
                ? 'product:' . $item->product_id
                : 'manual:' . $item->id;
        });

        $requisitionableItems = collect();

        $documentMap = BqDocument::whereIn('id', $rawItems->pluck('bq_document_id')->filter()->unique())->get()->keyBy('id');

        foreach ($groupedItems as $key => $group) {
            $sampleItem = $group->first();
            $totalQty = $group->sum('quantity');

            $displayName = optional($sampleItem->item_material)->name
                ?? optional($sampleItem->product)->name
                ?? $sampleItem->item_description
                ?? optional($sampleItem->bqSection)->item_name
                ?? __('Unassigned Material');

            $displayUnit = optional($sampleItem->item_material)->unit_of_measurement
                ?? optional($sampleItem->product)->unit
                ?? $sampleItem->unit
                ?? optional($sampleItem->bqSection)->units
                ?? 'unit';

            $sampleItem->display_name = $displayName;
            $sampleItem->display_unit = $displayUnit;
            $sampleItem->unit = $displayUnit;
            $sampleItem->total_quantity = $totalQty;

            $documentId = $group->pluck('bq_document_id')->filter()->first();
            $sampleItem->bq_document = $documentId ? $documentMap->get($documentId) : null;

            $requisitionedQty = Requisition::whereIn('bom_item_id', $group->pluck('id'))
                ->whereIn('status', ['pending', 'approved'])
                ->sum('quantity_requested');

            $remaining = max(0, $totalQty - $requisitionedQty);
            $sampleItem->remaining_quantity = $remaining;

            if ($remaining >= 1) {
                $requisitionableItems->push(clone $sampleItem);
            }
        }

        $sections = Section::all();
        $suppliers = Supplier::orderBy('name')->get();
        $units = UnitOfMeasurement::orderBy('abbrev')->get();

        if ($request->ajax()) {
            $table = view('materials.partials.delivered_table', compact('materials'))
                ->render();

            return response()->json([
                'table' => $table,
            ]);
        }

        return view('materials.materials_delivered', compact(
            'materials',
            'project',
            'availableYears',
            'year',
            'requisitionableItems',
            'sections',
            'suppliers',
            'units'
        ));
    }

    public function inventoryManagement()
    {
        $project = $this->resolveActiveProject();
        if (! $project) {
            return redirect()
                ->route('dashboard')
                ->with('warning', __('No project is selected. Please choose a project first.'));
        }

        $projectId = (int) $project->id;

        $inventory = Material::select('product_id', 'name', 'unit_of_measure')
            ->selectRaw('SUM(quantity_in_stock) as total_stock')
            ->where('project_id', $projectId)
            ->groupBy('product_id', 'name', 'unit_of_measure')
            ->get();

        $sections = Section::all();

        return view('materials.inventory_management', compact(
            'inventory',
            'sections'
        ));
    }

    public function stockUsageHistory(Request $request)
    {
        $project = $this->resolveActiveProject();
        if (! $project) {
            return redirect()
                ->route('dashboard')
                ->with('warning', __('No project is selected. Please choose a project first.'));
        }

        $projectId = (int) $project->id;
        $year = $request->input('year', now()->year);

        $stockUsageQuery = StockUsage::with(['material', 'section'])
            ->whereHas('material', function ($query) use ($projectId) {
                $query->where('project_id', $projectId);
            });

        if ($request->filter === 'week') {
            $stockUsageQuery->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
        } elseif ($request->filter === 'month') {
            $stockUsageQuery->whereMonth('created_at', now()->month)
                            ->whereYear('created_at', now()->year);
        }

        if ($request->section_id) {
            $stockUsageQuery->where('section_id', $request->section_id);
        }

        $stockUsageQuery->whereYear('created_at', $year);
        $stockUsages = $stockUsageQuery->orderBy('created_at', 'desc')->get();

        $sections = Section::all();

        $availableYears = StockUsage::selectRaw('DISTINCT ' . DateRangeQueries::yearColumn('created_at') . ' as year')
            ->orderBy('year', 'desc')
            ->pluck('year');

        if ($request->ajax()) {
            $table = view('materials.partials.stock_usage_table', compact('stockUsages'))
                ->render();

            return response()->json([
                'table' => $table,
            ]);
        }

        return view('materials.stock_usage_history', compact(
            'stockUsages',
            'sections',
            'availableYears',
            'year'
        ));
    }

    public function create()
    {
        $project = $this->resolveActiveProject();
        if (! $project) {
            return redirect()
                ->route('dashboard')
                ->with('warning', __('No project is selected. Please choose a project first.'));
        }

        $projectId = (int) $project->id;
        $suppliers = Supplier::orderBy('name')->get();

        $projectScope = function ($query) use ($projectId) {
            $query->whereHas('bomItem', function ($bomQuery) use ($projectId) {
                $bomQuery->where('project_id', $projectId);
            })->orWhere(function ($adhocQuery) use ($projectId) {
                $adhocQuery->whereNull('bom_item_id')
                    ->whereHas('requester', function ($userQuery) use ($projectId) {
                        $userQuery->where('project_id', $projectId);
                    });
            });
        };

        $approvedRequisitions = Requisition::where('status', 'approved')
            ->where(function ($query) use ($projectScope) {
                $projectScope($query);
            })
            ->with(['bomItem.item_material', 'bomItem.product'])
            ->orderByDesc('approved_at')
            ->orderByDesc('id')
            ->get();

        $deliveredByRequisition = Material::where('project_id', $projectId)
            ->whereNotNull('requisition_id')
            ->selectRaw('requisition_id, SUM(quantity_purchased) as total_delivered')
            ->groupBy('requisition_id')
            ->pluck('total_delivered', 'requisition_id');

        $requisitions = $approvedRequisitions
            ->map(function (Requisition $req) use ($deliveredByRequisition) {
                $itemMaterial = optional($req->bomItem)->item_material;
                $product = optional($req->bomItem)->product;

                $materialName = $itemMaterial->name
                    ?? $product->name
                    ?? $req->extra_material_name
                    ?? __('Unknown Material');

                $unit = $itemMaterial->unit_of_measurement
                    ?? $product->unit
                    ?? $req->extra_unit
                    ?? 'unit';

                $requestedQty = (float) $req->quantity_requested;
                $deliveredQty = (float) ($deliveredByRequisition[$req->id] ?? 0);
                $remainingQty = max(0, $requestedQty - $deliveredQty);

                return (object) [
                    'id' => $req->id,
                    'requisition_no' => $req->requisition_no,
                    'material_name' => $materialName,
                    'unit' => $unit,
                    'requested_quantity' => $requestedQty,
                    'delivered_quantity' => $deliveredQty,
                    'remaining_quantity' => $remainingQty,
                ];
            })
            ->filter(fn ($req) => $req->remaining_quantity > 0.0001)
            ->values();

        return view('materials.create', compact('suppliers', 'requisitions'));
    }


    public function store(StoreMaterialRequest $request)
    {
        $project = $this->resolveActiveProject();
        if (! $project) {
            return redirect()
                ->route('dashboard')
                ->with('warning', __('No project is selected. Please choose a project first.'));
        }

        $projectId = (int) $project->id;

        $projectScope = function ($query) use ($projectId) {
            $query->whereHas('bomItem', function ($bomQuery) use ($projectId) {
                $bomQuery->where('project_id', $projectId);
            })->orWhere(function ($adhocQuery) use ($projectId) {
                $adhocQuery->whereNull('bom_item_id')
                    ->whereHas('requester', function ($userQuery) use ($projectId) {
                        $userQuery->where('project_id', $projectId);
                    });
            });
        };

        $requisition = Requisition::where('id', $request->integer('requisition_id'))
            ->where('status', 'approved')
            ->where(function ($query) use ($projectScope) {
                $projectScope($query);
            })
            ->with(['bomItem.item_material', 'bomItem.product'])
            ->firstOrFail();

        $requestedQty = (float) $requisition->quantity_requested;
        $alreadyDelivered = (float) Material::where('project_id', $projectId)
            ->where('requisition_id', $requisition->id)
            ->sum('quantity_purchased');
        $remainingQty = max(0, $requestedQty - $alreadyDelivered);

        if ($remainingQty <= 0) {
            return back()->withErrors([
                'requisition_id' => __('This requisition has already been fully delivered.'),
            ])->withInput();
        }

        $quantityEntered = (float) $request->input('quantity_in_stock');
        $itemMaterial = optional($requisition->bomItem)->item_material;
        $product = optional($requisition->bomItem)->product;

        $name = $itemMaterial->name
            ?? $product->name
            ?? $requisition->extra_material_name
            ?? __('Unknown Material');

        $unit = $itemMaterial->unit_of_measurement
            ?? $product->unit
            ?? $requisition->extra_unit
            ?? 'unit';

        $varianceValue = round($quantityEntered - $remainingQty, 2);
        if (abs($varianceValue) < 0.005) {
            $varianceValue = 0.0;
        }

        $supplier = Supplier::findOrFail($request->input('supplier_id'));

        $data = [
            'requisition_id' => $requisition->id,
            'name' => $name,
            'product_id' => optional($requisition->bomItem)->product_id,
            'unit_of_measure' => $unit,
            'unit_price' => $request->unit_price,
            'quantity_purchased' => $quantityEntered,
            'quantity_in_stock' => $quantityEntered,
            'variance' => $varianceValue,
            'requisitioned_quantity' => $remainingQty,
            'supplier_id' => $supplier->id,
            'supplier_contact' => $supplier->contact_info,
            'project_id' => $projectId,
        ];

        if ($request->hasFile('document')) {
            $documentPath = $request->file('document')->store('documents', 'public');
            $data['document'] = $documentPath;
        }

        Material::create($data);

        return redirect()->route('materials.delivered')
            ->with('success', 'Material delivery recorded successfully.');
    }

    public function show($id)
    {
        $material = Material::findOrFail($id);
        return view('materials.show', compact('material'));
    }

    public function edit($id)
    {
        $material = Material::findOrFail($id);
        $suppliers = Supplier::all();
        $items = BomItem::where('project_id', project_id())
            ->whereIn('id', function ($query) {
                $query->selectRaw('MIN(bom_items.id)')
                    ->from('bom_items')
                    ->join('item_materials', 'bom_items.item_material_id', '=', 'item_materials.id')
                    ->where('bom_items.project_id', project_id())
                    ->groupBy('item_materials.product_id');
            })
            ->get();

        return view('materials.edit', compact('material', 'suppliers', 'items'));
    }

    public function update(Request $request, $id)
    {

        $material = Material::findOrFail($id);
        $supplier = Supplier::findOrFail($request->supplier_id);
        $bom_item = ItemMaterial::findOrFail($request->bom_item_id);

        $material->update([
            'name' => $bom_item->name,
            'product_id' => $bom_item->product_id,
            'unit_of_measure' => $bom_item->unit_of_measurement,
            'unit_price' => $request->unit_price,
            'quantity_in_stock' => $request->quantity_in_stock,
            'supplier_id' => $supplier->id,
            'supplier_contact' => $supplier->contact_info,
        ]);

        if ($request->hasFile('document')) {
            $documentPath = $request->file('document')->store('documents', 'public');
            $material->update(['document' => $documentPath]);
        }

        return redirect()->route('materials.index')->with('success', 'Material updated successfully!');
    }

    public function destroy(Material $material)
    {
        $supplier = $material->supplier;

        $material->delete();

        if ($supplier) {
            $remainingMaterials = $supplier->materials()->count();

            if ($remainingMaterials === 0) {
                $supplier->delete();
            } else {
                $remainingMaterialNames = $supplier->materials()->pluck('name')->toArray();
                $supplier->update([
                    'material_supplied' => implode(', ', $remainingMaterialNames),
                ]);
            }
        }

        return redirect()->route('materials.index')->with('success', 'Material and related supplier information updated successfully.');
    }

    public function viewDocument($id)
    {
        $material = Material::findOrFail($id);

        if ($material->document) {
            $documentUrl = 'storage/' . $material->document;

            return view('materials.document', compact('documentUrl'));
        }

        return redirect()->route('materials.index')->with('error', 'Receipt not found.');
    }

    public function useMaterial(Request $request, $key)
    {
        $isAdhoc = !ctype_digit((string) $key);

        $rules = [
            'quantity_used' => 'required|numeric|min:0.01',
            'section_id' => 'required|exists:sections,id',
        ];

        if ($isAdhoc) {
            $rules['adhoc_name'] = 'required|string|max:255';
            $rules['adhoc_unit'] = 'required|string|max:50';
        }

        $validated = $request->validate($rules);

        $projectId = Auth::user()->project_id;

        $materialQuery = Material::where('project_id', $projectId)
            ->where('quantity_in_stock', '>', 0);

        if ($isAdhoc) {
            $materialQuery->whereNull('product_id')
                ->where('name', $validated['adhoc_name'])
                ->where('unit_of_measure', $validated['adhoc_unit']);
        } else {
            $materialQuery->where('product_id', $key);
        }

        // Consume stock FIFO across multiple batches if necessary
        $batches = $materialQuery->orderBy('created_at', 'asc')->get();
        if ($batches->isEmpty()) {
            return response()->json(['error' => 'No material found in stock.'], 400);
        }

        $required = (float) $validated['quantity_used'];
        $remainingToConsume = $required;

        foreach ($batches as $batch) {
            if ($remainingToConsume <= 0) break;
            $available = (float) $batch->quantity_in_stock;
            if ($available <= 0) continue;

            $take = min($available, $remainingToConsume);
            // Update batch stock
            $batch->quantity_in_stock = $available - $take;
            $batch->save();

            // Record usage for this batch
            StockUsage::create([
                'material_id' => $batch->id,
                'quantity_used' => $take,
                'section_id' => $validated['section_id'],
            ]);

            $remainingToConsume -= $take;
        }

        if ($remainingToConsume > 0) {
            // Rollback-like notice would be more complex; inform client of partial availability
            $availableTotal = $required - $remainingToConsume;
            return response()->json(['error' => "Only $availableTotal available in stock."], 400);
        }

        $remainingStock = Material::where('project_id', $projectId)
            ->when($isAdhoc, function ($query) use ($validated) {
                $query->whereNull('product_id')
                    ->where('name', $validated['adhoc_name'])
                    ->where('unit_of_measure', $validated['adhoc_unit']);
            }, function ($query) use ($key) {
                $query->where('product_id', $key);
            })
            ->sum('quantity_in_stock');

        return response()->json([
            'success' => 'Material usage recorded and stock updated.',
            'remaining_stock' => $remainingStock,
        ]);
    }
}
