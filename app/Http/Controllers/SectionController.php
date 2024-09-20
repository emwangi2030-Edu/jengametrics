<?php

namespace App\Http\Controllers;

use App\Models\Section;
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
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $section = Section::create([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        if ($request->ajax()) {
            return response()->json(['section' => $section], 200);
        }

        return redirect()->back()->with('success', 'Section added successfully!');
    }
}
