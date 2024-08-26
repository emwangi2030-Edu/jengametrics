<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Material;
use App\Models\Supplier;


class MaterialController extends Controller
{
    // In your index method in MaterialsController
    public function index()
    {
        $materials = Material::with('supplier')->get(); // Eager loading suppliers
        return view('materials.index', compact('materials'));
    }

    public function create()
    {
        return view('materials.create');
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
        ]);

        // Check if the supplier exists, or create a new one
        $supplier = Supplier::firstOrCreate(
            ['name' => $request->supplier_name],
            ['contact_info' => $request->supplier_contact]
        );

        Material::create([
            'name' => $request->name,
            'unit_price' => $request->unit_price,
            'unit_of_measure' => $request->unit_of_measure,
            'quantity_in_stock' => $request->quantity_in_stock,
            'supplier_id' => $supplier->id,
            'supplier_contact' => $request->supplier_contact,
        ]);

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
            'supplier_id' => 'required|exists:suppliers,id',
            'supplier_name' => 'required|string|max:255',
            'supplier_contact' => 'nullable|string|max:255',
        ]);

        // Find the material
        $material = Material::findOrFail($id);

        // Update the material's attributes
        $material->update([
            'name' => $request->name,
            'unit_price' => $request->unit_price,
            'unit_of_measure' => $request->unit_of_measure,
            'quantity_in_stock' => $request->quantity_in_stock,
            'supplier_id' => $request->supplier_id,
        ]);

        // Update the supplier's name and contact info
        $supplier = Supplier::findOrFail($request->supplier_id);
        $supplier->update([
            'name' => $request->supplier_name,
            'contact_info' => $request->supplier_contact,
        ]);

        // Redirect back to the materials index with a success message
        return redirect()->route('materials.index')->with('success', 'Material and Supplier Contact updated successfully.');
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
}
