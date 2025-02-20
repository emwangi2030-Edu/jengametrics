<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BqDocument;
use App\Models\Bom;
use App\Models\BomLabour;
use App\Models\BomItem;
use App\Models\Section;
use App\Models\BqSection;
use App\Models\Material;
use App\Models\Product;

class BOMController extends Controller
{
    public function index()
    {
        $bqDocument = get_project()->id;
        $sections = Section::orderBy('id', 'asc')->get();

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
             // Fetch the rate from the products table based on product_id
             $product = Product::where('id', $item['product_id'])->get();

             $rate = $product ? $product->rate : 0;

            BomItem::create([
                'bom_id' => $bom->id,
                'item_description' => $item['description'],
                'quantity' => $item['quantity'],
                'unit' => $item['unit'],
                'rate' => $rate,
                'amount' => $rate * $item['quantity'],
            ]);
        }

        return redirect()->route('boms.index');
    }

    public function show($id)
    {
        // Fetch sections related to this BQ Document
        $bqSection = Section::find($id);

        // Fetch all bom_items for the given section and project
        $rawItems = BomItem::whereProjectId(project_id())
            ->where('section_id', $id)
            ->get();

        // Process items to combine entries with the same product_id
        $items = collect(); // Initialize an empty collection to store unique items

        foreach ($rawItems as $item) {
            // Check if this product_id already exists in the collection
            $existingItem = $items->firstWhere('product_id', $item->product_id);

            if ($existingItem) {
                // If product_id already exists, add the quantity and amount to the first occurrence
                $existingItem->total_quantity += $item->quantity;
                $existingItem->total_amount += $item->amount;

            } else {
                // If not found, store this item as the first instance
                $item->total_quantity = $item->quantity;
                $item->total_amount = $item->amount;
                $items->push($item);
            }
        }

        // Fetch labour records
        $labours = BomLabour::whereProjectId(project_id())->where('section_id', $id)->get();
        
        // Pass the processed collection to the view
        return view('boms.show', compact('bqSection', 'items', 'labours'));
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
        $totalEstimatedCost = BomItem::whereProjectId(project_id())->sum('amount');

        $totalEstimatedCost_labour = BomLabour::whereProjectId(project_id())->sum('amount');

        $materials = Material::whereProjectId(project_id())->get();

        // Calculate total cost of all materials
        $total_actual_cost = $materials->sum(function ($material) {
            return $material->unit_price * $material->quantity_in_stock;
        });


        return view('report.report', compact('totalEstimatedCost', 'totalEstimatedCost_labour', 'total_actual_cost'));
    }
}
