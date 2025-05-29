<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UnitsOfMeasurementSeeder extends Seeder
{
    public function run()
    {
        $units = [
             // Length units
             ['name' => 'Millimeter', 'abbrev' => 'mm', 'type' => 'length'],
             ['name' => 'Centimeter', 'abbrev' => 'cm', 'type' => 'length'],
             ['name' => 'Meter', 'abbrev' => 'm', 'type' => 'length'],
             ['name' => 'Linear Meter', 'abbrev' => 'Lm', 'type' => 'length'],
             ['name' => 'Foot', 'abbrev' => 'ft', 'type' => 'length'],
             ['name' => 'Inch', 'abbrev' => 'in', 'type' => 'length'],
 
             // Area units
             ['name' => 'Square Centimeter', 'abbrev' => 'cm²', 'type' => 'area'],
             ['name' => 'Square Meter', 'abbrev' => 'm²', 'type' => 'area'],
             ['name' => 'Square Foot', 'abbrev' => 'ft²', 'type' => 'area'],
 
             // Volume units
             ['name' => 'Cubic Centimeter', 'abbrev' => 'cm³', 'type' => 'volume'],
             ['name' => 'Cubic Meter', 'abbrev' => 'm³', 'type' => 'volume'],
             ['name' => 'Liter', 'abbrev' => 'L', 'type' => 'volume'],
             ['name' => 'Milliliter', 'abbrev' => 'mL', 'type' => 'volume'],
 
             // Weight units
             ['name' => 'Gram', 'abbrev' => 'g', 'type' => 'weight'],
             ['name' => 'Kilogram', 'abbrev' => 'kg', 'type' => 'weight'],
             ['name' => 'Metric Ton', 'abbrev' => 't', 'type' => 'weight'],
             ['name' => 'Milligram', 'abbrev' => 'mg', 'type' => 'weight'],
 
             // Quantity units
             ['name' => 'Set', 'abbrev' => 'set', 'type' => 'quantity'],
             ['name' => 'Bag', 'abbrev' => 'Bags', 'type' => 'quantity'],
             ['name' => 'Piece', 'abbrev' => 'Pcs', 'type' => 'quantity'],
             ['name' => 'Item', 'abbrev' => 'Item', 'type' => 'quantity'],
             ['name' => 'Packet', 'abbrev' => 'Pkt', 'type' => 'quantity'],
             ['name' => 'Number', 'abbrev' => 'Nr', 'type' => 'quantity'],
             ['name' => 'Boxes', 'abbrev' => 'boxes', 'type' => 'quantity'],
             ['name' => 'Rolls', 'abbrev' => 'rolls', 'type' => 'quantity'],
             ['name' => 'Sheets', 'abbrev' => 'sheets', 'type' => 'quantity'],
             ['name' => 'Pair', 'abbrev' => 'pair', 'type' => 'quantity'],
             ['name' => 'Block', 'abbrev' => 'blocks', 'type' => 'quantity'],
             ['name' => 'Tubes', 'abbrev' => 'tubes', 'type' => 'quantity'],
             ['name' => 'Cans', 'abbrev' => 'cans', 'type' => 'quantity'],

        ];

        DB::table('units_of_measurement')->insert($units);
    }
}
