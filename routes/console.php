<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Artisan::command('boq:check {--verbose}', function () {
    $v = $this->option('verbose');

    $sectionsOrphans = DB::table('bom_items as bi')
        ->leftJoin('sections as s', 's.id', '=', 'bi.section_id')
        ->whereNotNull('bi.section_id')
        ->whereNull('s.id')
        ->count();

    $materialsOrphans = DB::table('bom_items as bi')
        ->leftJoin('item_materials as im', 'im.id', '=', 'bi.item_material_id')
        ->whereNotNull('bi.item_material_id')
        ->whereNull('im.id')
        ->count();

    $productsOrphans = DB::table('bom_items as bi')
        ->leftJoin('products as p', 'p.id', '=', 'bi.product_id')
        ->whereNotNull('bi.product_id')
        ->whereNull('p.id')
        ->count();

    $bqSectionsOrphans = DB::table('bom_items as bi')
        ->leftJoin('bq_sections as bqs', 'bqs.id', '=', 'bi.bq_section_id')
        ->whereNotNull('bi.bq_section_id')
        ->whereNull('bqs.id')
        ->count();

    $boqMismatch = DB::table('bq_sections')
        ->whereRaw('(COALESCE(rate,0) * COALESCE(quantity,0)) <> COALESCE(amount,0)')
        ->count();

    $this->info('BoM Orphans:');
    $this->line("  section_id without parent: $sectionsOrphans");
    $this->line("  item_material_id without parent: $materialsOrphans");
    $this->line("  product_id without parent: $productsOrphans");
    $this->line("  bq_section_id without parent: $bqSectionsOrphans");
    $this->newLine();

    $this->info('BoQ Mismatches:');
    $this->line("  bq_sections where amount != rate*quantity: $boqMismatch");

    if ($v) {
        $this->newLine();
        $this->info('Sample mismatched BoQ rows (up to 10):');
        $rows = DB::table('bq_sections')
            ->select('id','section_id','item_id','rate','quantity','amount')
            ->whereRaw('(COALESCE(rate,0) * COALESCE(quantity,0)) <> COALESCE(amount,0)')
            ->limit(10)
            ->get();
        foreach ($rows as $r) {
            $this->line("  id={$r->id} section={$r->section_id} item={$r->item_id} rate={$r->rate} qty={$r->quantity} amount={$r->amount}");
        }
    }
})->purpose('Check BoQ and BoM consistency');
