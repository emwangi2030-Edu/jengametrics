<?php

namespace App\Http\Controllers;

use App\Models\ItemMaterial;
use App\Models\Item;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\UnitOfMeasurement;

class ItemMaterialController extends Controller
{
    // Show all materials for a given item
    public function index($itemId)
    {
        $item = Item::findOrFail($itemId);
        $materials = ItemMaterial::where('item_id', $itemId)->get();
        $units = UnitOfMeasurement::all(); // Fetch all units of measurement
        $products = Product::orderBy('name', 'asc')->get();

        return view('admin.sections.materials', compact('item', 'materials', 'units', 'products'));
    }


    // Store a new material
    public function store(Request $request)
    {

        $product = Product::find($request->product_id);

        $data = [
            'product_id' => $request->product_id,
            'name' => $product->name,
            'unit_of_measurement' => $request->unit_of_measurement,
            'conversion_factor'    => $request->conversion_factor,
            'item_id'              => $request->item_id,
            'rate' => $request->rate
        ];

        ItemMaterial::create($data);

        return redirect()->route('items.materials', $request->item_id)->with('success', 'Material added successfully.');
    }

    // Update material
    public function update(Request $request, $id)
    {
       

        // Find the item material by ID
        $itemMaterial = ItemMaterial::findOrFail($id);
        $product = Product::find($request->product_id);
        // Update the item material with the validated data

     
        $itemMaterial->update([
            'product_id' => $request->product_id,
            'name'       => $product->name,
            'unit_of_measurement' => $request->unit_of_measurement,
            'conversion_factor' => $request->conversion_factor,
        ]);

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Material updated successfully.');
    }

    // Delete material
    public function destroy($id)
    {
        $itemMaterial = ItemMaterial::findOrFail($id);
        $itemMaterial->delete();
    
        return redirect()->back()->with('success', 'Material deleted successfully.');
    }

    // Index all materials
    public function index_materials()
    {
        $materials = ItemMaterial::all(['name']);
        return view('admin.sections.products', compact('materials'));
    }


    // Private validation methods for store and update
    private function validateStoreRequest(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'item_id' => 'required|exists:items,id',
            'unit_of_measurement' => 'required|exists:units_of_measurement,abbrev',
            'conversion_factor' => 'required|numeric',
        ]);
    }

    private function validateUpdateRequest(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'unit_of_measurement' => 'required|exists:units_of_measurement,abbrev',
            'conversion_factor' => 'required|numeric|min:0',
        ]);
    }
}
