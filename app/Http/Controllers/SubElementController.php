<?php

namespace App\Http\Controllers;

use App\Models\SubElement;
use App\Models\Element;
use Illuminate\Http\Request;

class SubElementController extends Controller
{
    // Store a new sub-element
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'element_id' => 'required|exists:elements,id',
        ]);

        SubElement::create([
            'element_id' => $request->element_id,
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return redirect()->route('elements.subelements', ['element' => $request->element_id])
            ->with('success', 'Sub-Element added successfully.');
    }

    // Update an existing sub-element
    public function update(Request $request, $id)
    {
        $subElement = SubElement::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $subElement->update($request->all());

        return redirect()->back()->with('success', 'Sub-Element updated successfully.');
    }

    // Delete a sub-element
    public function destroy($id)
    {
        $subElement = SubElement::findOrFail($id);
        $subElement->delete();

        return redirect()->back()->with('success', 'Sub-Element deleted successfully.');
    }

    // Display the sub-elements for a specific element
    public function subelements($elementId)
    {
        // Find the element by ID, eager load sub-elements
        $element = Element::with('subelements')->findOrFail($elementId);

        // Fetch all sub-elements related to this element (already eager loaded, but this is an alternative if pagination is needed)
        // $subelements = SubElement::where('element_id', $element->id)->paginate(10); // Optional pagination

        // Return the view with the element and its sub-elements
        return view('admin.sections.subelements', compact('element'));
    }
}

