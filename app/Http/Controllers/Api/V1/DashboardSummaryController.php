<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Material;
use App\Models\Payment;
use App\Models\ProjectStep;
use App\Models\Worker;
use App\Support\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardSummaryController extends Controller
{
    public function __invoke(Request $request)
    {
        $project = $request->attributes->get('active_project');
        $projectId = (int) $project->id;

        $totalWorkers = Worker::where('project_id', $projectId)->count();
        $totalMaterialExpenses = (float) Material::where('project_id', $projectId)
            ->sum(DB::raw('COALESCE(unit_price, 0) * COALESCE(quantity_purchased, 0)'));
        $totalPayments = (float) Payment::where('project_id', $projectId)->sum('amount');
        $totalSpent = $totalMaterialExpenses + $totalPayments;
        $budget = (float) ($project->budget ?? 0);

        $totalProjectSteps = ProjectStep::where('project_id', $projectId)->count();
        $completedProjectSteps = ProjectStep::where('project_id', $projectId)
            ->where('is_completed', true)
            ->count();
        $projectCompletionPercent = $totalProjectSteps > 0
            ? (int) round(($completedProjectSteps / $totalProjectSteps) * 100)
            : 0;

        return ApiResponse::success([
            'project' => [
                'id' => $project->id,
                'name' => $project->name,
                'status' => $project->status,
                'budget' => $budget,
            ],
            'kpis' => [
                'active_projects' => 1,
                'total_budget' => $budget,
                'total_spent' => $totalSpent,
                'total_workers' => $totalWorkers,
                'pending_invoices' => 0,
            ],
            'progress' => [
                'total_steps' => $totalProjectSteps,
                'completed_steps' => $completedProjectSteps,
                'completion_percent' => $projectCompletionPercent,
            ],
            'cost_breakdown' => [
                'materials' => $totalMaterialExpenses,
                'labour' => $totalPayments,
            ],
        ]);
    }
}

