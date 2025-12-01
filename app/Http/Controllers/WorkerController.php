<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Worker;
use App\Models\Project;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class WorkerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();
        $projectId = $user?->project_id;

        if (!$projectId) {
            return redirect()
                ->route('wizard.step1')
                ->with('warning', 'Select or create a project before managing workers.');
        }

        // Retrieve workers (including archived) only for the user's current project
        $workers = Worker::withTrashed()
            ->withCount('attendances')
            ->where('project_id', $projectId)
            ->where('terminated', false)
            ->get()
            ->map(function (Worker $worker) {
                $worker->amount_owed = $this->calculateAmountOwed($worker);
                return $worker;
            });

        $project = Project::find($projectId);

        return view('workers.index', compact('workers', 'project'));
    }


    public function show(Request $request, $id)
    {
        $worker = Worker::withTrashed()->findOrFail($id);

        // Selected or current month/year
        $month = $request->input('month', now()->month);
        $year = $request->input('year', now()->year);

        // Get attendance records for selected month/year
        $attendances = Attendance::where('worker_id', $worker->id)
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->orderBy('date')
            ->get()
            ->keyBy(fn($att) => Carbon::parse($att->date)->format('d-m-Y'));

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


        // Recompute amount owed scoped to selected month/year
        try {
            $periodStart = Carbon::createFromDate($year, $month, 1)->startOfDay();
            $periodEnd = (clone $periodStart)->endOfMonth();
            $attendanceCount = $attendances->where('present', true)->count();
            $totalOwedPeriod = 0.0;
            if ($worker->payment_frequency === 'per day') {
                $totalOwedPeriod = $attendanceCount * (float) $worker->payment_amount;
            } elseif ($worker->payment_frequency === 'per month') {
                $activeStart = $worker->created_at->gt($periodStart) ? $worker->created_at->copy()->startOfDay() : $periodStart;
                $activeDays = max(0, $activeStart->diffInDays($periodEnd->copy()->addDay()));
                $daysInMonth = $periodStart->daysInMonth;
                $prorataFactor = $daysInMonth > 0 ? ($activeDays / $daysInMonth) : 0;
                $totalOwedPeriod = (float) $worker->payment_amount * $prorataFactor;
            } elseif ($worker->payment_frequency === 'one-time payment') {
                $hasAnyPayment = $worker->payments()->exists();
                $totalOwedPeriod = $hasAnyPayment ? 0.0 : (float) $worker->payment_amount;
            }
            if ($worker->payment_frequency === 'one-time payment') {
                $alreadyPaidPeriod = (float) $worker->payments()->sum('amount');
            } else {
                $alreadyPaidPeriod = (float) $worker->payments()
                    ->whereBetween('payment_date', [$periodStart, $periodEnd])
                    ->sum('amount');
            }
            $amountOwed = max($totalOwedPeriod - $alreadyPaidPeriod, 0.0);
        } catch (\Throwable $e) {
            // fallback to previous amountOwed if anything goes wrong
        }

        return view('workers.show', compact(
            'worker',
            'month',
            'year',
            'availableMonths',
            'availableYears',
            'amountOwed'
        ));
    }

    public function create()
    {
        $user = Auth::user();
        $projectId = $user?->project_id;

        if (!$projectId) {
            return redirect()
                ->route('wizard.step1')
                ->with('warning', 'Select or create a project before adding workers.');
        }

        return view('workers.create', compact('projectId'));
    }


    public function store(Request $request)
    {
        $user = Auth::user();
        $projectId = $user?->project_id;

        if (!$projectId) {
            return redirect()
                ->route('wizard.step1')
                ->with('warning', 'Select or create a project before adding workers.');
        }

        $validated = $request->validate([
            'full_name'         => 'required|string|max:255',
            'id_number'         => 'required|integer',
            'job_category'      => 'required|string',
            'work_type'         => 'required|string',
            'phone'             => 'required|string|max:15',
            'email'             => 'nullable|email|max:255',
            'picture'           => 'nullable|image|max:4096',
            'payment_amount'    => 'nullable|numeric|min:0',
            'payment_frequency' => 'nullable|string|in:per day,per month,one-time payment',
            'mode_of_payment'   => 'required|string',
            'bank_name'         => ['required_if:mode_of_payment,Bank', 'nullable', 'string', 'max:255'],
            'bank_account'      => ['required_if:mode_of_payment,Bank', 'nullable', 'string', 'max:255'],
        ]);

        if (($validated['mode_of_payment'] ?? '') !== 'Bank') {
            $validated['bank_name'] = null;
            $validated['bank_account'] = null;
        }
        if ($request->hasFile('picture')) {
            $path = $request->file('picture')->store('workers', 'public');
            $validated['picture'] = $path;
        }

        $validated['project_id'] = $projectId;

        Worker::create($validated);

        return redirect()
            ->route('workers.index')
            ->with('success', 'Worker added successfully');
    }

    public function edit($id)
    {
        $worker = Worker::findOrFail($id);

        return view('workers.edit', compact('worker'));
    }

    public function destroy($id)
    {
        $worker = Worker::withTrashed()->findOrFail($id);

        if (! $worker->trashed()) {
            $worker->delete();

            return redirect()
                ->route('workers.index')
                ->with('success', 'Worker archived. Attendance and payment records are retained.');
        }

        // Second delete: mark as terminated but keep for historical attendance display
        $worker->terminated = true;
        $worker->terminated_at = now();
        $worker->save();

        return redirect()
            ->route('workers.index')
            ->with('success', 'Worker removed after clearing debts. Attendance history retained as terminated.');
    }

    public function update(Request $request, $id)
    {
        $worker = Worker::findOrFail($id);

        $validated = $request->validate([
            'full_name'         => 'required|string|max:255',
            'id_number'         => 'required|integer',
            'job_category'      => 'required|string',
            'work_type'         => 'required|string',
            'phone'             => 'required|string|max:15',
            'email'             => 'nullable|email|max:255',
            'picture'           => 'nullable|image|max:4096',
            'payment_amount'    => 'nullable|numeric|min:0',
            'payment_frequency' => 'nullable|string|in:per day,per month,one-time payment',
            'mode_of_payment'   => 'required|string',
            'bank_name'         => ['required_if:mode_of_payment,Bank', 'nullable', 'string', 'max:255'],
            'bank_account'      => ['required_if:mode_of_payment,Bank', 'nullable', 'string', 'max:255'],
        ]);

        if (($validated['mode_of_payment'] ?? '') !== 'Bank') {
            $validated['bank_name'] = null;
            $validated['bank_account'] = null;
        }

        if ($request->hasFile('picture')) {
            $existingPicture = $worker->picture;

            if ($existingPicture && !Str::startsWith($existingPicture, ['http://', 'https://'])) {
                if (Str::startsWith($existingPicture, ['storage/', '/storage/'])) {
                    $previousPath = Str::after($existingPicture, 'storage/');
                } else {
                    $previousPath = ltrim($existingPicture, '/');
                }

                if (!empty($previousPath)) {
                    Storage::disk('public')->delete($previousPath);
                }
            }

            $path = $request->file('picture')->store('workers', 'public');
            $validated['picture'] = $path;
        }

        $worker->update($validated);

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
            ->keyBy(fn($att) => Carbon::parse($att->date)->format('d-m-Y'));

        $labels = [];
        $values = [];
        $backgroundColors = [];
        $borderColors = [];
        $statuses = [];

        $daysInMonth = Carbon::createFromDate($year, $month, 1)->daysInMonth;
        $today = Carbon::today();
        $workerCreatedAt = $worker->created_at->startOfDay();

        $colors = [
            'present' => ['bg' => 'rgba(40, 167, 69, 0.6)', 'border' => 'rgba(40, 167, 69, 1)'],
            'absent' => ['bg' => 'rgba(220, 53, 69, 0.6)', 'border' => 'rgba(220, 53, 69, 1)'],
            'weekend' => ['bg' => 'rgba(173, 216, 230, 0.8)', 'border' => 'rgba(100, 149, 237, 0.9)'],
            'inactive' => ['bg' => 'rgba(200, 200, 200, 0.5)', 'border' => 'rgba(200, 200, 200, 0.8)'],
        ];

        for ($day = 1; $day <= $daysInMonth; $day++) {
            $date = Carbon::createFromDate($year, $month, $day);
            $formattedDate = $date->format('d-m-Y');
            $labels[] = $date->format('M d');
            $values[] = 1;

            if ($date->lt($workerCreatedAt) || $date->gt($today)) {
                $backgroundColors[] = $colors['inactive']['bg'];
                $borderColors[] = $colors['inactive']['border'];
                $statuses[] = 'Inactive';
                continue;
            }

            $isPresent = $attendances->has($formattedDate) && (bool) $attendances[$formattedDate]->present;
            $isWeekend = $date->isWeekend();

            if ($isPresent) {
                $backgroundColors[] = $colors['present']['bg'];
                $borderColors[] = $colors['present']['border'];
                $statuses[] = 'Present';
                continue;
            }

            if ($isWeekend) {
                $backgroundColors[] = $colors['weekend']['bg'];
                $borderColors[] = $colors['weekend']['border'];
                $statuses[] = 'Weekend';
                continue;
            }

            $backgroundColors[] = $colors['absent']['bg'];
            $borderColors[] = $colors['absent']['border'];
            $statuses[] = 'Absent';
        }

        return response()->json([
            'labels' => $labels,
            'values' => $values,
            'backgroundColors' => $backgroundColors,
            'borderColors' => $borderColors,
            'statuses' => $statuses,
            'title' => "Attendance for " . Carbon::create()->month((int) $month)->format('F') . " $year"
        ]);
    }

    protected function calculateAmountOwed(Worker $worker): float
    {
        $paymentsTotal = (float) $worker->payments()->sum('amount');

        $frequency = $worker->payment_frequency;
        $rate = (float) ($worker->payment_amount ?? 0);
        $earned = 0.0;

        if ($frequency === 'per day') {
            $daysPresent = $worker->attendances()->where('present', true)->count();
            $earned = $daysPresent * $rate;
        } elseif ($frequency === 'per month') {
            $start = $worker->created_at ? $worker->created_at->copy()->startOfDay() : now()->startOfDay();
            $today = now()->startOfDay();
            $daysWorked = max(0, $start->diffInDays($today) + 1);
            $months = $daysWorked / 30;
            $earned = $months * $rate;
        } elseif ($frequency === 'one-time payment') {
            $earned = $rate;
        }

        return round(max(0.0, $earned - $paymentsTotal), 2);
    }
}
