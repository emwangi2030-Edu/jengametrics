<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Material;
use App\Models\Supplier;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Log;


class MaterialController extends Controller
{
    public function index()
    {
        $materials = Material::with('supplier')->get();
        return view('materials.index', compact('materials'));
    }

    public function create()
    {
        $suppliers = Supplier::all(); // Get all suppliers to populate dropdown
        return view('materials.create', compact('suppliers'));
    }

    public function store(Request $request)
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

        // Check if the supplier exists, or create a new one
        $supplier = Supplier::firstOrCreate(
            ['name' => $request->supplier_name],
            ['contact_info' => $request->supplier_contact]
        );

        // Initialize validated data array
        $validatedData = $request->only([
            'name', 
            'unit_price', 
            'unit_of_measure', 
            'quantity_in_stock'
        ]);

        // Assign supplier information
        $validatedData['supplier_id'] = $supplier->id;

        // Handle document upload
        if ($request->hasFile('document')) {
            $documentPath = $request->file('document')->store('documents', 'public');
            $validatedData['document'] = $documentPath;
        } else {
            $validatedData['document'] = null; // Set to null if no document is uploaded
        }

        // Create material using the validated data
        Material::create($validatedData);

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
        // Validate the incoming data
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
    
        // Update the material's attributes
        $material->update([
            'name' => $request->name,
            'unit_price' => $request->unit_price,
            'unit_of_measure' => $request->unit_of_measure,
            'quantity_in_stock' => $request->quantity_in_stock,
            'supplier_id' => $supplier->id,
            'document' => $material->document,
        ]);
    
        // Redirect back to the materials index with a success message
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
