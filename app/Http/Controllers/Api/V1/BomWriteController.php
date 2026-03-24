<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Bom;
use App\Models\BomItem;
use App\Models\Product;
use App\Support\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BomWriteController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'bq_document_id' => ['required', 'exists:bq_documents,id'],
            'bom_name' => ['required', 'string', 'max:255'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['nullable', 'integer'],
            'items.*.description' => ['nullable', 'string', 'max:500'],
            'items.*.quantity' => ['required', 'numeric', 'min:0.0001'],
            'items.*.unit' => ['nullable', 'string', 'max:50'],
            'items.*.section_id' => ['nullable', 'integer'],
            'items.*.item_id' => ['nullable', 'integer'],
            'items.*.item_material_id' => ['nullable', 'integer'],
            'items.*.rate' => ['nullable', 'numeric', 'min:0'],
        ]);

        $project = $request->attributes->get('active_project');

        $created = DB::transaction(function () use ($validated, $project) {
            $bom = Bom::create([
                'bq_document_id' => (int) $validated['bq_document_id'],
                'bom_name' => $validated['bom_name'],
            ]);

            $items = [];
            foreach ($validated['items'] as $item) {
                $product = ! empty($item['product_id']) ? Product::find($item['product_id']) : null;
                $rate = (float) ($item['rate'] ?? $product?->rate ?? 0);
                $quantity = (float) $item['quantity'];

                $items[] = BomItem::create([
                    'bom_id' => $bom->id,
                    'bq_document_id' => (int) $validated['bq_document_id'],
                    'project_id' => (int) $project->id,
                    'section_id' => $item['section_id'] ?? null,
                    'item_id' => $item['item_id'] ?? null,
                    'item_material_id' => $item['item_material_id'] ?? null,
                    'product_id' => $item['product_id'] ?? null,
                    'item_description' => $item['description'] ?? $product?->name,
                    'unit' => $item['unit'] ?? $product?->unit,
                    'quantity' => $quantity,
                    'rate' => $rate,
                    'amount' => $rate * $quantity,
                ]);
            }

            return [$bom, $items];
        });

        [$bom, $items] = $created;

        return ApiResponse::success([
            'id' => $bom->id,
            'bom_name' => $bom->bom_name,
            'items_count' => count($items),
        ], message: 'BoM created.', status: 201);
    }
}

