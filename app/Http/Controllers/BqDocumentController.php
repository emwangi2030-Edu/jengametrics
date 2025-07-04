<?php

namespace App\Http\Controllers;

use App\Models\BqDocument;
use App\Models\BqSection;
use App\Models\Section;
use App\Models\Element;
use App\Models\Item;
use Illuminate\Http\Request;

class BqDocumentController extends Controller
{
    /**
     * Display a listing of the BQ documents.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Retrieve BQ document with associated sections
        $bqDocument = get_project()->id;
        $sections = Section::orderBy('id', 'asc')->get();
        $totalAmount = BqSection::where('project_id', project_id())->sum('amount');

        return view('bq_documents.show', compact('bqDocument', 'sections', 'totalAmount'));
    }

    /**
     * Show the form for creating a new BQ document.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('bq_documents.create');
    }



    // Method to get elements based on the selected section
    public function getElements(Request $request)
    {
        $elements = Element::where('section_id', $request->section_id)->pluck('name', 'id');
        return response()->json($elements);
    }

    public function getItems(Request $request)
    {
        $items = Item::where('element_id', $request->element_id)->pluck('name', 'id');
        return response()->json($items);
    }
    
    /**
     * Store a newly created BQ document in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validate incoming request data
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        // Create a new BQ document
        BqDocument::create([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'user_id' => auth()->id(),
        ]);

        // Redirect to the index page with a success message
        return redirect()->route('bq_documents.index')
                         ->with('success', 'Document created successfully.');
    }

    /**
     * Display the specified BQ document.
     *
     * @param \App\Models\BqDocument $bqDocument
     * @return \Illuminate\View\View
     */
    public function show(BqDocument $bqDocument)
    {
        // Retrieve the sections associated with this BqDocument
        $bqSections = BqSection::where('bq_document_id', $bqDocument->id)->get();

        return view('bq_documents.show', compact('bqDocument', 'bqSections'));
    }
}
