<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\Project;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;

class CostTrackingController extends Controller
{
    public function index()
    {
        $materials = Material::whereProjectId(project_id())->get();

        $payments = Payment::whereProjectId(project_id())->get();

        $totalCost = $materials->sum(function ($material) {
            return $material->unit_price * $material->quantity_purchased;
        });

        $totalPayments = $payments->sum('amount');

        session(['totalMaterialCost' => $totalCost]);

        $projectId = Auth::user()->project_id;

        $project = Project::find($projectId);

        return view('cost-tracking.index', compact('materials', 'totalCost','project','payments','totalPayments'));
    }
}

