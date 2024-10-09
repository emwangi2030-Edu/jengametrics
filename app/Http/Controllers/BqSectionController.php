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
        dd('Reached');
        $sections = Section::all();

        return view('bq_sections.create', compact('bqDocument', 'sections'));
    }

    public function store(Request $request)
    {
       
        $data = [
            'section_id' => $request->section_id,
            'element_id' => $request->element_id,
            'sub_element_id' => $request->sub_element_id,
            'project_id' => project_id(),
        ];

        BqSection::create($data);


        return redirect()->route('bq_documents.index')->with('success', trans('Section added successfully.'));

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

    public function show(BqDocument $bqDocument)
    {
        // Fetch sections related to this BQ Document
        $sections = $bqDocument->sections;

        // Pass the document and its sections to the view
        return view('bq_documents.show', compact('bqDocument', 'sections'));
    }

}
