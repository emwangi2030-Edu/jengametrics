<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Worker;
use App\Models\Attendance;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function create()
    {
        $projectId = Auth::user()->project_id;
        $workers = Worker::where('project_id', $projectId)->get();
        return view('attendance.create', compact('workers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'present' => 'array'
        ]);

        $date = $request->input('date');
        $presentIds = $request->input('present', []);

        foreach ($request->input('worker_ids') as $workerId) {
            Attendance::updateOrCreate(
                ['worker_id' => $workerId, 'date' => $date],
                ['present' => in_array($workerId, $presentIds)]
            );
        }

        return redirect()->route('attendance.create')->with('success', 'Attendance saved successfully.');
    }
}
