<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Material;
use App\Models\Supplier;
use App\Support\ApiResponse;
use Illuminate\Http\Request;

class MaterialWriteController extends Controller
{
    public function storeAdhoc(Request $request)
    {
        $validated = $request->validate([
            'adhoc_name' => ['required', 'string', 'max:255'],
            'adhoc_unit' => ['required', 'string', 'max:50'],
            'unit_price' => ['required', 'numeric'],
            'quantity_in_stock' => ['required', 'numeric', 'min:0.01'],
            'supplier_id' => ['required', 'exists:suppliers,id'],
            'requisitioned_quantity' => ['nullable', 'numeric'],
        ]);

        $project = $request->attributes->get('active_project');
        $supplier = Supplier::findOrFail((int) $validated['supplier_id']);
        $quantity = (float) $validated['quantity_in_stock'];

        $material = Material::create([
            'name' => $validated['adhoc_name'],
            'product_id' => null,
            'unit_of_measure' => $validated['adhoc_unit'],
            'unit_price' => $validated['unit_price'],
            'quantity_purchased' => $quantity,
            'quantity_in_stock' => $quantity,
            'variance' => 0.0,
            'requisitioned_quantity' => (float) ($validated['requisitioned_quantity'] ?? $quantity),
            'supplier_id' => $supplier->id,
            'supplier_contact' => $supplier->contact_info,
            'project_id' => (int) $project->id,
        ]);

        return ApiResponse::success([
            'id' => $material->id,
            'name' => $material->name,
            'project_id' => $material->project_id,
        ], message: 'Material created.', status: 201);
    }
}

