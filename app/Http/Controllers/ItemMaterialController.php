<?php

namespace App\Http\Controllers;

use App\Models\ItemMaterial;
use App\Models\Item;
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

        return view('admin.sections.materials', compact('item', 'materials', 'units'));
    }

    // Store a new material
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'item_id' => 'required|exists:items,id',
            'unit_of_measurement' => 'required|exists:units_of_measurement,name',
            'conversion_factor' => 'required|numeric',
        ]);

        ItemMaterial::create($request->all());

        return redirect()->route('items.materials', $request->item_id)->with('success', 'Material added successfully.');
    }

    // Update material
    public function update(Request $request, $id)
    {
        // Validate the incoming request
        $request->validate([
            'name' => 'required|string|max:255',
            'unit_of_measurement' => 'required|exists:units_of_measurement,name',
            'conversion_factor' => 'required|numeric|min:0',
        ]);

        // Find the item material by ID
        $itemMaterial = ItemMaterial::findOrFail($id);

        // Update the item material with the validated data
        $itemMaterial->update([
            'name' => $request->name,
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
}
