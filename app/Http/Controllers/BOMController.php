<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BqDocument;
use App\Models\Bom;
use App\Models\BomItem;

class BOMController extends Controller
{
    public function index()
    {
        $boms = Bom::with('bqDocument')->get();
        return view('boms.index', compact('boms'));
    }

    public function create()
    {
        $bqDocuments = BqDocument::all();
        return view('boms.create', compact('bqDocuments'));
    }

    public function store(Request $request)
    {
        $bom = Bom::create([
            'bq_document_id' => $request->bq_document_id,
            'bom_name' => $request->bom_name,
        ]);

        foreach ($request->items as $item) {
            BomItem::create([
                'bom_id' => $bom->id,
                'item_description' => $item['description'],
                'quantity' => $item['quantity'],
                'unit' => $item['unit'],
                'rate' => $item['rate'],
                'amount' => $item['amount'],
            ]);
        }

        return redirect()->route('boms.index');
    }

    public function show($id)
    {
        $bom = BOM::with('bqDocument', 'items')->findOrFail($id);
        return view('boms.show', compact('bom'));
    }

    public function destroy($id)
    {
        // Find the BOM by its ID
        $bom = Bom::findOrFail($id);

        // Optionally, delete related items (if applicable)
        $bom->items()->delete();

        // Delete the BOM
        $bom->delete();

        // Redirect back to the index page with a success message
        return redirect()->route('boms.index')->with('success', 'BOM deleted successfully.');
    }
}
