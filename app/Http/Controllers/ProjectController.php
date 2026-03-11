<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Http\JsonResponse;

class ProjectController extends Controller
{
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
