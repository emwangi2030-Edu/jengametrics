<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Document;

class DocumentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'file' => [
                'required',
                'file',
                'max:2048',
                'mimes:pdf,doc,docx,xls,xlsx,csv,txt,jpg,jpeg,png,webp',
            ],
        ]);

        $file = $request->file('file');
        $path = $file->store('documents', 'public');

        $document = new Document();
        $document->name = $file->getClientOriginalName();
        $document->path = $path;
        $document->user_id = auth()->id();
        $document->save();

        return back()->with('success', 'Document uploaded successfully.');
    }


    public function index()
    {
        // Fetch all documents uploaded by the current user
        $documents = Document::all();
        // Return the view with the documents
        return view('documents.upload', compact('documents'));
    }
    
}
