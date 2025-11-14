<?php

namespace App\Services;

use App\Models\BqDocument;
use App\Models\BqLevel;
use App\Models\BqSection;
use App\Models\BomItem;
use App\Models\BomLabour;
use App\Models\Element;
use App\Models\Item;
use App\Models\ItemMaterial;
use App\Models\Product;
use App\Models\Section;
use Illuminate\Support\Facades\DB;

class BqItemCreator
{
    public function create(
        BqDocument $document,
        Section $section,
        BqLevel $level,
        Element $element,
        Item $item,
        float $quantity,
        float $rate,
        int $projectId,
        ?float $amount = null
    ): BqSection {
        return DB::transaction(function () use ($document, $section, $level, $element, $item, $quantity, $rate, $projectId, $amount) {
            $computedAmount = $amount ?? ($quantity * $rate);

            $bqSection = BqSection::create([
                'bq_document_id' => $document->id,
                'bq_level_id' => $level->id,
                'project_id' => $projectId,
                'section_id' => $section->id,
                'element_id' => $element->id,
                'item_id' => $item->id,
                'item_name' => $item->name,
                'units' => $item->unit_of_measurement,
                'quantity' => $quantity,
                'rate' => $rate,
                'amount' => $computedAmount,
            ]);

            $this->syncBom($bqSection, $item, $quantity, $projectId);

            return $bqSection;
        });
    }

    public function refresh(BqSection $bqSection, Item $item, float $quantity, int $projectId): void
    {
        DB::transaction(function () use ($bqSection, $item, $quantity, $projectId) {
            $this->syncBom($bqSection, $item, $quantity, $projectId);
        });
    }

    protected function syncBom(BqSection $bqSection, Item $item, float $quantity, int $projectId): void
    {
        BomItem::where('bq_section_id', $bqSection->id)->delete();
        BomLabour::where('bq_section_id', $bqSection->id)->delete();

        $labourRate = (float) ($item->labour ?? 0);

        BomLabour::create([
            'section_id' => $bqSection->section_id,
            'item_id' => $bqSection->item_id,
            'quantity' => $quantity,
            'rate' => $labourRate,
            'amount' => $quantity * $labourRate,
            'project_id' => $projectId,
            'bq_section_id' => $bqSection->id,
            'bq_document_id' => $bqSection->bq_document_id,
        ]);

        $materials = ItemMaterial::where('item_id', $item->id)->get();

        foreach ($materials as $material) {
            $product = Product::find($material->product_id);
            $conversionFactor = (float) ($material->conversion_factor ?? 0);
            $materialQuantity = $quantity * $conversionFactor;
            $rate = $product?->rate ?? 0;
            $amount = $materialQuantity * $rate;

            BomItem::create([
                'section_id' => $bqSection->section_id,
                'item_id' => $bqSection->item_id,
                'item_material_id' => $material->id,
                'product_id' => $material->product_id,
                'quantity' => $materialQuantity,
                'rate' => $rate,
                'amount' => $amount,
                'project_id' => $projectId,
                'bq_section_id' => $bqSection->id,
                'bq_document_id' => $bqSection->bq_document_id,
            ]);
        }
    }
}
