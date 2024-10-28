<?php

namespace App\Http\Controllers;

use App\Models\BqDocument;
use App\Models\BqSection;
use App\Models\Section;
use App\Models\BomItem;
use App\Models\ItemMaterial;

use Illuminate\Http\Request;

class BqSectionController extends Controller
{
    public function create(BqDocument $bqDocument)
    {
       
        $sections = Section::all();

        return view('bq_sections.create', compact('bqDocument', 'sections'));
    }

    public function store(Request $request)
    {

        
       
        $data = [
            'section_id' => $request->section_id,
            'element_id' => $request->element_id,
            'sub_element_id' => $request->sub_element_id,
            'item_id' => $request->item_id,
            'rate' => $request->rate,
            'quantity' => $request->quantity,
            'amount' => $request->amount,
            'project_id' => project_id(),
        ];

        $section_created = BqSection::create($data);
        if($section_created){

$materials = ItemMaterial::where('item_id', $request->item_id)->get();
 foreach($materials as $material) {
    $quantity = $request->quantity * $material->conversion_factor;
    $amount = $material->amount*$material->rate; 
    BomItem::create([
        'section_id' => $section_created->section_id,
        'item_id' => $section_created->item_id,
        'item_material_id' => $material->id,
        'quantity' => $quantity,
        'rate' => $material->rate,
        'amount' => $amount,
        'project_id' => project_id(),
    ]);
 }
           

        }




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

    public function show($id)
    {
        // Fetch sections related to this BQ Document
        $bqSection = Section::find($id);
        $items = BqSection::where('section_id', $id)->get();

        // Pass the document and its sections to the view
        return view('bq_sections.show', compact( 'bqSection', 'items'));
    }

}
