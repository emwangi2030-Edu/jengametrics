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
use App\Models\Element;
use Illuminate\Http\Request;

class BqSectionController extends Controller
{
    public function create(BqDocument $bqDocument)
    {
        $this->assertDocumentAccess($bqDocument);

        $sections = Section::orderBy('name')->get();

        return view('bq_sections.create', compact('bqDocument', 'sections'));
    }

    public function store(BqDocument $bqDocument, Request $request)
    {
        $this->assertDocumentAccess($bqDocument);

        $request->validate([
            'section_id' => 'required|exists:sections,id',
            'element_id' => 'required|exists:elements,id',
            'item_id' => 'required|exists:items,id',
            'rate' => 'required|numeric|min:0',
            'quantity' => 'required|numeric|min:0',
            'amount' => 'nullable|numeric|min:0',
        ]);

        // Retrieve the selected item
        $selectedItem = Item::find($request->item_id);
        $calculatedAmount = $request->amount ?? ($request->quantity * $request->rate);

        $data = [
            'section_id' => $request->section_id,
            'element_id' => $request->element_id,
            'item_id' => $request->item_id,
            'rate' => $request->rate,
            'quantity' => $request->quantity,
            'amount' => $calculatedAmount,
            'project_id' => project_id(),
            'item_name' => $selectedItem?->name,
            'units' => $selectedItem?->unit_of_measurement,
            'bq_document_id' => $bqDocument->id,
        ];

        $section_created = BqSection::create($data);
        if($section_created){

            $materials = ItemMaterial::where('item_id', $request->item_id)->get();

            $unit = Item::find($request->item_id);
            $labour = $unit->labour ?? 0;
            $labour = $labour * $request->quantity;

                BomLabour::create([
                    'section_id'       => $section_created->section_id,
                    'item_id'          => $section_created->item_id,
                    'quantity'         => $request->quantity,
                    'rate'             => $unit->labour,
                    'amount'           => $request->quantity*$unit->labour,
                    'project_id'       => project_id(),
                    'bq_section_id'    => $section_created->id,
                    'bq_document_id'   => $section_created->bq_document_id,
                ]);


            foreach($materials as $material) {
                $product = Product::find($material->product_id);
                $conversionFactor = $material->conversion_factor ?? 0;
                $quantity = $request->quantity * $conversionFactor;
                $rate = $product->rate ?? 0;
                $amount = $quantity * $rate;
                BomItem::create([
                    'section_id'       => $section_created->section_id,
                    'item_id'          => $section_created->item_id,
                    'item_material_id' => $material->id,
                    'product_id'       => $material->product_id,
                    'quantity'         => $quantity,
                    'rate'             => $rate,
                    'amount'           => $amount,
                    'project_id'       => project_id(),
                    'bq_section_id'    => $section_created->id,
                    'bq_document_id'   => $section_created->bq_document_id,
                ]);
            }
        }
        return redirect()->route('bq_documents.show', $bqDocument)->with('success', trans('Item added successfully.'));
    }

    // Update the specified item in storage
    public function updateItem(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'element_id' => 'required|exists:elements,id',
            'rate' => 'required|numeric',
            'quantity' => 'required|numeric',
        ]);
    
        $item = BqSection::findOrFail($request->id);

        if ($item->bqDocument) {
            $this->assertDocumentAccess($item->bqDocument);
        }

        $item->element_id = $request->element_id;
        $item->item_id = $request->item_id;
        $item->item_name = Item::findOrFail($request->item_id)->name;
        $item->units = Item::findOrFail($request->item_id)->unit_of_measurement;
        $item->rate = $request->rate;
        $item->quantity = $request->quantity;
        $item->amount = $request->rate * $request->quantity;
        $item->save();

        // Clear old BOM records
        BomItem::where('bq_section_id', $item->id)->delete();
        BomLabour::where('bq_section_id', $item->id)->delete();

        // Fetch related data
        $materials = ItemMaterial::where('item_id', $item->item_id)->get();
        $unit = Item::findOrFail($item->item_id);
    
        // Create labour
        BomLabour::create([
            'section_id'    => $item->section_id,
            'item_id'       => $item->item_id,
            'quantity'      => $item->quantity,
            'rate'          => $unit->labour,
            'amount'        => $item->quantity * $unit->labour,
            'project_id'    => project_id(),
            'bq_section_id' => $item->id,
            'bq_document_id'=> $item->bq_document_id,
        ]);

        // Create materials
        foreach ($materials as $material) {
            $product = Product::find($material->product_id);
            $conversionFactor = $material->conversion_factor ?? 0;
            $quantity = $item->quantity * $conversionFactor;
            $rate = $product->rate ?? 0;
            $amount = $quantity * $rate;

            BomItem::create([
                'section_id'       => $item->section_id,
                'item_id'          => $item->item_id,
                'item_material_id' => $material->id,
                'product_id'       => $material->product_id,
                'quantity'         => $quantity,
                'rate'             => $rate,
                'amount'           => $amount,
                'project_id'       => project_id(),
                'bq_section_id'    => $item->id,
                'bq_document_id'   => $item->bq_document_id,
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
        $this->assertDocumentAccess($bqDocument);
        if ($bqSection->bq_document_id !== $bqDocument->id) {
            abort(404);
        }

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

    public function show(BqDocument $bqDocument, Section $section)
    {
        $this->assertDocumentAccess($bqDocument);

        $items = BqSection::where('section_id', $section->id)
            ->where('bq_document_id', $bqDocument->id)
            ->whereProjectId(project_id())
            ->get();

        $elements = Element::where('section_id', $section->id)->get();

        return view('bq_sections.show', [
            'bqDocument' => $bqDocument,
            'section' => $section,
            'items' => $items,
            'elements' => $elements,
        ]);
    }

    public function destroyItem($id)
    {
        $item = BqSection::findOrFail($id);

        if ($item->bqDocument) {
            $this->assertDocumentAccess($item->bqDocument);
        }

        // Delete associated BOM records
        BomItem::where('bq_section_id', $item->id)->delete();
        BomLabour::where('bq_section_id', $item->id)->delete();

        // Delete the BQ section entry itself
        $documentId = $item->bq_document_id;
        $sectionId = $item->section_id;
        $item->delete();

        if ($documentId && $sectionId) {
            return redirect()->route('bq_sections.show', [$documentId, $sectionId])->with('success', 'Item deleted successfully.');
        }

        return redirect()->back()->with('success', 'Item deleted successfully.');
    }

    protected function assertDocumentAccess(BqDocument $bqDocument): void
    {
        if (is_null($bqDocument->project_id)) {
            $bqDocument->update(['project_id' => project_id()]);
        }

        if ((int) $bqDocument->project_id !== (int) project_id()) {
            abort(404);
        }

        if (is_null($bqDocument->parent_id)) {
            abort(404);
        }
    }
}
