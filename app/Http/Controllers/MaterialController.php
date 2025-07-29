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
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class MaterialController extends Controller
{
    public function index()
    {
        $projectId = Auth::user()->project_id;

        $materials = Material::with('supplier', 'requisition')
            ->where('project_id', $projectId)
            ->get();

        $inventory = Material::select('product_id', 'name', 'unit_of_measure')
            ->selectRaw('SUM(quantity_in_stock) as total_stock')
            ->where('project_id', $projectId)
            ->groupBy('product_id', 'name', 'unit_of_measure')
            ->get();

        $sections = Section::all();

        $stockUsages = StockUsage::with(['material', 'section'])
            ->whereHas('material', function ($query) use ($projectId) {
                $query->where('project_id', $projectId);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        $project = Project::find($projectId);

        return view('materials.index', compact('materials', 'project', 'inventory', 'sections', 'stockUsages'));
    }

    public function create()
    {
        $suppliers = Supplier::all();
        $items = BomItem::whereIn('id', function ($query) {
            $query->select('bom_item_id')
                ->from('requisitions')
                ->where('status', 'approved');
        })->with(['item_material'])
        ->where('project_id', project_id())
        ->get();

        // Also pass approved requisitions with quantity
        $requisitions = Requisition::select('bom_item_id')
            ->selectRaw('SUM(quantity_requested) as total_quantity')
            ->where('status', 'approved')
            ->groupBy('bom_item_id')
            ->with('bomItem.item_material')
            ->get();

        return view('materials.create', compact('suppliers', 'items', 'requisitions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'bom_item_id' => 'required|exists:item_materials,id',
            'unit_price' => 'required|numeric',
            'quantity_in_stock' => 'required|numeric',
            'supplier_id' => 'required|exists:suppliers,id',
        ]);

        $supplier = Supplier::findOrFail($request->input('supplier_id'));
        $bom_item = ItemMaterial::findOrFail($request->input('bom_item_id'));

        $data = [
            'name' => $bom_item->name,
            'product_id' => $bom_item->product_id,
            'unit_of_measure' => $bom_item->unit_of_measurement,
            'unit_price' => $request->unit_price,
            'quantity_purchased' => $request->quantity_in_stock,
            'quantity_in_stock' => $request->quantity_in_stock,
            'supplier_id' => $supplier->id,
            'supplier_contact' => $supplier->contact_info,
            'project_id' => Auth::user()->project_id,
        ];

        if ($request->hasFile('document')) {
            $documentPath = $request->file('document')->store('documents', 'public');
            $data['document'] = $documentPath;
        }

        Material::create($data);

        return redirect()->route('materials.index')->with('success', 'Material added successfully!');
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
