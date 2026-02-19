<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\School;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Http\JsonResponse;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::all();
        return view('projects.index', compact('projects'));
    }

    public function create()
    {
        $projects = Project::all(); // Fetch projects if needed for some reason in the create view
        // $schools = School::all();   // Fetch schools for dropdown or selection
        // $subjects = Subject::all(); // Fetch subjects if needed in the create view
        return view('projects.create', compact('projects', 'schools', 'subjects'));
    }

    public function store(Request $request)
    {
        if (Auth::check() && Auth::user()->isSubAccount()) {
            return redirect()->route('projects.index')->with('warning', 'Sub-accounts cannot create projects.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'school_id' => 'required|exists:schools,id',
        ]);

        $project = Project::create([
            'name' => $request->name,
            'school_id' => $request->school_id,
            'user_id' => Auth::id(),
        ]);

        if ($project) {
            $project->users()->syncWithoutDetaching([Auth::id()]);
            $subAccountIds = User::where('parent_user_id', Auth::id())->pluck('id');
            if ($subAccountIds->isNotEmpty()) {
                $project->users()->syncWithoutDetaching($subAccountIds->all());
            }
        }

        return redirect()->route('projects.index')->with('success', 'Project created successfully.');
    }

    public function edit(Project $project)
    {
        // $project = Project::all();
        return view('projects.edit', compact('project'));
    }
    
    public function update(Request $request, Project $project)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'school_id' => 'required|exists:schools,id',
        ]);

        $project->update([
            'name' => $request->name,
            'school_id' => $request->school_id,
        ]);

        return redirect()->route('projects.index')->with('success', 'Project updated successfully.');
    }

    public function destroy(Project $project)
    {
        if (Auth::check() && Auth::user()->isSubAccount()) {
            return redirect()->route('projects.settings')->with('warning', 'Sub-accounts cannot delete projects.');
        }

        $project->delete();
        return redirect()->route('projects.index')->with('success', 'Project deleted successfully.');
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
