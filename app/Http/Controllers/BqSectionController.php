<?php

namespace App\Http\Controllers;

use App\Models\BqDocument;
use App\Models\BqSection;
use App\Models\Section;

use Illuminate\Http\Request;

class BqSectionController extends Controller
{
    public function create(BqDocument $bqDocument)
    {
        // dd('Reached');
        $sections = Section::all();

        return view('bq_sections.create', compact('bqDocument', 'sections'));
    }

    public function store(Request $request, BqDocument $bqDocument)
    {
        // Find the selected section
        $section = Section::findOrFail($request->section_id);

        // Populate the BqSection fields
        $data = [
            'section_name' => $section->name,
            'details' => $section->description,
            'section_id' => $request->section_id,
            'element_id' => $request->element_id,
            'sub_element_id' => $request->sub_element_id,
            'project_id' => project_id(),
            'bq_document_id' => $bqDocument->id,
        ];

        // Create the BqSection entry
        BqSection::create($data);

        // Redirect back to the bq_documents route with success message
        return redirect()->route('bq_documents')
            ->with('success', trans('Section added successfully.'));
    }

    public function edit(BqDocument $bqDocument, BqSection $bqSection)
    {
        return view('bq_sections.edit', compact('bqDocument', 'bqSection'));
    }

    public function update(Request $request, BqDocument $bqDocument, BqSection $bqSection)
    {
        $request->validate([
            'section_name' => 'required|string|max:255',
            'details' => 'nullable|string',
        ]);

        $bqSection->update([
            'section_name' => $request->section_name,
            'details' => $request->details,
        ]);

        return redirect()->route('bq_documents.show', $bqDocument);
    }

    public function show(BqDocument $bqDocument, BqSection $bqSection)
    {
        // Retrieve items related to the section
        $items = $bqSection->items; // Ensure the `items` relationship is defined in BqSection model
    
        return view('bq_sections.show', compact('bqDocument', 'bqSection', 'items'));
    }
    
}
