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
use Illuminate\Support\Facades\Validator;

class BqSectionController extends Controller
{
    public function create()
    {
        $sections = Section::all();
        return view('bq_sections.create', compact('sections'));
    }

    public function bulkCreate(Request $request)
    {
        $sections = Section::all();
        return view('bq_sections.bulk', [
            'sections' => $sections,
            'prefillSection' => $request->get('section_id'),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'section_id' => 'required|exists:sections,id',
            'element_id' => 'required|exists:elements,id',
            'item_id'    => 'required|exists:items,id',
            'rate'       => 'required|numeric|min:0',
            'quantity'   => 'required|numeric|min:0',
            'amount'     => 'nullable|numeric|min:0',
        ]);
        // Retrieve the selected item
        $selectedItem = Item::find($request->item_id);
       
        $calculatedAmount = ($request->rate ?? 0) * ($request->quantity ?? 0);

        $data = [
            'section_id' => $request->section_id,
            'element_id' => $request->element_id,
            'item_id' => $request->item_id,
            'rate' => $request->rate,
            'quantity' => $request->quantity,
            'amount' => $calculatedAmount,
            'project_id' => project_id(),
            'item_name' => $selectedItem?->name,
            'units' => $selectedItem?->unit_of_measurement
        ];

        $section_created = BqSection::create($data);
        if ($section_created) {
            $materials = ItemMaterial::where('item_id', $request->item_id)->get();

            $unit = Item::find($request->item_id);

            BomLabour::create([
                'section_id'    => $section_created->section_id,
                'item_id'       => $section_created->item_id,
                'quantity'      => $request->quantity,
                'rate'          => $unit->labour,
                'amount'        => $request->quantity * $unit->labour,
                'project_id'    => project_id(),
                'bq_section_id' => $section_created->id,
            ]);

            foreach ($materials as $material) {
                $product = Product::find($material->product_id);
                $quantity = $request->quantity * ($material->conversion_factor ?? 0);
                $rate = $product?->rate ?? 0;
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
                ]);
            }
        }
        return redirect()->route('bq_documents.index')->with('success', trans('Section added successfully.'));
    }

    public function storeBulk(Request $request)
    {
        $request->validate([
            'section_id' => 'required|exists:sections,id',
            'items' => 'required|array|min:1',
            'items.*.element_id' => 'required|exists:elements,id',
            'items.*.item_id' => 'required|exists:items,id',
            'items.*.rate' => 'required|numeric|min:0',
            'items.*.quantity' => 'required|numeric|min:0',
        ]);

        $sectionId = (int) $request->section_id;
        $count = 0;

        foreach ($request->items as $row) {
            $selectedItem = Item::find($row['item_id']);
            $rate = (float) ($row['rate'] ?? 0);
            $qty = (float) ($row['quantity'] ?? 0);
            $amount = $rate * $qty;

            $sectionCreated = BqSection::create([
                'section_id' => $sectionId,
                'element_id' => $row['element_id'],
                'item_id'    => $row['item_id'],
                'rate'       => $rate,
                'quantity'   => $qty,
                'amount'     => $amount,
                'project_id' => project_id(),
                'item_name'  => $selectedItem?->name,
                'units'      => $selectedItem?->unit_of_measurement,
            ]);

            if ($sectionCreated) {
                // Labour
                $unit = Item::find($row['item_id']);
                BomLabour::create([
                    'section_id'    => $sectionCreated->section_id,
                    'item_id'       => $sectionCreated->item_id,
                    'quantity'      => $qty,
                    'rate'          => $unit->labour ?? 0,
                    'amount'        => $qty * ($unit->labour ?? 0),
                    'project_id'    => project_id(),
                    'bq_section_id' => $sectionCreated->id,
                ]);

                // Materials
                $materials = ItemMaterial::where('item_id', $row['item_id'])->get();
                foreach ($materials as $material) {
                    $product = Product::find($material->product_id);
                    $quantity = $qty * ($material->conversion_factor ?? 0);
                    $matRate = $product?->rate ?? 0;
                    $matAmt = $quantity * $matRate;

                    BomItem::create([
                        'section_id'       => $sectionCreated->section_id,
                        'item_id'          => $sectionCreated->item_id,
                        'item_material_id' => $material->id,
                        'product_id'       => $material->product_id,
                        'quantity'         => $quantity,
                        'rate'             => $matRate,
                        'amount'           => $matAmt,
                        'project_id'       => project_id(),
                        'bq_section_id'    => $sectionCreated->id,
                    ]);
                }
                $count++;
            }
        }

        return redirect()->route('section.show', $sectionId)->with('success', "$count items added to BoQ and BoM.");
    }

    public function importCsv(Request $request)
    {
        $request->validate([
            'section_id' => 'required|exists:sections,id',
            'csv' => 'required|file|mimes:csv,txt',
        ]);

        $sectionId = (int) $request->section_id;
        $path = $request->file('csv')->getRealPath();

        $handle = fopen($path, 'r');
        if ($handle === false) {
            return back()->withErrors(['csv' => 'Unable to read uploaded file.']);
        }

        $rows = [];
        $header = null;
        while (($data = fgetcsv($handle)) !== false) {
            if ($header === null) {
                // Detect header by checking if it contains expected column names
                $lower = array_map(fn($v) => strtolower(trim($v)), $data);
                if (in_array('element_id', $lower) && in_array('item_id', $lower) && in_array('rate', $lower) && in_array('quantity', $lower)) {
                    $header = $lower;
                    continue;
                }
            }

            if ($header) {
                $row = array_combine($header, $data);
                $rows[] = [
                    'element_id' => trim($row['element_id'] ?? ''),
                    'item_id'    => trim($row['item_id'] ?? ''),
                    'rate'       => trim($row['rate'] ?? ''),
                    'quantity'   => trim($row['quantity'] ?? ''),
                ];
            } else {
                if (count($data) < 4) { continue; }
                $rows[] = [
                    'element_id' => trim($data[0] ?? ''),
                    'item_id'    => trim($data[1] ?? ''),
                    'rate'       => trim($data[2] ?? ''),
                    'quantity'   => trim($data[3] ?? ''),
                ];
            }
        }
        fclose($handle);

        if (empty($rows)) {
            return back()->withErrors(['csv' => 'No valid rows found in CSV.']);
        }

        // Validate each row
        $errors = [];
        foreach ($rows as $i => $row) {
            $v = Validator::make($row, [
                'element_id' => 'required|exists:elements,id',
                'item_id'    => 'required|exists:items,id',
                'rate'       => 'required|numeric|min:0',
                'quantity'   => 'required|numeric|min:0',
            ]);
            if ($v->fails()) {
                $errors["row_".($i+1)] = $v->errors()->all();
            }
        }

        if (!empty($errors)) {
            return back()->withErrors($errors)->withInput();
        }

        // Reuse creation logic from storeBulk
        $count = 0;
        foreach ($rows as $row) {
            $selectedItem = Item::find($row['item_id']);
            $rate = (float) ($row['rate'] ?? 0);
            $qty = (float) ($row['quantity'] ?? 0);
            $amount = $rate * $qty;

            $sectionCreated = BqSection::create([
                'section_id' => $sectionId,
                'element_id' => $row['element_id'],
                'item_id'    => $row['item_id'],
                'rate'       => $rate,
                'quantity'   => $qty,
                'amount'     => $amount,
                'project_id' => project_id(),
                'item_name'  => $selectedItem?->name,
                'units'      => $selectedItem?->unit_of_measurement,
            ]);

            if ($sectionCreated) {
                $unit = Item::find($row['item_id']);
                BomLabour::create([
                    'section_id'    => $sectionCreated->section_id,
                    'item_id'       => $sectionCreated->item_id,
                    'quantity'      => $qty,
                    'rate'          => $unit->labour ?? 0,
                    'amount'        => $qty * ($unit->labour ?? 0),
                    'project_id'    => project_id(),
                    'bq_section_id' => $sectionCreated->id,
                ]);

                $materials = ItemMaterial::where('item_id', $row['item_id'])->get();
                foreach ($materials as $material) {
                    $product = Product::find($material->product_id);
                    $quantity = $qty * ($material->conversion_factor ?? 0);
                    $matRate = $product?->rate ?? 0;
                    $matAmt = $quantity * $matRate;

                    BomItem::create([
                        'section_id'       => $sectionCreated->section_id,
                        'item_id'          => $sectionCreated->item_id,
                        'item_material_id' => $material->id,
                        'product_id'       => $material->product_id,
                        'quantity'         => $quantity,
                        'rate'             => $matRate,
                        'amount'           => $matAmt,
                        'project_id'       => project_id(),
                        'bq_section_id'    => $sectionCreated->id,
                    ]);
                }
                $count++;
            }
        }

        return redirect()->route('section.show', $sectionId)->with('success', "$count items imported into BoQ and BoM.");
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
        ]);
    
        // Create materials
        foreach ($materials as $material) {
            $product = Product::find($material->product_id);
            $quantity = $item->quantity * $material->conversion_factor;
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
        $elements = Element::where('section_id', $bqSection->id)->get();

        // Pass the document and its sections to the view
        return view('bq_sections.show', compact( 'bqSection', 'items','bq_sections','elements'));
    }

    public function destroyItem($id)
    {
        $item = BqSection::findOrFail($id);

        // Delete associated BOM records
        BomItem::where('bq_section_id', $item->id)->delete();
        BomLabour::where('bq_section_id', $item->id)->delete();

        // Delete the BQ section entry itself
        $item->delete();

        return redirect()->back()->with('success', 'Item deleted successfully.');
    }
}
