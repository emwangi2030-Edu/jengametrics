<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Worker;
use App\Models\Project; 

class WorkerController extends Controller
{
    public function index(Request $request)
    {
        // Get the project ID
        $projectId = $request->session()->get('selected_project_id'); 
        
        // Retrieve workers only for the selected project
        $workers = Worker::where('project_id', $projectId)->get();
        
        return view('workers.index', compact('workers'));
    }

    public function show($id)
    {
        $worker = Worker::findOrFail($id);
        return view('workers.show', compact('worker'));
    }

    public function create(Request $request)
    {
        // Check if project_id is in session
        $projectId = $request->session()->get('selected_project_id');
        
        if (!$projectId) {
            return redirect()->route('projects.index')->with('error', 'Please select a project first.');
        }

        return view('workers.create', compact('projectId'));
    }


    public function store(Request $request)
    {
        // Validate the request except for project_id
        $request->validate([
            'full_name' => 'required|string|max:255',
            'id_number' => 'required|integer',
            'job_category' => 'required|string',
            'work_type' => 'required|string',
            'phone' => 'required|string|max:15',
            'email' => 'nullable|email|max:255',
        ]);

        // Retrieve project_id from session
        $projectId = $request->session()->get('selected_project_id');
        
        // Create and save the worker with the project ID from session
        $worker = new Worker;
        $worker->full_name = $request->input('full_name');
        $worker->id_number = $request->input('id_number');
        $worker->job_category = $request->input('job_category');
        $worker->work_type = $request->input('work_type');
        $worker->phone = $request->input('phone');
        $worker->email = $request->input('email');
        $worker->project_id = $projectId;  // Set project ID from session
        $worker->save();

        return redirect()->route('workers.index')->with('success', 'Worker added successfully');
    }

}
