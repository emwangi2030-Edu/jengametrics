<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Requisition;
use App\Support\ApiResponse;
use Illuminate\Http\Request;

class RequisitionWriteController extends Controller
{
    public function storeAdhoc(Request $request)
    {
        $validated = $request->validate([
            'material_name' => ['required', 'string', 'max:255'],
            'unit_of_measurement' => ['required', 'string', 'max:50'],
            'quantity_requested' => ['required', 'numeric', 'min:0.01'],
            'section' => ['required', 'exists:sections,id'],
        ]);

        do {
            $requisitionNo = rand(1000000, 9999999) . '-RQS';
        } while (Requisition::where('requisition_no', $requisitionNo)->exists());

        $requisition = Requisition::create([
            'requisition_no' => $requisitionNo,
            'bom_item_id' => null,
            'extra_material_name' => $validated['material_name'],
            'extra_unit' => $validated['unit_of_measurement'],
            'quantity_requested' => $validated['quantity_requested'],
            'section_id' => (int) $validated['section'],
            'requested_by' => (int) $request->user()->id,
            'requested_at' => now(),
            'status' => 'pending',
        ]);

        return ApiResponse::success([
            'id' => $requisition->id,
            'requisition_no' => $requisition->requisition_no,
            'status' => $requisition->status,
        ], message: 'Ad-hoc requisition submitted.', status: 201);
    }
}

