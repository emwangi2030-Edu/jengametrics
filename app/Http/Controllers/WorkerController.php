<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Worker;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;

class WorkerController extends Controller
{
    public function index()
    {
        // Get the project ID from the authenticated user
        $projectId = Auth::user()->project_id;

        // Retrieve workers only for the user's current project
        $workers = Worker::where('project_id', $projectId)->get();

        $project = Project::find($projectId);

        return view('workers.index', compact('workers', 'project'));
    }


    public function show($id)
    {
        $worker = Worker::findOrFail($id);
        return view('workers.show', compact('worker'));
    }

    public function create()
    {
        // Get the project ID from the authenticated user
        $projectId = Auth::user()->project_id;

        return view('workers.create', compact('projectId'));
    }


    public function store(Request $request)
    {
        // Validate the request
        $request->validate([
            'full_name' => 'required|string|max:255',
            'id_number' => 'required|integer',
            'job_category' => 'required|string',
            'work_type' => 'required|string',
            'phone' => 'required|string|max:15',
            'email' => 'nullable|email|max:255',
        ]);

        // Create and save the worker, using the project_id from the authenticated user
        $worker = new Worker;
        $worker->full_name = $request->input('full_name');
        $worker->id_number = $request->input('id_number');
        $worker->job_category = $request->input('job_category');
        $worker->work_type = $request->input('work_type');
        $worker->phone = $request->input('phone');
        $worker->email = $request->input('email');
        $worker->project_id = Auth::user()->project_id; // Set the project_id from the authenticated user
        $worker->save();

        return redirect()->route('workers.index')->with('success', 'Worker added successfully');
    }
    
}

