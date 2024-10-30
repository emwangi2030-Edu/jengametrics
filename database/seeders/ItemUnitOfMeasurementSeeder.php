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
            ['name' => 'millimeter', 'category' => 'length'],
            ['name' => 'centimeter', 'category' => 'length'],
            ['name' => 'meter', 'category' => 'length'],
            ['name' => 'kilometer', 'category' => 'length'],
            ['name' => 'square millimeter', 'category' => 'area'],
            ['name' => 'square centimeter', 'category' => 'area'],
            ['name' => 'square meter', 'category' => 'area'],
            ['name' => 'hectare', 'category' => 'area'],
            ['name' => 'cubic millimeter', 'category' => 'volume'],
            ['name' => 'cubic centimeter', 'category' => 'volume'],
            ['name' => 'cubic meter', 'category' => 'volume'],
            ['name' => 'liter', 'category' => 'volume'],
            ['name' => 'milligram', 'category' => 'weight'],
            ['name' => 'gram', 'category' => 'weight'],
            ['name' => 'kilogram', 'category' => 'weight'],
            ['name' => 'ton', 'category' => 'weight'],
            ['name' => 'piece', 'category' => 'quantity'],
            ['name' => 'per tree', 'category' => 'quantity'],
            ['name' => 'per stump', 'category' => 'quantity'],
            ['name' => 'each', 'category' => 'quantity'],
        ];
    
        foreach ($units as $unit) {
            ItemUnitOfMeasurement::create($unit);
        }
    }
}
