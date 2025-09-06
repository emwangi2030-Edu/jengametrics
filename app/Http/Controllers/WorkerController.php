<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Worker;
use App\Models\Project;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class WorkerController extends Controller
{
    public function index()
    {
        // Get the project ID from the authenticated user
        $projectId = Auth::user()->project_id;

        // Retrieve workers only for the user's current project
         $workers = Worker::withCount('attendances')
            ->where('project_id', $projectId)
            ->get();

        $project = Project::find($projectId);

        return view('workers.index', compact('workers', 'project'));
    }


    public function show(Request $request, $id)
    {
        $worker = Worker::findOrFail($id);

        // Selected or current month/year
        $month = $request->input('month', now()->month);
        $year = $request->input('year', now()->year);

        // Get attendance records for selected month/year
        $attendances = Attendance::where('worker_id', $worker->id)
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->orderBy('date')
            ->get()
            ->keyBy(fn($att) => Carbon::parse($att->date)->format('Y-m-d'));

        $labels = [];
        $presentData = [];
        $absentData = [];
        $inactiveData = [];

        $daysInMonth = Carbon::createFromDate($year, $month, 1)->daysInMonth;
        $today = Carbon::today();
        $workerCreatedAt = $worker->created_at->startOfDay();

        for ($day = 1; $day <= $daysInMonth; $day++) {
            $date = Carbon::createFromDate($year, $month, $day);
            $formattedDate = $date->format('Y-m-d');
            $labels[] = $date->format('M d');

            // If before worker joined or after today, leave as inactive
            if ($date->lt($workerCreatedAt) || $date->gt($today)) {
                $presentData[] = 0;
                $absentData[] = 0;
                $inactiveData[] = 1;
                continue;
            }

            $isPresent = $attendances->has($formattedDate) && (bool) $attendances[$formattedDate]->present;
            $presentData[] = $isPresent ? 1 : 0;
            $absentData[] = $isPresent ? 0 : 1;
            $inactiveData[] = 0;
        }

        // Dropdown data
        $availableYears = Attendance::where('worker_id', $worker->id)
            ->selectRaw('YEAR(date) as year')->distinct()->pluck('year');

        $availableMonths = collect(range(1, 12))->mapWithKeys(function ($m) {
            return [$m => Carbon::create()->month($m)->format('F')];
        });

        // 💰 Calculate Amount Owed
        $attendanceCount = $attendances->where('present', true)->count();
        $totalOwed = 0;

        if ($worker->payment_frequency === 'per day') {
            $totalOwed = $attendanceCount * $worker->payment_amount;

        } elseif ($worker->payment_frequency === 'per month') {
            // Worker start date
            $startDate = $worker->created_at->startOfDay();
            $today = now()->startOfDay();

            // Total days worked since start
            $daysWorked = $startDate->diffInDays($today);

            // Count full 30-day periods as "months"
            $monthsCount = floor($daysWorked / 30);

            $totalOwed = $monthsCount * $worker->payment_amount;

        } elseif ($worker->payment_frequency === 'one-time payment') {
            $totalOwed = $worker->payment_amount;
        }

        // Subtract what has already been paid
        $alreadyPaid = $worker->payments()->sum('amount');
        $amountOwed = max($totalOwed - $alreadyPaid, 0);


        return view('workers.show', compact(
            'worker',
            'labels',
            'presentData',
            'absentData',
            'inactiveData',
            'month',
            'year',
            'availableMonths',
            'availableYears',
            'amountOwed'
        ));
    }

    public function create()
    {
        // Get the project ID from the authenticated user
        $projectId = Auth::user()->project_id;

        return view('workers.create', compact('projectId'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'full_name'         => 'required|string|max:255',
            'id_number'         => 'required|integer',
            'job_category'      => 'required|string',
            'work_type'         => 'required|string',
            'phone'             => 'required|string|max:15',
            'email'             => 'nullable|email|max:255',
            'payment_amount'    => 'nullable|numeric|min:0',
            'payment_frequency' => 'nullable|string|in:per day,per month,one-time payment',
            'mode_of_payment'   => 'required|string',
            'bank_name'         => 'required_if:mode_of_payment,Bank|string|max:255',
            'bank_account'      => 'required_if:mode_of_payment,Bank|string|max:255',
        ]);

        Worker::create([
            'full_name'         => $request->input('full_name'),
            'id_number'         => $request->input('id_number'),
            'job_category'      => $request->input('job_category'),
            'work_type'         => $request->input('work_type'),
            'phone'             => $request->input('phone'),
            'email'             => $request->input('email'),
            'payment_amount'    => $request->input('payment_amount'),
            'payment_frequency' => $request->input('payment_frequency'),
            'mode_of_payment'   => $request->input('mode_of_payment'),
            'bank_name'         => $request->input('bank_name'),
            'bank_account'      => $request->input('bank_account'),
            'project_id'        => Auth::user()->project_id,
        ]);

        return redirect()
            ->route('workers.index')
            ->with('success', 'Worker added successfully');
    }

    public function edit($id)
    {
        $worker = Worker::findOrFail($id);

        return view('workers.edit', compact('worker'));
    }

    public function update(Request $request, $id)
    {
        $worker = Worker::findOrFail($id);

        $request->validate([
            'full_name'         => 'required|string|max:255',
            'id_number'         => 'required|integer',
            'job_category'      => 'required|string',
            'work_type'         => 'required|string',
            'phone'             => 'required|string|max:15',
            'email'             => 'nullable|email|max:255',
            'payment_amount'    => 'nullable|numeric|min:0',
            'payment_frequency' => 'nullable|string|in:per day,per month,one-time payment',
            'mode_of_payment'   => 'required|string',
            'bank_name'         => 'required_if:mode_of_payment,Bank|string|max:255',
            'bank_account'      => 'required_if:mode_of_payment,Bank|string|max:255',
        ]);

        $worker->update([
            'full_name'         => $request->input('full_name'),
            'id_number'         => $request->input('id_number'),
            'job_category'      => $request->input('job_category'),
            'work_type'         => $request->input('work_type'),
            'phone'             => $request->input('phone'),
            'email'             => $request->input('email'),
            'payment_amount'    => $request->input('payment_amount'),
            'payment_frequency' => $request->input('payment_frequency'),
            'mode_of_payment'   => $request->input('mode_of_payment'),
            'bank_name'         => $request->input('bank_name'),
            'bank_account'      => $request->input('bank_account'),
        ]);

        return redirect()
            ->route('workers.index')
            ->with('success', 'Worker updated successfully.');
    }

    public function attendanceData(Request $request, $id)
    {
        $worker = Worker::findOrFail($id);

        $month = $request->input('month', now()->month);
        $year = $request->input('year', now()->year);

        $attendances = Attendance::where('worker_id', $worker->id)
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->orderBy('date')
            ->get()
            ->keyBy(fn($att) => Carbon::parse($att->date)->format('Y-m-d'));

        $labels = [];
        $presentData = [];
        $absentData = [];
        $inactiveData = [];
        $weekendIndexes = [];

        $daysInMonth = Carbon::createFromDate($year, $month, 1)->daysInMonth;
        $today = Carbon::today();
        $workerCreatedAt = $worker->created_at->startOfDay();

        for ($day = 1; $day <= $daysInMonth; $day++) {
            $date = Carbon::createFromDate($year, $month, $day);
            $formattedDate = $date->format('Y-m-d');
            $labels[] = $date->format('M d');

            if ($date->lt($workerCreatedAt) || $date->gt($today)) {
                $presentData[] = 0;
                $absentData[] = 0;
                $inactiveData[] = 1;
                continue;
            }

            $isPresent = $attendances->has($formattedDate) && (bool) $attendances[$formattedDate]->present;
            $presentData[] = $isPresent ? 1 : 0;
            $absentData[] = $isPresent ? 0 : 1;
            $inactiveData[] = 0;
        }

        return response()->json([
            'labels' => $labels,
            'presentData' => $presentData,
            'absentData' => $absentData,
            'inactiveData' => $inactiveData,
            'title' => "Attendance for " . Carbon::create()->month((int) $month)->format('F') . " $year"
        ]);
    }
}

