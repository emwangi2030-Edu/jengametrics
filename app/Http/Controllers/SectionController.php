<?php

namespace App\Http\Controllers;

use App\Models\Section;
use App\Models\Element;
use Illuminate\Http\Request;

class SectionController extends Controller
{
    // List all sections
    public function index()
    {
        $sections = Section::all();
        return view('admin.sections.index', compact('sections'));
    }

    // Store new section
    public function store(Request $request)
    {
        // Validate the inputs
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // Create a new section
        Section::create([
            'name' => $request->name,
        ]);

        // Redirect back with a success message
        return redirect()->route('sections.index')->with('success', 'Section added successfully.');
    }

    public function destroy(Section $section)
    {
        $section->delete();

        return redirect()->route('sections.index')->with('success', 'Section deleted successfully!');
    }

    public function update(Request $request, $id)
    {
        // Find the section by its ID
        $section = Section::findOrFail($id);

        // Validate the incoming request
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // Update the section with the new data
        $section->name = $request->input('name');
        $section->save();

        // Redirect back to the sections index page with a success message
        return redirect()->route('sections.index')->with('success', 'Section updated successfully!');
    }

    public function elements($id)
    {
        // Find the section by ID and load its related elements
        $section = Section::with('elements')->findOrFail($id);

        // Return the view with the section and its elements
        return view('admin.sections.elements', compact('section'));
    }
}
