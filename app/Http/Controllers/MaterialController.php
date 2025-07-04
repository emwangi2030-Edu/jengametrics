<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Material;
use App\Models\ItemMaterial;
use App\Models\Supplier;
use App\Models\BomItem;
use App\Models\Project;
use App\Models\Requisition;
use App\Models\UnitOfMeasurement;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class MaterialController extends Controller
{
    public function index()
    {
        // Fetch the project_id of the current user
        $projectId = Auth::user()->project_id;

        // Retrieve materials associated with the project
        $materials = Material::with('supplier', 'requisition')
            ->where('project_id', $projectId)
            ->get();

        $project = Project::find($projectId);

        return view('materials.index', compact('materials', 'project'));
    }

    public function create()
    {
        $suppliers = Supplier::all(); // Get all suppliers
        $items = BomItem::whereIn('id', function ($query) {
            $query->select('bom_item_id')
                ->from('requisitions')
                ->where('status', 'approved');
        })->with(['item_material'])
        ->where('project_id', project_id())
        ->get();

        // Also pass approved requisitions with quantity
        $requisitions = Requisition::where('status', 'approved')
            ->doesntHave('material') // Exclude already-purchased ones
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
            'requisition_id' => 'required|exists:requisitions,id'
        ]);

        $supplier = Supplier::find($request->supplier_id);
        $bom_item = ItemMaterial::find($request->bom_item_id);

        $data = [
            'name' => $bom_item->name,
            'product_id' => $bom_item->product_id,
            'unit_of_measure' => $bom_item->unit_of_measurement,
            'unit_price' => $request->unit_price,
            'quantity_in_stock' => $request->quantity_in_stock,
            'supplier_id' => $supplier->id,
            'supplier_contact' => $supplier->contact_info,
            'project_id' => Auth::user()->project_id,
            'requisition_id' => $request->requisition_id,
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

        return redirect()->route('materials.index')->with('error', 'Document not found.');
    }
}
