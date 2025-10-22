<?php

namespace App\Http\Controllers;

use App\Models\BqDocument;
use App\Models\BqSection;
use App\Models\Section;
use App\Models\Item;
use App\Models\Element;
use Illuminate\Http\Request;
use App\Services\BqItemCreator;

class BqSectionController extends Controller
{
    protected BqItemCreator $bqItemCreator;

    public function __construct(BqItemCreator $bqItemCreator)
    {
        $this->bqItemCreator = $bqItemCreator;
    }

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
            $element,
            $item,
            $quantity,
            $rate,
            (int) project_id(),
            $amount
        );

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
