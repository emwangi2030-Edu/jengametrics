<?php

namespace App\Http\Controllers;

use App\Models\BqDocument;
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
        // Retrieve all BQ documents with their associated sections
        $documents = BqDocument::with('sections')->get();
        return view('bq_documents.index', compact('documents'));
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
        return view('bq_documents.show', compact('bqDocument'));
    }
}
