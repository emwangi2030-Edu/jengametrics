<?php

namespace App\Http\Controllers;

use App\Models\Worker;
use App\Models\Material;
use App\Models\Payment;
use App\Models\Project;
use App\Models\ProgressCertificate;
use App\Models\ProjectStep;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Carbon\Carbon;
use Illuminate\Validation\Rule;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route("login");
        }


        if ($user && $user->is_admin()) {
            $users = User::query()
                ->where('user_type', 'user')
                ->whereNull('parent_user_id')
                ->orderBy('name')
                ->get();

            return view('dashboard_admin', compact('users'));
        }

        bootstrap_user_active_project_if_missing();

        if (file_exists(public_path('build/manifest.json'))) {
            // Render the React dashboard when Vite build assets are available.
            return view('ui.jenga-metrics');
        }

        $projectId = (int) ($user->project_id ?? 0);
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
        $projectCreatedDate = $project?->created_at?->format('M d, Y');

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

        $selectedYear = $request->input('year', now()->year);
        $chartData = \App\Services\DashboardChartService::chartDataForProject($projectId, $selectedYear);
        $labels = $chartData['labels'];
        $data = $chartData['data'];
        $labourData = $chartData['labourData'];
        $availableYears = $chartData['availableYears'];

        $projectSteps = ProjectStep::where('project_id', $projectId)
            ->orderBy('position')
            ->orderBy('id')
            ->get();

        $projectStepStats = $this->projectStepStats($projectId);
        $totalProjectSteps = $projectStepStats['totalProjectSteps'];
        $completedProjectSteps = $projectStepStats['completedProjectSteps'];
        $projectCompletionPercent = $projectStepStats['projectCompletionPercent'];
        // Dashboard design: all projects, alerts, recent activity
        $owned = $user->ownedProjects()->get();
        $assigned = $user->projects()->get();
        $allProjects = $owned->merge($assigned)->unique('id')->sortBy('name', SORT_NATURAL | SORT_FLAG_CASE)->values();
        if ($allProjects->isEmpty() && $projectId && $project) {
            $allProjects = collect([$project]);
        }
        $projectIds = $allProjects->pluck('id')->toArray();
        $dashboardProjects = [];
        $totalBudget = 0;
        $totalSpent = 0;
        $activeCount = 0;
        foreach ($allProjects as $proj) {
            $budget = (float) ($proj->budget ?? 0);
            $matSpent = (float) Material::where('project_id', $proj->id)->sum(DB::raw('COALESCE(unit_price, 0) * COALESCE(quantity_purchased, 0)'));
            $paySpent = (float) Payment::where('project_id', $proj->id)->sum('amount');
            $spent = $matSpent + $paySpent;
            $totalBudget += $budget;
            $totalSpent += $spent;
            $totalSteps = ProjectStep::where('project_id', $proj->id)->count();
            $completedSteps = ProjectStep::where('project_id', $proj->id)->where('is_completed', true)->count();
            $progress = $totalSteps > 0 ? (int) round(($completedSteps / $totalSteps) * 100) : 0;
            $weeks = (int) ($proj->project_duration ?? 0);
            $created = $proj->created_at ? $proj->created_at->copy()->startOfDay()->diffInWeeks(now()->startOfDay()) : 0;
            $weeksLeft = $weeks > 0 ? max(0, $weeks - $created) : 0;
            $currentStep = ProjectStep::where('project_id', $proj->id)->where('is_completed', false)->orderBy('position')->first();
            $phase = $currentStep ? $currentStep->title : '—';
            $statusMap = ['in_progress' => 'Active', 'pending' => 'Active', 'on_hold' => 'On Hold', 'completed' => 'Completed'];
            $status = $statusMap[$proj->status ?? 'in_progress'] ?? 'Active';
            if ($status === 'Active') $activeCount++;
            $dashboardProjects[] = [
                'id' => $proj->id,
                'project_uid' => $proj->project_uid ?? ('JM-' . str_pad((string) $proj->id, 3, '0', STR_PAD_LEFT)),
                'name' => $proj->name,
                'status' => $status,
                'progress' => $progress,
                'budget' => $budget,
                'spent' => $spent,
                'weeks' => $weeks,
                'weeksLeft' => $weeksLeft,
                'phase' => $phase,
                'workers' => Worker::where('project_id', $proj->id)->count(),
            ];
        }
        $pendingCerts = ProgressCertificate::whereIn('project_id', $projectIds)->whereIn('status', ['draft', 'sent'])->get();
        $pendingInvoicesCount = $pendingCerts->count();
        $pendingInvoicesAmount = $pendingCerts->sum('amount');
        $alerts = [];
        $overdueCerts = ProgressCertificate::whereIn('project_id', $projectIds)->where('status', 'sent')->where('period_end', '<', now()->subDays(14))->with('project')->get();
        foreach ($overdueCerts as $c) {
            $days = (int) Carbon::parse($c->period_end)->diffInDays(now());
            $alerts[] = ['type' => 'danger', 'message' => ($c->project->project_uid ?? '?') . ' payment certificate overdue by ' . $days . ' days', 'time' => $c->updated_at->diffForHumans()];
        }
        foreach ($dashboardProjects as $p) {
            if ($p['budget'] > 0 && $p['spent'] >= $p['budget'] * 0.94 && $p['progress'] < 80) {
                $alerts[] = ['type' => 'warning', 'message' => $p['name'] . ' — budget ' . round($p['spent'] / $p['budget'] * 100) . '% consumed at ' . $p['progress'] . '% completion', 'time' => 'Recent'];
            }
        }
        $alerts = array_slice($alerts, 0, 5);
        $recentActivity = [];
        foreach (Material::whereIn('project_id', $projectIds)->with('project')->orderBy('created_at', 'desc')->take(3)->get() as $m) {
            $recentActivity[] = ['at' => $m->created_at, 'icon' => '📦', 'action' => 'Materials received', 'project' => $m->project->name ?? '—', 'user' => '—'];
        }
        foreach (Payment::whereIn('project_id', $projectIds)->with(['project', 'worker'])->orderBy('created_at', 'desc')->take(3)->get() as $p) {
            $recentActivity[] = ['at' => $p->created_at, 'icon' => '👷', 'action' => 'Labour logged', 'project' => $p->project->name ?? '—', 'user' => $p->worker->full_name ?? '—'];
        }
        foreach (ProgressCertificate::whereIn('project_id', $projectIds)->with('project')->orderBy('created_at', 'desc')->take(3)->get() as $c) {
            $recentActivity[] = ['at' => $c->created_at, 'icon' => '🧾', 'action' => 'Certificate #' . ($c->reference_number ?: $c->id) . ' raised', 'project' => $c->project->name ?? '—', 'user' => 'Admin'];
        }
        usort($recentActivity, fn($a, $b) => $b['at']->getTimestamp() - $a['at']->getTimestamp());
        $recentActivity = array_slice($recentActivity, 0, 6);
        foreach ($recentActivity as &$a) {
            $a['time'] = $a['at']->format('Y-m-d') === now()->format('Y-m-d') ? 'Today ' . $a['at']->format('H:i') : $a['at']->diffForHumans();
            unset($a['at']);
        }

        $totalWorkersAll = Worker::whereIn('project_id', $projectIds)->count();
        $totalMaterialExpensesAll = (float) Material::whereIn('project_id', $projectIds)->sum(DB::raw('COALESCE(unit_price,0) * COALESCE(quantity_purchased,0)'));
        $totalPaymentsAll = (float) Payment::whereIn('project_id', $projectIds)->sum('amount');
        $costBreakdown = [];
        if ($totalSpent > 0) {
            $matPct = (int) round($totalMaterialExpensesAll / $totalSpent * 100);
            $labPct = (int) round($totalPaymentsAll / $totalSpent * 100);
            $otherPct = max(0, 100 - $matPct - $labPct);
            $costBreakdown = [
                ['name' => 'Materials', 'value' => $matPct, 'color' => '#22c55e'],
                ['name' => 'Labour', 'value' => $labPct, 'color' => '#38bdf8'],
                ['name' => 'Other', 'value' => $otherPct, 'color' => '#f59e0b'],
            ];
            $costBreakdown = array_values(array_filter($costBreakdown, fn($x) => $x['value'] > 0));
        }
        if (empty($costBreakdown)) {
            $costBreakdown = [['name' => 'Materials', 'value' => 50, 'color' => '#22c55e'], ['name' => 'Labour', 'value' => 50, 'color' => '#38bdf8']];
        }
        $milestones = ProjectStep::whereIn('project_id', $projectIds)->where('is_completed', false)->with('project')->orderBy('position')->take(8)->get();

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
            'projectCreatedDate',
            'projectSteps',
            'totalProjectSteps',
            'completedProjectSteps',
            'projectCompletionPercent',
            'dashboardProjects',
            'alerts',
            'recentActivity',
            'totalBudget',
            'totalSpent',
            'activeCount',
            'pendingInvoicesCount',
            'pendingInvoicesAmount',
            'totalWorkersAll',
            'costBreakdown',
            'milestones'
        ));
    }

    public function showAdminUser(User $user)
    {
        $authUser = Auth::user();

        if (!$authUser || !$authUser->is_admin()) {
            abort(403);
        }

        if ($user->user_type !== 'user' || !is_null($user->parent_user_id)) {
            abort(404);
        }

        $ownedProjects = $user->ownedProjects()
            ->select('projects.id', 'projects.name', 'projects.project_uid', 'projects.project_duration', 'projects.created_at')
            ->get();

        $assignedProjects = $user->projects()
            ->select('projects.id', 'projects.name', 'projects.project_uid', 'projects.project_duration', 'projects.created_at')
            ->get();

        $projects = $ownedProjects
            ->merge($assignedProjects)
            ->unique('id')
            ->sortBy('name', SORT_NATURAL | SORT_FLAG_CASE)
            ->values();

        return view('dashboard_admin_user', [
            'listedUser' => $user,
            'projects' => $projects,
        ]);
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

        if ($request->expectsJson()) {
            $stats = $this->projectStepStats($projectId);

            return response()->json([
                'success' => true,
                'step' => [
                    'id' => $projectStep->id,
                    'is_completed' => (bool) $projectStep->is_completed,
                    'completed_at_human' => $projectStep->completed_at ? $projectStep->completed_at->diffForHumans() : null,
                ],
                'stats' => $stats,
            ]);
        }

        return redirect()->route('dashboard');
    }

    public function reorderProjectSteps(Request $request)
    {
        $user = Auth::user();
        $projectId = (int) ($user->project_id ?? 0);

        if (!$projectId) {
            abort(403);
        }

        $validated = $request->validate([
            'steps' => 'required|array|min:1',
            'steps.*' => [
                'integer',
                Rule::exists('project_steps', 'id')->where(fn ($query) => $query->where('project_id', $projectId)),
            ],
        ]);

        $orderedStepIds = collect($validated['steps'])
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values();

        $validStepIds = ProjectStep::where('project_id', $projectId)
            ->whereIn('id', $orderedStepIds)
            ->pluck('id')
            ->map(fn ($id) => (int) $id)
            ->values();

        if ($orderedStepIds->count() !== $validStepIds->count()) {
            abort(403);
        }

        DB::transaction(function () use ($orderedStepIds, $projectId) {
            foreach ($orderedStepIds as $index => $stepId) {
                ProjectStep::where('project_id', $projectId)
                    ->where('id', $stepId)
                    ->update(['position' => $index + 1]);
            }
        });

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'stats' => $this->projectStepStats($projectId),
            ]);
        }

        return redirect()->route('dashboard');
    }

    protected function projectStepStats(int $projectId): array
    {
        $totalProjectSteps = ProjectStep::where('project_id', $projectId)->count();
        $completedProjectSteps = ProjectStep::where('project_id', $projectId)
            ->where('is_completed', true)
            ->count();

        $projectCompletionPercent = $totalProjectSteps > 0
            ? (int) round(($completedProjectSteps / $totalProjectSteps) * 100)
            : 0;

        return [
            'totalProjectSteps' => $totalProjectSteps,
            'completedProjectSteps' => $completedProjectSteps,
            'projectCompletionPercent' => $projectCompletionPercent,
        ];
    }
}
