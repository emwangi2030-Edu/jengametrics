<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Material;
use App\Models\ItemMaterial;
use App\Models\Supplier;
use App\Models\BomItem;
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
        $materials = Material::with('supplier')
            ->where('project_id', $projectId)
            ->get();

        return view('materials.index', compact('materials'));
    }

    public function create()
    {
        $suppliers = Supplier::all(); // Get all suppliers to populate the dropdown
        $items = BomItem::where('project_id', project_id())
               ->distinct()
               ->get(['item_material_id']);

        return view('materials.create', compact('suppliers', 'items'));
    }

public function store(Request $request)
{



    // Check if the supplier exists, or create a new one
    $supplier = Supplier::firstOrCreate(
        ['name' => $request->supplier_name],
        ['contact_info' => $request->supplier_contact ?? ''] // Ensure contact_info is set even if it's null
    );


   $bom_item = ItemMaterial::find($request->bom_item_id);


    // Initialize the data array
    $data = [
        'product_id' => $bom_item->product_id,
        'unit_of_measure' => $bom_item->unit_of_measurement,
        'unit_price' => $request->unit_price,
        'quantity_in_stock' => $request->quantity_in_stock,
        'supplier_id' => $supplier->id,
        'supplier_contact' => $request->supplier_contact ?? '', // Ensure contact_info is included even if null
        'project_id' => project_id(), // Assuming project_id() is a helper function
    ];

    // Handle document upload if it exists
    if ($request->hasFile('document')) {
        $documentPath = $request->file('document')->store('documents', 'public');
        $data['document'] = $documentPath;
    }

    // Create material
    Material::create($data);

    // Redirect with success message
    return redirect()->route('materials.index')->with('success', 'Material added successfully!');
}



    public function show($id)
    {
        $material = Material::findOrFail($id);
        return view('materials.show', compact('material'));
    }

    public function edit($id)
    {
        $material = Material::with('supplier')->findOrFail($id);
        return view('materials.edit', compact('material'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'unit_price' => 'required|numeric',
            'unit_of_measure' => 'required|string|max:255',
            'quantity_in_stock' => 'required|integer',
            'supplier_name' => 'required|string|max:255',
            'supplier_contact' => 'nullable|string|max:255',
            'document' => 'nullable|file|mimes:pdf,doc,docx,txt,jpg,jpeg,png|max:2048',
        ]);

        // Find the material
        $material = Material::findOrFail($id);

        // Handle document upload
        if ($request->hasFile('document')) {
            $documentPath = $request->file('document')->store('documents', 'public');
            $material->document = $documentPath;  // Assign new document path
        }

        // Check if the supplier exists, or create a new one
        $supplier = Supplier::firstOrCreate(
            ['name' => $request->supplier_name],
            ['contact_info' => $request->supplier_contact]
        );

        // Get the current user's project_id
        $projectId = Auth::user()->project_id;

        // Update the material's attributes
        $material->update([
            'name' => $request->name,
            'unit_price' => $request->unit_price,
            'unit_of_measure' => $request->unit_of_measure,
            'quantity_in_stock' => $request->quantity_in_stock,
            'supplier_id' => $supplier->id,
            'project_id' => $projectId, // Ensure project_id is updated
            'document' => $material->document,
        ]);

        return redirect()->route('materials.index')->with('success', 'Material and Supplier updated successfully.');
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
