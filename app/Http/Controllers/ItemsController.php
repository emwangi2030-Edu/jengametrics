<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\SubElement;
use Illuminate\Http\Request;

class ItemsController extends Controller
{
    // Show all items for a given sub-element
    public function index($subElementId)
    {
        $subElement = SubElement::findOrFail($subElementId);
        $items = $subElement->items;

        return view('admin.sections.items', compact('subElement', 'items'));
    }

    // Store a new item
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'sub_element_id' => 'required|exists:sub_elements,id',
        ]);

        Item::create([
            'sub_element_id' => $request->sub_element_id,
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return redirect()->route('subelements.items', $request->sub_element_id)->with('success', 'Item added successfully.');
    }

    // Update an item
    public function update(Request $request, $id)
    {
        $item = Item::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
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
}
