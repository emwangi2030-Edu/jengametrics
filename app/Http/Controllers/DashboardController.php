<?php

namespace App\Http\Controllers;

use App\Models\Worker;
use App\Models\Material;
use App\Models\Payment;
use App\Models\Project;
use App\Models\ProjectStep;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        if (!$user->has_project) {
            return redirect()->route('wizard');
        }

        $projectId = $user->project_id;
        $project = Project::find($projectId);
        $totalWorkers = Worker::where('project_id', $projectId)->count();
        $totalMaterialExpenses = Material::where('project_id', $projectId)
                                        ->sum(DB::raw('unit_price * quantity_purchased'));
        $totalPayments = Payment::where('project_id', $projectId)
                                        ->sum('amount');

        $projectRunningWeeks = 0;
        $projectEstimatedWeeks = $project?->project_duration ? (int) $project->project_duration : null;
        $projectDurationColorClass = 'text-success';
        $projectDurationExceeded = false;

        if ($project && $project->created_at) {
            $projectRunningWeeks = max(
                0,
                (int) floor($project->created_at->startOfDay()->diffInDays(now()->startOfDay()) / 7)
            );
        }

        if ($projectEstimatedWeeks && $projectEstimatedWeeks > 0) {
            $ratio = $projectRunningWeeks / $projectEstimatedWeeks;

            if ($ratio >= 1) {
                $projectDurationColorClass = 'text-danger';
                $projectDurationExceeded = true;
            } elseif ($ratio >= 0.8) {
                $projectDurationColorClass = 'text-danger';
            } elseif ($ratio >= 0.5) {
                $projectDurationColorClass = 'text-warning';
            } else {
                $projectDurationColorClass = 'text-success';
            }
        }

        // Get selected year or default to current year
        $selectedYear = $request->input('year', now()->year);

        $rawExpenses = Material::select(
            DB::raw("MONTH(created_at) as month_num"),
            DB::raw("DATE_FORMAT(created_at, '%b') as month"),
            DB::raw("SUM(quantity_purchased * unit_price) as total")
        )
        ->where('project_id', $projectId)
        ->whereYear('created_at', $selectedYear)
        ->groupBy('month_num', 'month')
        ->orderBy('month_num')
        ->get()
        ->keyBy('month_num');

        $rawLabourExpenses = Payment::selectRaw("MONTH(COALESCE(payment_date, created_at)) as month_num")
            ->selectRaw("DATE_FORMAT(COALESCE(payment_date, created_at), '%b') as month")
            ->selectRaw("SUM(amount) as total")
            ->where('project_id', $projectId)
            ->whereYear(DB::raw('COALESCE(payment_date, created_at)'), $selectedYear)
            ->groupByRaw("MONTH(COALESCE(payment_date, created_at)), DATE_FORMAT(COALESCE(payment_date, created_at), '%b')")
            ->orderBy('month_num')
            ->get()
            ->keyBy('month_num');

        $allMonths = collect([
            1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr',
            5 => 'May', 6 => 'Jun', 7 => 'Jul', 8 => 'Aug',
            9 => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Dec'
        ]);

        $labels = [];
        $data = [];
        $labourData = [];

        foreach ($allMonths as $monthNum => $monthName) {
            $labels[] = $monthName;
            $data[] = $rawExpenses->has($monthNum) ? (float) $rawExpenses[$monthNum]->total : 0;
            $labourData[] = $rawLabourExpenses->has($monthNum) ? (float) $rawLabourExpenses[$monthNum]->total : 0;
        }
        // Get all years for dropdown
        $materialYears = Material::where('project_id', $projectId)
            ->select(DB::raw('YEAR(created_at) as year'))
            ->distinct()
            ->pluck('year');

        $paymentYears = Payment::where('project_id', $projectId)
            ->select(DB::raw('YEAR(COALESCE(payment_date, created_at)) as year'))
            ->distinct()
            ->pluck('year');

        $availableYears = $materialYears
            ->merge($paymentYears)
            ->map(fn ($year) => $year ? (int) $year : null)
            ->filter()
            ->unique()
            ->sort()
            ->values();

        $projectSteps = ProjectStep::where('project_id', $projectId)
            ->orderBy('position')
            ->orderBy('id')
            ->get();

        $totalProjectSteps = $projectSteps->count();
        $completedProjectSteps = $projectSteps->where('is_completed', true)->count();
        $projectCompletionPercent = $totalProjectSteps > 0
            ? (int) round(($completedProjectSteps / $totalProjectSteps) * 100)
            : 0;

        return view('dashboard', compact(
            'totalWorkers',
            'totalMaterialExpenses',
            'totalPayments',
            'labels',
            'data',
            'labourData',
            'availableYears',
            'selectedYear',
            'projectRunningWeeks',
            'projectEstimatedWeeks',
            'projectDurationColorClass',
            'projectDurationExceeded',
            'projectSteps',
            'totalProjectSteps',
            'completedProjectSteps',
            'projectCompletionPercent'
        ));
    }

    public function storeProjectSteps(Request $request)
    {
        $user = Auth::user();
        $projectId = (int) ($user->project_id ?? 0);

        if (!$projectId) {
            return redirect()->route('dashboard')->with('warning', 'Select a project first.');
        }

        $validated = $request->validate([
            'steps' => 'required|array|min:1',
            'steps.*' => 'required|string|max:255',
        ]);

        $steps = collect($validated['steps'])
            ->map(fn ($step) => trim((string) $step))
            ->filter()
            ->values();

        if ($steps->isEmpty()) {
            return redirect()->route('dashboard')->with('warning', 'Add at least one project step.');
        }

        $nextPosition = ((int) ProjectStep::where('project_id', $projectId)->max('position')) + 1;

        foreach ($steps as $step) {
            ProjectStep::create([
                'project_id' => $projectId,
                'created_by' => $user->id,
                'title' => $step,
                'position' => $nextPosition++,
            ]);
        }

        return redirect()->route('dashboard')->with('success', 'Project step(s) added.');
    }

    public function toggleProjectStep(Request $request, ProjectStep $projectStep)
    {
        $user = Auth::user();
        $projectId = (int) ($user->project_id ?? 0);

        if ((int) $projectStep->project_id !== $projectId) {
            abort(403);
        }

        $isCompleted = $request->boolean('is_completed');

        $projectStep->update([
            'is_completed' => $isCompleted,
            'completed_at' => $isCompleted ? now() : null,
        ]);

        return redirect()->route('dashboard');
    }
}
