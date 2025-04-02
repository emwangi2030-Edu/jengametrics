<?php

namespace Database\Seeders;

use App\Models\ItemUnitOfMeasurement;
use Illuminate\Database\Seeder;

class ItemUnitOfMeasurementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $units = [
            ['name' => 'millimeter', 'category' => 'length', 'abbrev' => 'mm'],
            ['name' => 'centimeter', 'category' => 'length', 'abbrev' => 'cm'],
            ['name' => 'meter', 'category' => 'length', 'abbrev' => 'm'],
            ['name' => 'kilometer', 'category' => 'length', 'abbrev' => 'km'],
            ['name' => 'square millimeter', 'category' => 'area', 'abbrev' => 'mm²'],
            ['name' => 'square centimeter', 'category' => 'area', 'abbrev' => 'cm²'],
            ['name' => 'square meter', 'category' => 'area', 'abbrev' => 'm²'],
            ['name' => 'hectare', 'category' => 'area', 'abbrev' => 'ha'],
            ['name' => 'cubic millimeter', 'category' => 'volume', 'abbrev' => 'mm³'],
            ['name' => 'cubic centimeter', 'category' => 'volume', 'abbrev' => 'cm³'],
            ['name' => 'cubic meter', 'category' => 'volume', 'abbrev' => 'm³'],
            ['name' => 'liter', 'category' => 'volume', 'abbrev' => 'L'],
            ['name' => 'milligram', 'category' => 'weight', 'abbrev' => 'mg'],
            ['name' => 'gram', 'category' => 'weight', 'abbrev' => 'g'],
            ['name' => 'kilogram', 'category' => 'weight', 'abbrev' => 'kg'],
            ['name' => 'ton', 'category' => 'weight', 'abbrev' => 't'],
            ['name' => 'piece', 'category' => 'quantity', 'abbrev' => 'pc'],
            ['name' => 'per tree', 'category' => 'quantity', 'abbrev' => 'tree'],
            ['name' => 'per stump', 'category' => 'quantity', 'abbrev' => 'stump'],
            ['name' => 'number', 'category' => 'quantity', 'abbrev' => 'no.'],
            ['name' => 'bag', 'category' => 'quantity', 'abbrev' => 'bag'],
        ];
    
        foreach ($units as $unit) {
            ItemUnitOfMeasurement::create($unit);
        }
    }
}
