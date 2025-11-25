<?php

namespace App\Http\Controllers;

use App\Models\BqDocument;
use App\Models\BqLevel;
use App\Models\BqSection;
use App\Models\BomItem;
use App\Models\BomLabour;
use App\Models\Section;
use App\Models\Item;
use App\Models\Element;
use App\Models\ItemMaterial;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Services\BqItemCreator;
use Illuminate\Support\Facades\Validator;

class BqSectionController extends Controller
{
    protected BqItemCreator $bqItemCreator;

    public function __construct(BqItemCreator $bqItemCreator)
    {
        $this->bqItemCreator = $bqItemCreator;
    }

    public function create(BqDocument $bqDocument, BqLevel $bqLevel)
    {
        $this->assertDocumentAccess($bqDocument);
        $this->assertLevelAccess($bqLevel, $bqDocument);

        $sections = Section::orderBy('name')->get();

        return view('bq_sections.create', [
            'bqDocument' => $bqDocument,
            'bqLevel' => $bqLevel,
            'sections' => $sections,
        ]);
    }

    public function bulkCreate(Request $request)
    {
        $sections = Section::all();
        return view('bq_sections.bulk', [
            'sections' => $sections,
            'prefillSection' => $request->get('section_id'),
        ]);
    }

    public function store(BqDocument $bqDocument, BqLevel $bqLevel, Request $request)
    {
        $this->assertDocumentAccess($bqDocument);
        $this->assertLevelAccess($bqLevel, $bqDocument);

        $request->validate([
            'section_id' => 'required|exists:sections,id',
            'element_id' => 'required|exists:elements,id',
            'item_id' => 'required|exists:items,id',
            'rate' => 'required|numeric|min:0',
            'quantity' => 'required|numeric|min:0',
            'amount' => 'nullable|numeric|min:0',
        ]);

        // Retrieve the selected item
        $section = Section::findOrFail($request->section_id);
        $element = Element::where('id', $request->element_id)
            ->where('section_id', $section->id)
            ->firstOrFail();
        $item = Item::where('id', $request->item_id)
            ->where('element_id', $element->id)
            ->firstOrFail();

        $quantity = (float) $request->quantity;
        $rate = (float) $request->rate;
        $amount = $request->filled('amount')
            ? (float) $request->amount
            : null;

        $this->bqItemCreator->create(
            $bqDocument,
            $section,
            $bqLevel,
            $element,
            $item,
            $quantity,
            $rate,
            (int) project_id(),
            $amount
        );

        return redirect()->route('bq_levels.show', [$bqDocument, $bqLevel])->with('success', trans('Item added successfully.'));
    }

