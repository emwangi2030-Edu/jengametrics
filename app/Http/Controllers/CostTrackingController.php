<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\ResolvesActiveProject;
use App\Models\Material;
use App\Models\Project;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;

class CostTrackingController extends Controller
{
    use ResolvesActiveProject;

    public function index()
    {
        $project = $this->resolveActiveProject();
        if (! $project) {
            return redirect()
                ->route('dashboard')
                ->with('warning', __('No project is selected. Please choose a project first.'));
        }

        $projectId = (int) $project->id;

        $materials = Material::whereProjectId($projectId)->get();

        $payments = Payment::with('worker')
            ->whereProjectId($projectId)
            ->get();

        $totalCost = $materials->sum(function ($material) {
            return $material->unit_price * $material->quantity_purchased;
        });

        $totalPayments = $payments->sum('amount');

        session(['totalMaterialCost' => $totalCost]);

        return view('cost-tracking.index', compact('materials', 'totalCost','project','payments','totalPayments'));
    }
}
