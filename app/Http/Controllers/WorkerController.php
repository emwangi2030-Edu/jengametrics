<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Worker;

class WorkerController extends Controller
{
    public function index()
    {
        $workers = Worker::all();
        return view('workers.index', compact('workers'));
    }

    public function show($id)
    {
        $worker = Worker::findOrFail($id);
        return view('workers.show', compact('worker'));
    }

    public function create()
    {
        return view('workers.create');
    }

    public function store(Request $request)
    {
        $worker = new Worker;
        $worker->full_name = $request->input('full_name');
        $worker->id_number = $request->input('id_number');
        $worker->job_category = $request->input('job_category');
        $worker->work_type = $request->input('work_type');
        $worker->phone = $request->input('phone');
        $worker->email = $request->input('email');
        $worker->save();

        return redirect()->route('workers.index')->with('success', 'Worker added successfully');
    }

}
