<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;

class CostTrackingController extends Controller
{
    public function index()
    {
        // Retrieve all materials
        $materials = Material::whereProjectId(project_id())->get();

        // Calculate total cost of all materials
        $totalCost = $materials->sum(function ($material) {
            return $material->unit_price * $material->quantity_in_stock;
        });

        // Store the total in session for use on the dashboard
        session(['totalMaterialCost' => $totalCost]);

        $projectId = Auth::user()->project_id;

        $project = Project::find($projectId);

        return view('cost-tracking.index', compact('materials', 'totalCost','project'));
    }
}

