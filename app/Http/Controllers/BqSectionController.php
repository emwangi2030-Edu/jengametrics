<?php

namespace App\Http\Controllers;

use App\Models\BqDocument;
use App\Models\BqSection;
use Illuminate\Http\Request;

class BqSectionController extends Controller
{
    public function create(BqDocument $bqDocument)
    {
        return view('bq_sections.create', compact('bqDocument'));
    }

    public function store(Request $request, BqDocument $bqDocument)
    {
        $request->validate([
            'section_name' => 'required|string|max:255',
            'details' => 'nullable|string',
        ]);

        $bqDocument->sections()->create([
            'section_name' => $request->section_name,
            'details' => $request->details,
        ]);

        return redirect()->route('bq_documents.show', $bqDocument);
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
