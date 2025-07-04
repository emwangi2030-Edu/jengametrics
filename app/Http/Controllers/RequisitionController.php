<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Requisition;
use App\Models\BomItem;
use App\Models\Product;
use App\Models\Section;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;

class RequisitionController extends Controller
{
   public function index(Request $request)
    {
        $requisitions = Requisition::with('bomItem', 'requester', 'approver')
            ->orderByDesc('created_at')
            ->get();

        return view('requisitions.index', compact('requisitions'));
    }


    public function create()
    {
        $bomItems = BomItem::whereProjectId(project_id())->get();

        $items = collect(); // Initialize an empty collection to store unique items

        foreach ($bomItems as $item) {
            // Check if this product_id already exists in the collection
            $existingItem = $items->firstWhere('product_id', $item->product_id);

            if ($existingItem) {
                // Add quantity and amount
                $existingItem->total_quantity += $item->quantity;
                $existingItem->total_amount += $item->amount;
            } else {
                // First time seeing this product_id
                $item->total_quantity = $item->quantity;
                $item->total_amount = $item->amount;

                // Add unit from product table
                $product = Product::find($item->product_id);
                $item->unit = $product?->unit ?? 'N/A';

                $items->push($item);

                // Add section
                $section = Section::find($item?->section_id);
                $section_name = $section?->name ?? null;
            }
        }

        return view('requisitions.create', compact('items', 'section_name'));
    }

   public function store(Request $request)
    {
        $request->validate([
            'bom_item_id' => 'required|exists:bom_items,id',
            'quantity_requested' => 'required|numeric|min:0.01',
        ]);

        // Generate a unique requisition number
        do {
            $requisitionNo = rand(1000000, 9999999) . '-RQS';
        } while (Requisition::where('requisition_no', $requisitionNo)->exists());

        // Store the requisition
        Requisition::create([
            'requisition_no' => $requisitionNo,
            'bom_item_id' => $request->bom_item_id,
            'quantity_requested' => $request->quantity_requested,
            'requested_by' => Auth::id(),
            'requested_at' => now(),
            'status' => 'pending',
        ]);

        return redirect()->route('requisitions.index')->with('success', 'Requisition submitted.');
    }


    public function approve($id)
    {
        $requisition = Requisition::findOrFail($id);
        $requisition->update([
            'status' => 'approved',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        return back()->with('success', 'Requisition approved.');
    }

    public function reject($id)
    {
        $requisition = Requisition::findOrFail($id);
        $requisition->update([
            'status' => 'rejected',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        return back()->with('info', 'Requisition rejected.');
    }
}
