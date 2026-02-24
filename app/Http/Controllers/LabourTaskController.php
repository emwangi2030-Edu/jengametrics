<?php

namespace App\Http\Controllers;

use App\Models\LabourTask;
use App\Models\Worker;
use App\Models\WorkerGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class LabourTaskController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();
        $this->authorizeLabourManagement($user);

        $projectId = (int) ($user->project_id ?? 0);
        if (!$projectId) {
            return redirect()->route('wizard')->with('warning', 'Select or create a project first.');
        }

        $workers = Worker::where('project_id', $projectId)
            ->where('terminated', false)
            ->whereNull('deleted_at')
            ->orderBy('full_name')
            ->get();

        $groups = WorkerGroup::with(['workers' => fn ($query) => $query->orderBy('full_name')])
            ->where('project_id', $projectId)
            ->orderBy('name')
            ->get();

        $pendingTasks = LabourTask::with(['worker', 'group.workers'])
            ->where('project_id', $projectId)
            ->where('is_completed', false)
            ->orderByRaw('due_date IS NULL')
            ->orderBy('due_date')
            ->latest()
            ->get();

        $completedTasks = LabourTask::with(['worker', 'group.workers'])
            ->where('project_id', $projectId)
            ->where('is_completed', true)
            ->orderByDesc('completed_at')
            ->latest()
            ->get();

        return view('labour_tasks.index', compact(
            'workers',
            'groups',
            'pendingTasks',
            'completedTasks'
        ));
    }

    public function showGroup(WorkerGroup $group)
    {
        $user = Auth::user();
        $this->authorizeLabourManagement($user);

        if ((int) $group->project_id !== (int) $user->project_id) {
            abort(403);
        }

        $group->load(['workers' => function ($query) {
            $query->orderBy('full_name');
        }]);

        return view('labour_tasks.group_show', compact('group'));
    }

    public function storeGroup(Request $request)
    {
        $user = Auth::user();
        $this->authorizeLabourManagement($user);

        $projectId = (int) ($user->project_id ?? 0);

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('worker_groups', 'name')->where(fn ($query) => $query->where('project_id', $projectId)),
            ],
            'worker_ids' => 'required|array|min:1',
            'worker_ids.*' => [
                'integer',
                Rule::exists('workers', 'id')->where(fn ($query) => $query->where('project_id', $projectId)),
            ],
        ]);

        $group = WorkerGroup::create([
            'project_id' => $projectId,
            'created_by' => $user->id,
            'name' => trim($validated['name']),
        ]);

        $group->workers()->sync(collect($validated['worker_ids'])->map(fn ($id) => (int) $id)->unique()->all());

        return redirect()->route('labour_tasks.index')->with('success', 'Worker group created successfully.');
    }

    public function storeTask(Request $request)
    {
        $user = Auth::user();
        $this->authorizeLabourManagement($user);

        $projectId = (int) ($user->project_id ?? 0);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'assignee_type' => ['required', Rule::in(['group', 'worker'])],
            'worker_group_id' => [
                'nullable',
                Rule::requiredIf(fn () => $request->input('assignee_type') === 'group'),
                'integer',
                Rule::exists('worker_groups', 'id')->where(fn ($query) => $query->where('project_id', $projectId)),
            ],
            'worker_id' => [
                'nullable',
                Rule::requiredIf(fn () => $request->input('assignee_type') === 'worker'),
                'integer',
                Rule::exists('workers', 'id')->where(fn ($query) => $query->where('project_id', $projectId)),
            ],
            'due_date' => 'nullable|date',
        ]);

        LabourTask::create([
            'project_id' => $projectId,
            'created_by' => $user->id,
            'title' => trim($validated['title']),
            'description' => $validated['description'] ?? null,
            'assignee_type' => $validated['assignee_type'],
            'worker_group_id' => $validated['assignee_type'] === 'group' ? (int) $validated['worker_group_id'] : null,
            'worker_id' => $validated['assignee_type'] === 'worker' ? (int) $validated['worker_id'] : null,
            'due_date' => $validated['due_date'] ?? null,
        ]);

        return redirect()->route('labour_tasks.index')->with('success', 'Task created successfully.');
    }

    public function completeTask(LabourTask $task)
    {
        $user = Auth::user();
        $this->authorizeLabourManagement($user);

        if ((int) $task->project_id !== (int) $user->project_id) {
            abort(403);
        }

        $task->update([
            'is_completed' => true,
            'completed_at' => now(),
        ]);

        if (request()->expectsJson()) {
            $task->load(['worker', 'group.workers']);

            $assigneeLabel = $task->assignee_type === 'group'
                ? optional($task->group)->name
                : optional($task->worker)->full_name;

            $pendingCount = LabourTask::where('project_id', (int) $user->project_id)
                ->where('is_completed', false)
                ->count();

            $completedCount = LabourTask::where('project_id', (int) $user->project_id)
                ->where('is_completed', true)
                ->count();

            return response()->json([
                'success' => true,
                'task' => [
                    'id' => $task->id,
                    'title' => $task->title,
                    'assignee_label' => $assigneeLabel ?: 'N/A',
                    'completed_human' => optional($task->completed_at)->diffForHumans() ?: 'just now',
                ],
                'counts' => [
                    'pending' => $pendingCount,
                    'completed' => $completedCount,
                ],
            ]);
        }

        return redirect()->route('labour_tasks.index')->with('success', 'Task marked as completed.');
    }

    protected function authorizeLabourManagement($user): void
    {
        if ($user && $user->isSubAccount() && ! $user->can_manage_labour) {
            abort(403);
        }
    }
}
