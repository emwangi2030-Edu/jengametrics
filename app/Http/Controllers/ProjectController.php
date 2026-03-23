<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Http\JsonResponse;

class ProjectController extends Controller
{
    /**
     * Create project from the React dashboard modal (JSON).
     */
    public function store(Request $request): JsonResponse
    {
        $user = Auth::user();
        if (! $user || $user->isSubAccount() || $user->is_admin()) {
            return response()->json([
                'message' => 'You are not allowed to create projects.',
            ], 403);
        }

        $validated = $request->validate([
            'project_uid' => 'required|string|max:100|alpha_dash|unique:projects,project_uid',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'project_duration' => 'required|integer|min:1',
            'address' => 'required|string|max:255',
            'budget' => 'required|string|max:255',
            'project_type' => 'nullable|string|max:100',
            'priority' => 'nullable|string|max:50',
        ]);

        $description = $validated['description'];
        if (! empty($validated['project_type'])) {
            $description .= "\n\nType: ".$validated['project_type'];
        }
        if (! empty($validated['priority'])) {
            $description .= "\nPriority: ".$validated['priority'];
        }

        $project = Project::create([
            'project_uid' => $validated['project_uid'],
            'name' => $validated['name'],
            'user_id' => $user->id,
            'address' => $validated['address'],
            'description' => $description,
            'project_duration' => $validated['project_duration'],
            'budget' => $validated['budget'],
        ]);

        $user->has_project = 1;
        $user->project_id = $project->id;
        $user->save();

        $project->users()->syncWithoutDetaching([$user->id]);
        $subAccountIds = User::where('parent_user_id', $user->id)->pluck('id');
        if ($subAccountIds->isNotEmpty()) {
            $project->users()->syncWithoutDetaching($subAccountIds->all());
        }

        return response()->json([
            'data' => [
                'id' => $project->id,
                'name' => $project->name,
                'project_uid' => $project->project_uid,
            ],
        ], 201);
    }

    public function destroy(Project $project)
    {
        if (Auth::check() && Auth::user()->isSubAccount()) {
            return redirect()->route('projects.settings')->with('warning', 'Sub-accounts cannot delete projects.');
        }

        $project->delete();
        return redirect()->route('dashboard')->with('success', 'Project deleted successfully.');
    }

    public function selectProject(Request $request)
    {
        $user = Auth::user();
        $projectId = (int) $request->id;

        if (! $user->projects()->where('projects.id', $projectId)->exists()) {
            return redirect()->route('dashboard')->with('warning', 'You do not have access to that project.');
        }

        $user->project_id = $projectId;
        $user->save();

        return redirect()->route('dashboard')->with('success', 'Project selected successfully.');
    }

    public function settings()
    {
        $project = get_project();
        if (! $project) {
            return redirect()->route('dashboard')->with('warning', 'No project selected.');
        }

        return view('projects.settings', compact('project'));
    }

    public function updateSettings(Request $request)
    {
        $project = get_project();
        if (! $project) {
            return redirect()->route('dashboard')->with('warning', 'No project selected.');
        }

        $validated = $request->validate([
            'project_uid' => [
                'required',
                'string',
                'max:100',
                'alpha_dash',
                Rule::unique('projects', 'project_uid')->ignore($project->id),
            ],
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'project_duration' => 'nullable|integer|min:1',
            'budget' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
        ]);

        $project->update($validated);

        return redirect()->route('projects.settings')->with('success', 'Project settings updated successfully.');
    }

    public function checkProjectUid(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'project_uid' => 'required|string|max:100',
            'ignore_project_id' => 'nullable|integer',
        ]);

        $query = Project::query()->where('project_uid', $validated['project_uid']);

        if (!empty($validated['ignore_project_id'])) {
            $query->where('id', '!=', (int) $validated['ignore_project_id']);
        }

        return response()->json([
            'exists' => $query->exists(),
        ]);
    }

}
