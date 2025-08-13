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

        $approvedSummary = Requisition::select('bom_item_id')
            ->selectRaw('SUM(quantity_requested) as total_quantity')
            ->where('status', 'approved')
            ->groupBy('bom_item_id')
            ->with('bomItem.item_material')
            ->get();

        return view('requisitions.index', compact('requisitions', 'approvedSummary'));
    }

    public function create()
    {
        $bomItems = BomItem::whereProjectId(project_id())->get();

        // Group BOM items by product_id
        $groupedItems = $bomItems->groupBy('product_id');
        $items = collect();
        $section_name = null;

        foreach ($groupedItems as $product_id => $group) {
            $sampleItem = $group->first();
            $totalQty = $group->sum('quantity');
            $totalAmt = $group->sum('amount');

            // Get all requisitions related to any of the BOM items in this group
            $requisitionedQty = Requisition::whereIn('bom_item_id', $group->pluck('id'))
                ->whereIn('status', ['pending', 'approved'])
                ->sum('quantity_requested');

            $product = Product::find($product_id);
            $section = Section::find($sampleItem->section_id);

            $sampleItem->total_quantity = $totalQty;
            $sampleItem->total_amount = $totalAmt;
            $sampleItem->remaining_quantity = max(0, $totalQty - $requisitionedQty);
            $sampleItem->unit = $product?->unit ?? 'N/A';

            // ✅ Only push items with remaining_quantity >= 1
            if ($sampleItem->remaining_quantity >= 1) {
                $items->push($sampleItem);
            }

            if (!$section_name) {
                $section_name = $section?->name;
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

        $bomItem = BomItem::findOrFail($request->bom_item_id);
        $productId = $bomItem->product_id;

        // Total quantity of this material in the BOM for this project
        $totalAvailable = BomItem::where('product_id', $productId)
            ->whereProjectId(project_id())
            ->sum('quantity');

        // Total quantity already requisitioned (pending or approved)
        $alreadyRequisitioned = Requisition::whereIn('bom_item_id', BomItem::where('product_id', $productId)->pluck('id'))
            ->whereIn('status', ['pending', 'approved'])
            ->sum('quantity_requested');

        $remaining = $totalAvailable - $alreadyRequisitioned;

        // Reject if user exceeds available quantity
        if ($request->quantity_requested > $remaining) {
            return back()->withErrors([
                'quantity_requested' => "The quantity entered ({$request->quantity_requested}) exceeds the available limit of {$remaining}."
            ])->withInput();
        }

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
