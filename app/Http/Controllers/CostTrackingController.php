<?php

namespace App\Http\Controllers;

use App\Models\Material;

class CostTrackingController extends Controller
{
    public function index()
    {
        // Retrieve all materials
        $materials = Material::all();

        // Calculate total cost of all materials
        $totalCost = $materials->sum(function ($material) {
            return $material->unit_price * $material->quantity_in_stock;
        });

        // Store the total in session for use on the dashboard
        session(['totalMaterialCost' => $totalCost]);

        return view('cost-tracking.index', compact('materials', 'totalCost'));
    }
}

