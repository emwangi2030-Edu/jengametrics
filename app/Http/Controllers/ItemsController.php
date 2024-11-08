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
        // Validate and ensure unit_of_measurement uses abbrev from item_unit_of_measurements
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'sub_element_id' => 'required|exists:sub_elements,id',
            'unit_of_measurement' => 'required|exists:item_unit_of_measurements,abbrev'
        ]);

        // Get the corresponding abbrev value for the selected unit of measurement
        $unit = ItemUnitOfMeasurement::where('abbrev', $request->unit_of_measurement)->firstOrFail();

        // Create the item with validated data, including the abbrev
        Item::create([
            'name' => $request->name,
            'description' => $request->description,
            'sub_element_id' => $request->sub_element_id,
            'unit_of_measurement' => $unit->name,
            'abbrev' => $unit->abbrev,
        ]);

        return redirect()->route('subelements.items', $request->sub_element_id)->with('success', 'Item added successfully.');
    }

    // Update an item
    public function update(Request $request, $id)
    {
        $item = Item::findOrFail($id);

        // Validate the request and ensure unit_of_measurement uses abbrev from item_unit_of_measurements
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'unit_of_measurement' => 'required|exists:item_unit_of_measurements,abbrev'
        ]);

        // Get the corresponding abbrev value for the selected unit of measurement
        $unit = ItemUnitOfMeasurement::where('abbrev', $request->unit_of_measurement)->firstOrFail();

        // Update the item with validated data, including the abbrev
        $item->update([
            'name' => $request->name,
            'description' => $request->description,
            'unit_of_measurement' => $unit->name,
            'abbrev' => $unit->abbrev,
        ]);

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
