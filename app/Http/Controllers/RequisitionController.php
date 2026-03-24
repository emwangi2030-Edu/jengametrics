<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\ResolvesActiveProject;
use Illuminate\Http\Request;
use App\Models\Requisition;
use App\Models\BomItem;
use App\Models\Section;
use App\Models\UnitOfMeasurement;
use App\Models\BqDocument;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Carbon\Unit;

class RequisitionController extends Controller
{
    use ResolvesActiveProject;

    public function index(Request $request)
    {
        $project = $this->resolveActiveProject();
        if (! $project) {
            return redirect()
                ->route('dashboard')
                ->with('warning', __('No project is selected. Please choose a project first.'));
        }

        $projectId = (int) $project->id;

        $projectScope = function ($query) use ($projectId) {
            $query->whereHas('bomItem', function ($bomQuery) use ($projectId) {
                $bomQuery->where('project_id', $projectId);
            })->orWhere(function ($adhocQuery) use ($projectId) {
                $adhocQuery->whereNull('bom_item_id')
                    ->whereHas('requester', function ($userQuery) use ($projectId) {
                        $userQuery->where('project_id', $projectId);
                    });
            });
        };

        $baseQuery = Requisition::query()->where(function ($query) use ($projectScope) {
            $projectScope($query);
        });

        $requisitions = (clone $baseQuery)
            ->with('bomItem.bqDocument', 'requester', 'approver', 'section')
            ->orderByDesc('created_at')
            ->get();

        $approvedSummary = (clone $baseQuery)
            ->where('status', 'approved')
            ->with('bomItem.item_material')
            ->get()
            ->groupBy(function ($req) {
                return $req->bom_item_id ?: $req->extra_material_name . '|' . $req->extra_unit;
            })
            ->map(function ($group) {
                $first = $group->first();
                $bomItem = $first->bomItem;
                $itemMaterial = optional($bomItem)->item_material;

                return (object) [
                    'bom_item' => $bomItem,
                    'material' => optional($itemMaterial)->name ?? $first->extra_material_name,
                    'unit' => optional($itemMaterial)->unit_of_measurement ?? $first->extra_unit,
                    'total_quantity' => $group->sum('quantity_requested'),
                ];
            })
            ->values();

        $sections = Section::all();

        $rawItems = BomItem::whereProjectId($projectId)
            ->with(['item_material', 'product', 'bqDocument', 'bqSection'])
            ->get();

        $groupedItems = $rawItems->groupBy(function (BomItem $item) {
            return $item->product_id
                ? 'product:' . $item->product_id
                : 'manual:' . $item->id;
        });

        $requisitionableItems = collect();

        $documentMap = BqDocument::whereIn('id', $rawItems->pluck('bq_document_id')->filter()->unique())->get()->keyBy('id');

        foreach ($groupedItems as $key => $group) {
            $sampleItem = $group->first();
            $totalQty = $group->sum('quantity');

            $displayName = optional($sampleItem->item_material)->name
                ?? optional($sampleItem->product)->name
                ?? $sampleItem->item_description
                ?? optional($sampleItem->bqSection)->item_name
                ?? __('Unassigned Material');

            $displayUnit = optional($sampleItem->item_material)->unit_of_measurement
                ?? optional($sampleItem->product)->unit
                ?? $sampleItem->unit
                ?? optional($sampleItem->bqSection)->units
                ?? 'unit';

            $sampleItem->display_name = $displayName;
            $sampleItem->display_unit = $displayUnit;
            $sampleItem->unit = $displayUnit;
            $sampleItem->total_quantity = $totalQty;

            $documentId = $group->pluck('bq_document_id')->filter()->first();
            $sampleItem->bq_document = $documentId ? $documentMap->get($documentId) : null;

            $requisitionedQty = Requisition::whereIn('bom_item_id', $group->pluck('id'))
                ->whereIn('status', ['pending', 'approved'])
                ->sum('quantity_requested');

            $remaining = max(0, $totalQty - $requisitionedQty);
            $sampleItem->remaining_quantity = $remaining;

            if ($remaining >= 1) {
                $requisitionableItems->push(clone $sampleItem);
            }
        }

        $units = UnitOfMeasurement::all();

        return view('requisitions.index', compact('requisitions', 'approvedSummary', 'sections', 'requisitionableItems', 'units'));
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
        $projectBomItemIds = BomItem::where('product_id', $productId)
            ->whereProjectId(project_id())
            ->pluck('id');

        $totalAvailable = BomItem::whereIn('id', $projectBomItemIds)
            ->sum('quantity');

        // Total quantity already requisitioned (pending or approved)
        $alreadyRequisitioned = Requisition::whereIn('bom_item_id', $projectBomItemIds)
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
            'section_id' => $request->section,
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

    public function storeAdhoc(Request $request)
    {
        $request->validate([
            'material_name' => 'required|string|max:255',
            'unit_of_measurement' => 'required|string|max:50',
            'quantity_requested' => 'required|numeric|min:0.01',
            'section' => 'required|exists:sections,id',
        ]);

        // Generate a requisition number
        do {
            $requisitionNo = rand(1000000, 9999999) . '-RQS';
        } while (Requisition::where('requisition_no', $requisitionNo)->exists());

        Requisition::create([
            'requisition_no' => $requisitionNo,
            'bom_item_id' => null,
            'extra_material_name' => $request->material_name,
            'extra_unit' => $request->unit_of_measurement,
            'quantity_requested' => $request->quantity_requested,
            'section_id' => $request->section,
            'requested_by' => Auth::id(),
            'requested_at' => now(),
            'status' => 'pending',
        ]);

        return redirect()->route('requisitions.index')->with('success', 'Ad-hoc requisition submitted.');
    }
}
