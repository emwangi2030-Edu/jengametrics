<?php

namespace App\Http\Controllers;

use App\Models\BqDocument;
use App\Models\BqSection;
use App\Models\Section;
use App\Models\BomLabour;
use App\Models\BomItem;
use App\Models\Item;
use App\Models\ItemMaterial;
use App\Models\Product;

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
        // Retrieve the selected item
        $selectedItem = Item::find($request->item_id);
       
        $data = [
            'section_id' => $request->section_id,
            'element_id' => $request->element_id,
            'item_id' => $request->item_id,
            'rate' => $request->rate,
            'quantity' => $request->quantity,
            'amount' => $request->amount,
            'project_id' => project_id(),
            'item_name' => $selectedItem?->name,
            'units' => $selectedItem?->unit_of_measurement
        ];

        $section_created = BqSection::create($data);
        if($section_created){

            $materials = ItemMaterial::where('item_id', $request->item_id)->get();

            $unit = Item::find($request->item_id);
            $labour = $unit->labour;
            $labour = $labour*$request->quantity;

                BomLabour::create([
                    'section_id'       => $section_created->section_id,
                    'item_id'          => $section_created->item_id,
                    'quantity'         => $request->quantity,
                    'rate'             => $unit->labour,
                    'amount'           => $request->quantity*$unit->labour,
                    'project_id'       => project_id(),
                    'bq_section_id'    => $section_created->id,
                ]);


            foreach($materials as $material) {
                $product = Product::find($material->product_id);
                $quantity = $request->quantity * $material->conversion_factor;
                $amount = $material->amount*$material->rate; 
                BomItem::create([
                    'section_id'       => $section_created->section_id,
                    'item_id'          => $section_created->item_id,
                    'item_material_id' => $material->id,
                    'product_id'       => $material->product_id,
                    'quantity'         => $request->quantity,
                    'rate'             => $product->rate,
                    'amount'           => $amount,
                    'project_id'       => project_id(),
                    'bq_section_id'    => $section_created->id,
                ]);
            }
                    

        }




        return redirect()->route('bq_documents.index')->with('success', trans('Section added successfully.'));

    }


               // Update the specified item in storage
               public function updateItem(Request $request)
               {
                   $request->validate([
                       'item_name' => 'required|string',
                       'rate' => 'required|numeric',
                       'quantity' => 'required|numeric',
                   ]);
               
                   $item = BqSection::findOrFail($request->id);
                   $item->item_name = $request->item_name;
                   $item->quantity = $request->quantity;
                   $item->rate = $request->rate;
                   $item->amount = $request->rate * $request->quantity;
                   $item->save();
               
                   // Delete old BOM items & labour records
                   BomItem::where('bq_section_id', $request->id)->delete();
                   BomLabour::where('bq_section_id', $request->id)->delete();
               
                   // Retrieve related materials and labour
                   $materials = ItemMaterial::where('item_id', $item->item_id)->get();
                   $unit = Item::findOrFail($item->item_id);
                   
                   // Create updated labour entry
                   BomLabour::create([
                       'section_id'    => $item->section_id,
                       'item_id'       => $item->item_id,
                       'quantity'      => $request->quantity,
                       'rate'          => $unit->labour,
                       'amount'        => $request->quantity * $unit->labour,
                       'project_id'    => project_id(),
                       'bq_section_id' => $item->id,
                   ]);
               
                   // Process materials
                   foreach ($materials as $material) {
                       $product = Product::find($material->product_id);
                       $quantity = $request->quantity * $material->conversion_factor;
                       $amount = $quantity * $product->rate;
               
                       BomItem::create([
                           'section_id'       => $item->section_id,
                           'item_id'          => $item->item_id,
                           'item_material_id' => $material->id,
                           'product_id'       => $material->product_id,
                           'quantity'         => $quantity,
                           'rate'             => $product->rate,
                           'amount'           => $amount,
                           'project_id'       => project_id(),
                           'bq_section_id'    => $item->id,
                       ]);
                   }
               
                   return redirect()->back()->with('success', 'Item updated successfully.');
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
        $bq_sections = Item::all();
        $items = BqSection::where('section_id', $id)->whereProjectId(project_id())->get();

        // Pass the document and its sections to the view
        return view('bq_sections.show', compact( 'bqSection', 'items','bq_sections'));
    }

}
