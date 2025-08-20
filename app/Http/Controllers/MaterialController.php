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
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

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

        // Get delivered materials
        $materialsQuery = Material::with('supplier', 'requisition')
            ->where('project_id', $projectId);

        if ($request->filter === 'week') {
            $materialsQuery->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
        } elseif ($request->filter === 'month') {
            $materialsQuery->whereMonth('created_at', now()->month)
                        ->whereYear('created_at', now()->year);
        }

        $materialsQuery->whereYear('created_at', $year);
        $materials = $materialsQuery->get();

        // Available years for filter dropdown
        $availableYears = Material::selectRaw('DISTINCT YEAR(created_at) as year')
            ->orderBy('year', 'desc')
            ->pluck('year');

        $project = Project::find($projectId);

        $bomItems = BomItem::select('product_id', 'name', 'unit_of_measure', DB::raw('SUM(quantity) as total_quantity'))
            ->whereHas('bom', function ($q) use ($projectId) {
                $q->where('project_id', $projectId);
            })
            ->groupBy('product_id', 'name', 'unit_of_measure')
            ->get();

        foreach ($bomItems as $item) {
            $approvedQty = Requisition::where('product_id', $item->product_id)
                ->whereIn('status', ['approved', 'pending'])
                ->sum('quantity');

            $item->remaining_quantity = $item->total_quantity - $approvedQty;
        }

        // Only items with stock still available for requisition
        $requisitionableItems = $bomItems->filter(fn($item) => $item->remaining_quantity > 0);

        return view('materials.materials_delivered', compact(
            'materials',
            'project',
            'availableYears',
            'year',
            'requisitionableItems'
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

        return view('materials.stock_usage_history', compact(
            'stockUsages',
            'sections',
            'availableYears',
            'year'
        ));
    }

    public function create()
    {
        $suppliers = Supplier::all();

        // Group all approved requisitions by product_id and sum quantity_requested
        $groupedRequisitions = Requisition::where('status', 'approved')
            ->with('bomItem.item_material')
            ->get()
            ->groupBy(fn($req) => $req->bomItem->product_id)
            ->map(function ($group) {
                $first = $group->first();
                return (object) [
                    'product_id' => $first->bomItem->product_id,
                    'material' => $first->bomItem->item_material,
                    'total_requested' => $group->sum('quantity_requested'),
                    'bom_item_ids' => $group->pluck('bom_item_id')->unique(),
                ];
            });

        // Get purchased quantities grouped by product_id
        $purchasedQuantities = Material::select('product_id')
            ->selectRaw('SUM(quantity_purchased) as total_purchased')
            ->where('project_id', project_id())
            ->groupBy('product_id')
            ->pluck('total_purchased', 'product_id');

        // Filter to only those with remaining quantity > 0
        $requisitions = $groupedRequisitions->filter(function ($item) use ($purchasedQuantities) {
            $purchased = $purchasedQuantities[$item->product_id] ?? 0;
            return $item->total_requested > $purchased;
        })->map(function ($item) use ($purchasedQuantities) {
            $purchased = $purchasedQuantities[$item->product_id] ?? 0;
            $item->remaining_quantity = $item->total_requested - $purchased;
            return $item;
        });

        return view('materials.create', compact('suppliers', 'requisitions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'unit_price' => 'required|numeric',
            'quantity_in_stock' => 'required|numeric|min:0.01',
            'supplier_id' => 'required|exists:suppliers,id',
            'requisitioned_quantity' => 'nullable|numeric',
        ]);

        $productId = $request->input('product_id');
        $quantityEntered = $request->input('quantity_in_stock');

        // Get all approved requisitions for this product
        $approvedRequisitions = Requisition::where('status', 'approved')
            ->whereHas('bomItem', function ($query) use ($productId) {
                $query->where('product_id', $productId);
            })
            ->get();

        $totalApproved = $approvedRequisitions->sum('quantity_requested');

        // Get total purchased so far
        $totalPurchased = Material::where('product_id', $productId)
            ->where('project_id', project_id())
            ->sum('quantity_purchased');

        $remainingQty = $totalApproved - $totalPurchased;

        $variance = $quantityEntered - $remainingQty;

        // Get product details
        $product = Product::findOrFail($productId);

        // Get supplier info
        $supplier = Supplier::findOrFail($request->input('supplier_id'));

        // Prepare data for saving
        $data = [
            'name' => $product->name,
            'product_id' => $product->id,
            'unit_of_measure' => $product->unit,
            'unit_price' => $request->unit_price,
            'quantity_purchased' => $quantityEntered,
            'quantity_in_stock' => $quantityEntered,
            'variance' => $variance,
            'requisitioned_quantity' => $request->requisitioned_quantity,
            'supplier_id' => $supplier->id,
            'supplier_contact' => $supplier->contact_info,
            'project_id' => Auth::user()->project_id,
        ];

        // Handle optional document upload
        if ($request->hasFile('document')) {
            $documentPath = $request->file('document')->store('documents', 'public');
            $data['document'] = $documentPath;
        }

        Material::create($data);

        return redirect()->route('materials.index')->with('success', 'Material recorded successfully!');
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
        // Get the supplier associated with this material
        $supplier = $material->supplier;

        // Delete the material
        $material->delete();

        // Check how many materials the supplier has left
        $remainingMaterials = $supplier->materials()->count();

        if ($remainingMaterials === 0) {
            // If no materials are left, delete the supplier as well
            $supplier->delete();
        } else {
            // If there are other materials, update the 'material_supplied' column
            $remainingMaterialNames = $supplier->materials()->pluck('name')->toArray();
            $supplier->update([
                'material_supplied' => implode(', ', $remainingMaterialNames),
            ]);
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

    public function useMaterial(Request $request, $id)
    {
        $request->validate([
            'quantity_used' => 'required|numeric|min:0.01',
            'section_id' => 'required|exists:sections,id', // must match a section
        ]);

        // Find the first available material batch for this product
        $material = Material::where('product_id', $id)
            ->where('quantity_in_stock', '>', 0)
            ->orderBy('created_at', 'asc')
            ->firstOrFail();

        // Check if stock is sufficient
        if ($request->quantity_used > $material->quantity_in_stock) {
            return back()->withErrors(['quantity_used' => 'Not enough stock available.']);
        }

        // Deduct stock
        $material->quantity_in_stock -= $request->quantity_used;
        $material->save();

        // Log usage
        StockUsage::create([
            'material_id' => $material->id,
            'quantity_used' => $request->quantity_used,
            'section_id' => $request->section_id,
        ]);

        return back()->with('success', 'Material usage recorded and stock updated.');
    }
}
