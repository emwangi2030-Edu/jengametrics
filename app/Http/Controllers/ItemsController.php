<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Element;
use App\Models\ItemUnitOfMeasurement;
use Illuminate\Http\Request;

class ItemsController extends Controller
{
    // Show all items for a given sub-element
    public function index($elementId)
    {


        $element = Element::findOrFail($elementId);
        $items = Item::where('element_id', $elementId)->get();

        $units = ItemUnitOfMeasurement::all();

        return view('admin.sections.items', compact('element', 'items', 'units'));
    }

    // Store a new item
    public function store(Request $request)
    {
        // Create the item with validated data, including the abbrev
        Item::create([
            'name' => $request->name,
            'labour' => $request->labour,
            'element_id' => $request->element_id,
            'unit_of_measurement' => $request->unit_of_measurement,
        ]);

        return redirect()->route('subelements.items', $request->element_id)->with('success', 'Item added successfully.');
    }

    // Update an item
   public function update(Request $request, $id)
    {
        $item = Item::findOrFail($id);
        $request->validate([
            'name' => 'required|string|max:255',
            'labour' => 'nullable|string|max:255',
            'unit_of_measurement' => 'required|string|max:255',
        ]);

        $item->update($request->all());

        return redirect()->back()->with('success', 'Item updated successfully.');
    }


    // Delete an item
    public function destroy($id)
    {
        $item = Item::findOrFail($id);
        $item->delete();

        return redirect()->back()->with('success', 'Item deleted successfully.');
    }

    public function getItemsByElement(Request $request)
    {
        $elementId = $request->get('element_id');

        if (!$elementId) {
            return response()->json([], 400);
        }

        $items = Item::where('element_id', $elementId)
            ->orderBy('name')
            ->get()
            ->map(function (Item $item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'unit' => $item->unit_of_measurement,
                ];
            });

        return response()->json($items);
    }

    public function getItemsDetails(Request $request)
    {
        $validated = $request->validate([
            'element_id' => 'required|exists:elements,id',
        ]);

        $items = Item::query()
            ->with(['Element.section'])
            ->where('element_id', $validated['element_id'])
            ->orderBy('name')
            ->get()
            ->map(function (Item $item) {
                $element = $item->Element;
                $section = $element?->section;

                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'unit' => $item->unit_of_measurement,
                    'element_id' => $element?->id,
                    'element_name' => $element?->name,
                    'section_id' => $section?->id,
                    'section_name' => $section?->name,
                ];
            });

        return response()->json($items);
    }

}
