<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Worker;
use App\Models\Attendance;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function create(Request $request)
    {
        $projectId = Auth::user()->project_id;
        $date = $request->input('date') ?? $request->input('selected_date') ?? now()->toDateString();

        $workers = Worker::where('project_id', $projectId)->get();

        $existingAttendances = Attendance::whereDate('date', $date)->get()
            ->keyBy('worker_id');

        return view('attendance.create', compact('workers', 'date', 'existingAttendances'));
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
            if (in_array($workerId, $presentIds)) {
                // Mark as present (update or create)
                Attendance::updateOrCreate(
                    ['worker_id' => $workerId, 'date' => $date],
                    ['present' => true]
                );
            } else {
                // If unchecked, remove attendance record
                Attendance::where('worker_id', $workerId)
                        ->whereDate('date', $date)
                        ->delete();
            }
        }

        return redirect()->route('workers.index')->with('success', 'Attendance saved successfully.');
    }

    public function fetchAttendance(Request $request)
    {
        $projectId = Auth::user()->project_id;
        $date = $request->input('date') ?? now()->toDateString();

        $workers = Worker::where('project_id', $projectId)->get();
        $existingAttendances = Attendance::whereDate('date', $date)->get()
            ->keyBy('worker_id');

        // return only the table HTML
        return view('attendance.partials.table', compact('workers', 'date', 'existingAttendances'));
    }

}
