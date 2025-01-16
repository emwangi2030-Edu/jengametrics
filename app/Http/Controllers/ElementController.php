<?php

namespace App\Http\Controllers;

use App\Models\Element;
use App\Models\Section;
use Illuminate\Http\Request;

class ElementController extends Controller
{
    // Store a new element
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'section_id' => 'required|exists:sections,id',
        ]);

        Element::create([
            'section_id' => $request->section_id,
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return redirect()->route('sections.elements', $request->section_id)->with('success', 'Element added successfully.');
    }

    // Update an existing element
    public function update(Request $request, $id)
    {
        $element = Element::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $element->update($request->all());

        return redirect()->back()->with('success', 'Element updated successfully.');
    }

    // Delete an element
    public function destroy($id)
    {
        $element = Element::findOrFail($id);
        $element->delete();

        return redirect()->back()->with('success', 'Element deleted successfully.'); 
    }

    public function items($section_id, $element_id)
    {
        $section = Section::findOrFail($section_id);
        $element = Element::findOrFail($element_id);
        $items = $element->items; // Retrieve items related to this element
    
        return view('admin.sections.items', compact('section', 'element', 'items'));
    }
    
}
