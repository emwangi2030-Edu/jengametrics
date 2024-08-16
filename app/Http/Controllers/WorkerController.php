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
}
