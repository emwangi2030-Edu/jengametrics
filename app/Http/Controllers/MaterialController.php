<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Material;
use App\Models\ItemMaterial;
use App\Models\Supplier;
use App\Models\BomItem;
use App\Models\Project;
use App\Models\Requisition;
use App\Models\StockUsage;
use App\Models\Section;
use App\Models\Product;
use App\Models\UnitOfMeasurement;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class MaterialController extends Controller
{
    public function index(Request $request)
    {
        $projectId = Auth::user()->project_id;
        $year = $request->input('year', now()->year);

        // Build materials query
        $materialsQuery = Material::with('supplier', 'requisition')
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

        $rawItems = BomItem::whereProjectId($projectId)->get();
        $groupedItems = $rawItems->groupBy('product_id');

        $requisitionableItems = collect();

        foreach ($groupedItems as $product_id => $group) {
            $sampleItem = $group->first();
            $totalQty = $group->sum('quantity');
            $product = Product::find($product_id);
            $sampleItem->unit = $product?->unit ?? 'unit';
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
        $availableYears = Material::selectRaw('DISTINCT YEAR(created_at) as year')
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
        $projectId = Auth::user()->project_id;
        $year = $request->input('year', now()->year);

        $materialsQuery = Material::with('supplier', 'requisition')
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

        $availableYears = Material::selectRaw('DISTINCT YEAR(created_at) as year')
            ->orderBy('year', 'desc')
            ->pluck('year');

        $project = Project::find($projectId);

        $bomItems = BomItem::select(
                'product_id',
                DB::raw('SUM(quantity) as total_quantity')
            )
            ->where('project_id', $projectId)
            ->whereNotNull('product_id')
            ->groupBy('product_id')
            ->with('product:id,name,unit')
            ->get();


        foreach ($bomItems as $item) {
            $approvedQty = Requisition::whereIn('status', ['approved', 'pending'])
                ->whereHas('bomItem', function ($query) use ($projectId, $item) {
                    $query->where('product_id', $item->product_id)
                        ->where('project_id', $projectId);
                })
                ->sum('quantity_requested');

            $item->remaining_quantity = $item->total_quantity - $approvedQty;
        }

        $requisitionableItems = $bomItems->filter(fn($item) => $item->remaining_quantity > 0);

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
        $projectId = Auth::user()->project_id;

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
        $projectId = Auth::user()->project_id;
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

        $availableYears = StockUsage::selectRaw('DISTINCT YEAR(created_at) as year')
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
        $projectId = Auth::user()->project_id;
        $suppliers = Supplier::all();

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
            ->with(['bomItem.item_material', 'requester'])
            ->get()
            ->map(function ($req) {
                if ($req->bom_item_id && $req->bomItem && $req->bomItem->item_material) {
                    $itemMaterial = $req->bomItem->item_material;

                    return (object) [
                        'key' => 'product_' . $req->bomItem->product_id,
                        'type' => 'bom',
                        'product_id' => $req->bomItem->product_id,
                        'name' => $itemMaterial->name,
                        'unit' => $itemMaterial->unit_of_measurement,
                        'total_requested' => $req->quantity_requested,
                    ];
                }

                if (!$req->bom_item_id) {
                    return (object) [
                        'key' => 'adhoc_' . Str::slug($req->extra_material_name . '_' . $req->extra_unit),
                        'type' => 'adhoc',
                        'product_id' => null,
                        'name' => $req->extra_material_name,
                        'unit' => $req->extra_unit,
                        'total_requested' => $req->quantity_requested,
                    ];
                }

                return null;
            })
            ->filter()
            ->groupBy('key')
            ->map(function ($group) {
                $first = $group->first();

                return (object) [
                    'key' => $first->key,
                    'type' => $first->type,
                    'product_id' => $first->product_id,
                    'name' => $first->name,
                    'unit' => $first->unit,
                    'total_requested' => $group->sum('total_requested'),
                ];
            });

        $purchases = Material::where('project_id', $projectId)
            ->get()
            ->groupBy(function ($material) {
                if ($material->product_id) {
                    return 'product_' . $material->product_id;
                }

                return 'adhoc_' . Str::slug($material->name . '_' . $material->unit_of_measure);
            })
            ->map(function ($group) {
                return $group->sum(function ($material) {
                    $reference = $material->requisitioned_quantity;

                    if (is_null($reference) || $reference <= 0) {
                        return (float) $material->quantity_purchased;
                    }

                    return min((float) $material->quantity_purchased, (float) $reference);
                });
            });

        $requisitions = $approvedRequisitions
            ->map(function ($item) use ($purchases) {
                $totalRequested = (float) $item->total_requested;
                $totalPurchased = (float) ($purchases[$item->key] ?? 0);

                $item->requested_quantity = $totalRequested;
                $item->remaining_quantity = max(0.0, $totalRequested - $totalPurchased);

                return $item;
            })
            ->filter(fn ($item) => $item->remaining_quantity > 0)
            ->values();

        return view('materials.create', compact('suppliers', 'requisitions'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'material_type' => 'required|in:bom,adhoc',
            'unit_price' => 'required|numeric',
            'quantity_in_stock' => 'required|numeric|min:0.01',
            'supplier_id' => 'required|exists:suppliers,id',
            'requisitioned_quantity' => 'nullable|numeric',
            'expected_quantity' => 'nullable|numeric',
            'variance' => 'nullable|string',
            'product_id' => 'nullable|integer',
            'adhoc_name' => 'nullable|string|max:255',
            'adhoc_unit' => 'nullable|string|max:50',
        ]);

        $materialType = $request->input('material_type');
        $quantityEntered = (float) $request->input('quantity_in_stock');
        $productId = $request->input('product_id');

        if ($materialType === 'bom') {
            if (!$productId) {
                return back()->withErrors([
                    'product_id' => 'Please select a material from approved requisitions.',
                ])->withInput();
            }

            $product = Product::findOrFail($productId);
            $name = $product->name;
            $unit = $product->unit;
        } else {
            $adhocName = $request->input('adhoc_name');
            $adhocUnit = $request->input('adhoc_unit');

            if (!$adhocName || !$adhocUnit) {
                return back()->withErrors([
                    'adhoc_name' => 'Please provide both material name and unit of measure for ad-hoc purchases.',
                ])->withInput();
            }

            $productId = null;
            $name = $adhocName;
            $unit = $adhocUnit;
        }

        $expectedQty = $request->filled('expected_quantity')
            ? (float) $request->input('expected_quantity')
            : null;

        $projectId = Auth::user()->project_id;

        $requisitionedQty = $expectedQty
            ?? ($request->filled('requisitioned_quantity')
                ? (float) $request->input('requisitioned_quantity')
                : ($materialType === 'adhoc' ? $quantityEntered : 0.0));

        $varianceValue = 0.0;

        if ($request->filled('variance')) {
            $varianceValue = (float) $request->input('variance');
        } elseif ($materialType === 'bom') {
            $referenceQty = $expectedQty
                ?? ($request->filled('requisitioned_quantity')
                    ? (float) $request->input('requisitioned_quantity')
                    : 0.0);

            $varianceValue = $quantityEntered - $referenceQty;
        }

        $varianceValue = round($varianceValue, 2);

        if (abs($varianceValue) < 0.005) {
            $varianceValue = 0.0;
        }

        $supplier = Supplier::findOrFail($request->input('supplier_id'));

        $data = [
            'name' => $name,
            'product_id' => $productId,
            'unit_of_measure' => $unit,
            'unit_price' => $request->unit_price,
            'quantity_purchased' => $quantityEntered,
            'quantity_in_stock' => $quantityEntered,
            'variance' => $varianceValue,
            'requisitioned_quantity' => $requisitionedQty,
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
            ->with('success', 'Material recorded successfully!');
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
        $request->validate([
            'bom_item_id' => 'required|exists:item_materials,id',
            'unit_price' => 'required|numeric',
            'quantity_in_stock' => 'required|numeric',
            'supplier_id' => 'required|exists:suppliers,id',
        ]);

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

        $material = $materialQuery->oldest()->first();

        if (!$material) {
            return response()->json(['error' => 'No material found in stock.'], 400);
        }

        if ($validated['quantity_used'] > $material->quantity_in_stock) {
            return response()->json(['error' => 'Not enough stock available.'], 400);
        }

        $material->quantity_in_stock -= $validated['quantity_used'];
        $material->save();

        StockUsage::create([
            'material_id' => $material->id,
            'quantity_used' => $validated['quantity_used'],
            'section_id' => $validated['section_id'],
        ]);

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
