<?php

namespace App\Http\Controllers;

use App\Models\BqDocument;
use App\Models\BqSection;
use App\Models\BqItem;
use Illuminate\Http\Request;

class BqItemController extends Controller
{
    // Show the form for creating a new item
    public function create(Request $request)
    {
      $bqSection = $request->bqSection;

$bqSection = BqSection::find($bqSection);
$units = ['kg', 'litre', 'piece', 'meter', 'ton', 'gallon']; // Example units; replace with actual data source

        return view('bq_items.create', compact('bqSection','units'));
    }

    // Store a newly created item in storage
    public function store(Request $request)
    {

    
        $item = new BqItem([
            'item_description' => $request->input('item_description'),
            'quantity' => $request->input('quantity'),
            'unit' => $request->input('unit'),
            'rate' => $request->input('rate'),
            'amount' => $request->input('amount'),
            'bq_section_id' => $request->input('bq_section_id'),
        ]);
    
        $item->save();

        $bqSection = BqSection::find($request->input('bq_section_id'));
        $bqDocument = BqDocument::find($bqSection->bq_document_id);
    
        return redirect()->route('bq_sections.show', [$bqDocument, $bqSection])->with('success', 'Item added successfully.');
    }
    
    
    

    // Show the form for editing the specified item
    public function edit(BqDocument $bqDocument, BqItem $bqItem)
    {
        $sections = $bqDocument->sections;
        return view('bq_items.edit', compact('bqDocument', 'bqItem', 'sections'));
    }

    // Update the specified item in storage
    public function update(Request $request, BqDocument $bqDocument, BqItem $bqItem)
    {
        $request->validate([
            'bq_section_id' => 'required|exists:bq_sections,id',
            'item_description' => 'required|string|max:255',
            'quantity' => 'required|integer',
            'unit' => 'required|string',
            'rate' => 'required|numeric',
            'amount' => 'required|numeric',
        ]);

        $bqItem->update([
            'bq_section_id' => $request->bq_section_id,
            'item_description' => $request->item_description,
            'quantity' => $request->quantity,
            'unit' => $request->unit,
            'rate' => $request->rate,
            'amount' => $request->amount,
        ]);

        return redirect()->route('bq_documents.show', $bqDocument);
    }

    // Remove the specified item from storage
    public function destroy(BqDocument $bqDocument, BqItem $bqItem)
    {
        $bqItem->delete();

        return redirect()->route('bq_documents.show', $bqDocument);
    }
}
