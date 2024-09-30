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
            ['name' => 'Millimeter', 'type' => 'length'],
            ['name' => 'Centimeter', 'type' => 'length'],
            ['name' => 'Meter', 'type' => 'length'],
            ['name' => 'Kilometer', 'type' => 'length'],

            // Area units
            ['name' => 'Square Centimeter', 'type' => 'area'],
            ['name' => 'Square Meter', 'type' => 'area'],
            ['name' => 'Hectare', 'type' => 'area'],
            ['name' => 'Square Kilometer', 'type' => 'area'],

            // Volume units
            ['name' => 'Cubic Centimeter', 'type' => 'volume'],
            ['name' => 'Cubic Meter', 'type' => 'volume'],
            ['name' => 'Liter', 'type' => 'volume'],
            ['name' => 'Milliliter', 'type' => 'volume'],

            // Weight units
            ['name' => 'Gram', 'type' => 'weight'],
            ['name' => 'Kilogram', 'type' => 'weight'],
            ['name' => 'Metric Ton', 'type' => 'weight'],
            ['name' => 'Milligram', 'type' => 'weight']
        ];

        DB::table('units_of_measurement')->insert($units);
    }
}
