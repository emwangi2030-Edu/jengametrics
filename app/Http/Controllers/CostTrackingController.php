<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Material;

class CostTrackingController extends Controller
{
    public function index()
    {
        // Fetch all materials
        $materials = Material::all();

        // Calculate total cost of all materials
        $totalCost = $materials->sum(function ($material) {
            return $material->unit_price * $material->quantity_in_stock;
        });

        // Pass materials and total cost to the view
        return view('cost-tracking.index', compact('materials', 'totalCost'));
    }
}
