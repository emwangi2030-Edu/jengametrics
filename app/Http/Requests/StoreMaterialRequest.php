<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMaterialRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user()?->project_id;
    }

    public function rules(): array
    {
        return [
            'material_type' => 'required|in:bom,adhoc',
            'unit_price' => 'required|numeric',
            'quantity_in_stock' => 'required|numeric|min:0.01',
            'supplier_id' => 'required|exists:suppliers,id',
            'requisitioned_quantity' => 'nullable|numeric',
            'expected_quantity' => 'nullable|numeric',
            'variance' => 'nullable|string',
            'product_id' => 'nullable|integer',
            'adhoc_name' => 'nullable|string|max:255',
            'adhoc_unit' => 'nullable|string|max:50',
        ];
    }
}
