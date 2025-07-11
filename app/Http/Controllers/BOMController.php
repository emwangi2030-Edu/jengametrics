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
use App\Models\Project;
use App\Models\Requisition;
use Illuminate\Support\Facades\Auth;

class BOMController extends Controller
{
    public function index()
    {
        $bqDocument = get_project()->id;
        $sections = Section::orderBy('id', 'asc')->get();
        // $total_section_material_costs = BomItem::whereProjectId(project_id())
        //                                     ->where('section_id', $sections->id)
        //                                     ->selectRaw('SUM(quantity * rate) as total')
        //                                     ->value('total');
        $totalAmount = BomItem::where('project_id', project_id())
                                ->selectRaw('SUM(quantity * rate) as total')
                                ->value('total');
        $totalLabour = BomLabour::where('project_id', project_id())
                                ->sum('amount');


        return view('boms.index', compact('bqDocument', 'sections', 'totalAmount', 'totalLabour'));
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
             $quantity = $item['quantity'];
             $amount = $rate * $quantity;

            BomItem::create([
                'bom_id' => $bom->id,
                'item_description' => $item['description'],
                'quantity' => $quantity,
                'unit' => $item['unit'],
                'rate' => $rate,
                'amount' => $amount,
            ]);
        }

        return redirect()->route('boms.index');
    }

    public function show($id)
    {
        $bqSection = Section::find($id);

        $rawItems = BomItem::whereProjectId(project_id())
            ->where('section_id', $id)
            ->get();

        $items = collect();
        $requisitionableItems = collect(); // for modal
        $section_name = $bqSection?->name;

        $groupedItems = $rawItems->groupBy('product_id');

        foreach ($groupedItems as $product_id => $group) {
            $sampleItem = $group->first();
            $totalQty = $group->sum('quantity');
            $totalAmt = $group->sum('amount');

            $product = Product::find($product_id);

            $sampleItem->total_quantity = $totalQty;
            $sampleItem->total_amount = $totalAmt;
            $sampleItem->unit = $product?->unit ?? 'N/A';

            // Push for BoM table
            $items->push($sampleItem);

            // Calculate remaining quantity
            $requisitionedQty = Requisition::whereIn('bom_item_id', $group->pluck('id'))
                ->whereIn('status', ['pending', 'approved'])
                ->sum('quantity_requested');

            $remaining = max(0, $totalQty - $requisitionedQty);
            $sampleItem->remaining_quantity = $remaining;

            // Push for requisition modal only if remaining >= 1
            if ($remaining >= 1) {
                $requisitionableItems->push(clone $sampleItem);
            }
        }

        $labours = BomLabour::whereProjectId(project_id())
            ->where('section_id', $id)
            ->get();

        return view('boms.show', compact('bqSection', 'items', 'requisitionableItems', 'labours', 'section_name'));
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
       $totalEstimatedCost = BomItem::whereProjectId(project_id())
            ->selectRaw('SUM(quantity * rate) as total')
            ->value('total');

        $totalEstimatedCost_labour = BomLabour::whereProjectId(project_id())->sum('amount');

        $materials = Material::whereProjectId(project_id())->get();

        // Calculate total cost of all materials
        $total_actual_cost = $materials->sum(function ($material) {
            return $material->unit_price * $material->quantity_in_stock;
        });

        // Get the project details
        $projectId = Auth::user()->project_id;

        $project = Project::find($projectId);

        return view('report.report', compact('totalEstimatedCost', 'totalEstimatedCost_labour', 'total_actual_cost', 'project'));
    }
}
