<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BqDocument;
use App\Models\Bom;
use App\Models\BomLabour;
use App\Models\BomItem;
use App\Models\Section;
use App\Models\BqSection;

class BOMController extends Controller
{
    public function index()
    {
        $bqDocument = get_project()->id;
        $sections = Section::orderBy('id', 'desc')->get();

        return view('boms.index', compact('bqDocument', 'sections'));
    

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
        // Fetch sections related to this BQ Document
        $bqSection = Section::find($id);
         $items = BomItem::whereProjectId(project_id())->where('section_id', $id)->get();

        $labours = BomLabour::whereProjectId(project_id())->where('section_id', $id)->get();
        
        // Pass the document and its sections to the view
         return view('boms.show', compact( 'bqSection', 'items', 'labours'));
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

    public function report()
    {
        // Calculate the total estimated cost across all sections
        $totalEstimatedCost = BqSection::with('bomItems')
            ->get()
            ->reduce(function ($carry, $section) {
                $sectionTotal = $section->bomItems->sum('amount');
                return $carry + $sectionTotal;
            }, 0);

        return view('boms.report', compact('totalEstimatedCost'));
    }
}