    public function storeBulk(Request $request)
    {
        $request->validate([
            'section_id' => 'required|exists:sections,id',
            'bq_level_id' => 'required|exists:bq_levels,id',
            'items' => 'required|array|min:1',
            'items.*.element_id' => 'required|exists:elements,id',
            'items.*.item_id' => 'required|exists:items,id',
            'items.*.rate' => 'required|numeric|min:0',
            'items.*.quantity' => 'required|numeric|min:0',
        ]);

        $bqLevel = BqLevel::findOrFail($request->bq_level_id);
        $this->assertLevelAccess($bqLevel);

        $sectionId = (int) $request->section_id;
        $count = 0;

        foreach ($request->items as $row) {
            $selectedItem = Item::find($row['item_id']);
            $rate = (float) ($row['rate'] ?? 0);
            $qty = (float) ($row['quantity'] ?? 0);
            $amount = $rate * $qty;

            $sectionCreated = BqSection::create([
                'bq_document_id' => $bqLevel->bq_document_id,
                'bq_level_id' => $bqLevel->id,
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

        return redirect()->route('bq_levels.show', [$bqLevel->bq_document_id, $bqLevel->id])->with('success', "$count items added to BoQ and BoM.");
    }

    public function importCsv(Request $request)
    {
        $request->validate([
            'section_id' => 'required|exists:sections,id',
            'bq_level_id' => 'required|exists:bq_levels,id',
            'csv' => 'required|file|mimes:csv,txt',
        ]);

        $bqLevel = BqLevel::findOrFail($request->bq_level_id);
        $this->assertLevelAccess($bqLevel);

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
                'bq_document_id' => $bqLevel->bq_document_id,
                'bq_level_id' => $bqLevel->id,
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

        return redirect()->route('bq_levels.show', [$bqLevel->bq_document_id, $bqLevel->id])->with('success', "$count items imported into BoQ and BoM.");
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
    
        $bqSection = BqSection::findOrFail($request->id);

        if ($bqSection->bqDocument) {
            $this->assertDocumentAccess($bqSection->bqDocument);
        }

        $element = Element::where('id', $request->element_id)
            ->where('section_id', $bqSection->section_id)
            ->firstOrFail();

        $item = Item::where('id', $request->item_id)
            ->where('element_id', $element->id)
            ->firstOrFail();

        $quantity = (float) $request->quantity;
        $rate = (float) $request->rate;

        $bqSection->element_id = $element->id;
        $bqSection->item_id = $item->id;
        $bqSection->item_name = $item->name;
        $bqSection->units = $item->unit_of_measurement;
        $bqSection->rate = $rate;
        $bqSection->quantity = $quantity;
        $bqSection->amount = $rate * $quantity;
        $bqSection->save();

        $this->bqItemCreator->refresh($bqSection, $item, $quantity, (int) project_id());

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

    public function show(BqDocument $bqDocument, BqLevel $bqLevel)
    {
        $this->assertLevelAccess($bqLevel, $bqDocument);

        $items = BqSection::where('bq_level_id', $bqLevel->id)
            ->where('bq_document_id', $bqDocument->id)
            ->whereProjectId(project_id())
            ->with('section')
            ->orderByDesc('created_at')
            ->get();

        return view('bq_sections.show', [
            'bqDocument' => $bqDocument,
            'bqLevel' => $bqLevel,
            'items' => $items,
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
        $levelId = $item->bq_level_id;
        $item->delete();

        if ($documentId && $levelId) {
            return redirect()->route('bq_levels.show', [$documentId, $levelId])->with('success', 'Item deleted successfully.');
        }

        return redirect()->back()->with('success', 'Item deleted successfully.');
    }

    protected function assertDocumentAccess(BqDocument $bqDocument): void
    {
        if (is_null($bqDocument->project_id)) {
            $bqDocument->update(['project_id' => project_id()]);
        }

        if ((int) $bqDocument->project_id !== (int) project_id()) {
            \Log::warning('BqSection access blocked (doc mismatch)', [
                'user_id' => auth()->id(),
                'user_project_id' => project_id(),
                'bq_document_id' => $bqDocument->id,
                'bq_document_project_id' => $bqDocument->project_id,
                'bq_document_parent_id' => $bqDocument->parent_id,
                'route' => request()->fullUrl(),
            ]);
            abort(404);
        }

        if (is_null($bqDocument->parent_id)) {
            \Log::warning('BqSection access blocked (parent missing)', [
                'user_id' => auth()->id(),
                'user_project_id' => project_id(),
                'bq_document_id' => $bqDocument->id,
                'bq_document_project_id' => $bqDocument->project_id,
                'bq_document_parent_id' => $bqDocument->parent_id,
                'route' => request()->fullUrl(),
            ]);
            abort(404);
        }
    }

    protected function assertLevelAccess(BqLevel $bqLevel, ?BqDocument $bqDocument = null): void
    {
        $document = $bqDocument ?? $bqLevel->document;

        if (! $document) {
            \Log::warning('BqSection access blocked (no document on level)', [
                'user_id' => auth()->id(),
                'user_project_id' => project_id(),
                'bq_level_id' => $bqLevel->id,
                'bq_level_document_id' => $bqLevel->bq_document_id,
                'route' => request()->fullUrl(),
            ]);
            abort(404);
        }

        $this->assertDocumentAccess($document);

        if ((int) $bqLevel->bq_document_id !== (int) $document->id) {
            \Log::warning('BqSection access blocked (level/doc mismatch)', [
                'user_id' => auth()->id(),
                'user_project_id' => project_id(),
                'bq_level_id' => $bqLevel->id,
                'bq_level_document_id' => $bqLevel->bq_document_id,
                'bq_document_id' => $document->id,
                'bq_document_project_id' => $document->project_id,
                'route' => request()->fullUrl(),
            ]);
            abort(404);
        }
    }
}
