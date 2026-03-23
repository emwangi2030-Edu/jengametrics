<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use App\Support\ApiResponse;
use Illuminate\Http\Request;

class SupplierWriteController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'contact_info' => ['required', 'string', 'max:255'],
        ]);

        $supplier = Supplier::firstOrCreate([
            'name' => $validated['name'],
            'contact_info' => $validated['contact_info'],
        ]);

        return ApiResponse::success([
            'id' => $supplier->id,
            'name' => $supplier->name,
            'contact_info' => $supplier->contact_info,
        ], message: 'Supplier saved.');
    }
}

