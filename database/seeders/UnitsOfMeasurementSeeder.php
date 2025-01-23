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
             ['name' => 'Kilometer', 'abbrev' => 'km', 'type' => 'length'],
             ['name' => 'Roll', 'abbrev' => 'roll', 'type' => 'length'],
             ['name' => 'Foot', 'abbrev' => 'ft', 'type' => 'length'],
 
             // Area units
             ['name' => 'Square Centimeter', 'abbrev' => 'cm²', 'type' => 'area'],
             ['name' => 'Square Meter', 'abbrev' => 'm²', 'type' => 'area'],
             ['name' => 'Hectare', 'abbrev' => 'ha', 'type' => 'area'],
             ['name' => 'Square Kilometer', 'abbrev' => 'km²', 'type' => 'area'],
 
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
             ['name' => 'Each', 'abbrev' => 'each', 'type' => 'quantity'],
             ['name' => 'Set', 'abbrev' => 'set', 'type' => 'quantity'],
             ['name' => 'Bag', 'abbrev' => 'bag', 'type' => 'quantity'],
             ['name' => 'Piece', 'abbrev' => 'pcs', 'type' => 'quantity'],
             ['name' => 'Item', 'abbrev' => 'item', 'type' => 'quantity'],
             ['name' => 'Packet', 'abbrev' => 'pkt', 'type' => 'quantity'],
 
             // Time units
             ['name' => 'Hour', 'abbrev' => 'hr', 'type' => 'time']
        ];

        DB::table('units_of_measurement')->insert($units);
    }
}
