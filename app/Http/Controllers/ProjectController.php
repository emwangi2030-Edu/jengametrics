<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\School;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $request->validate([
            'name' => 'required|string|max:255',
            'school_id' => 'required|exists:schools,id',
        ]);

        Project::create([
            'name' => $request->name,
            'school_id' => $request->school_id,
        ]);

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
        $project->delete();
        return redirect()->route('projects.index')->with('success', 'Project deleted successfully.');
    }

    public function selectProject(Request $request)
    {
        $user = Auth::user();
        $user->project_id = $request->id;
        $user->save();
        return redirect()->route('dashboard')->with('success', 'Project selected successfully.');
    }

}
