<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\SubElement;
use App\Models\ItemUnitOfMeasurement;
use Illuminate\Http\Request;

class ItemsController extends Controller
{
    // Show all items for a given sub-element
    public function index($subElementId)
    {
        $subElement = SubElement::findOrFail($subElementId);
        $items = Item::where('sub_element_id', $subElementId)->get();
        $units = ItemUnitOfMeasurement::all();

        return view('admin.sections.items', compact('subElement', 'items', 'units'));
    }

    // Store a new item
    public function store(Request $request)
    {
        



        // Create the item with validated data, including the abbrev
        Item::create([
            'name' => $request->name,
            'labour' => $request->labour,
            'description' => $request->description,
            'sub_element_id' => $request->sub_element_id,
            'unit_of_measurement' => $request->unit_of_measurement,
        ]);

        return redirect()->route('subelements.items', $request->sub_element_id)->with('success', 'Item added successfully.');
    }

    // Update an item
   public function update(Request $request, $id)
    {
        $subElement = Item::findOrFail($id);
        $request->validate([
            'name' => 'required|string|max:255',
            'labour' => 'nullable|string|max:255',
            'unit_of_measurement' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $subElement->update($request->all());

        return redirect()->back()->with('success', 'Item updated successfully.');
    }


    // Delete an item
    public function destroy($id)
    {
        $item = Item::findOrFail($id);
        $item->delete();

        return redirect()->back()->with('success', 'Item deleted successfully.');
    }
}
