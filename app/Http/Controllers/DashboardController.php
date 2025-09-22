<?php

namespace App\Http\Controllers;

use App\Models\Worker;
use App\Models\Material;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        if (!$user->has_project) {
            return redirect()->route('wizard.step1');
        }

        $projectId = $user->project_id;
        $totalWorkers = Worker::where('project_id', $projectId)->count();
        $totalMaterialExpenses = Material::where('project_id', $projectId)
                                        ->sum(DB::raw('unit_price * quantity_purchased'));
        $totalPayments = Payment::where('project_id', $projectId)
                                        ->sum('amount');

        // Get selected year or default to current year
        $selectedYear = $request->input('year', now()->year);

        $rawExpenses = Material::select(
            DB::raw("MONTH(created_at) as month_num"),
            DB::raw("DATE_FORMAT(created_at, '%b') as month"),
            DB::raw("SUM(quantity_purchased * unit_price) as total")
        )
        ->where('project_id', $projectId)
        ->whereYear('created_at', $selectedYear)
        ->groupBy('month_num', 'month')
        ->orderBy('month_num')
        ->get()
        ->keyBy('month_num');

        $rawLabourExpenses = Payment::selectRaw("MONTH(COALESCE(payment_date, created_at)) as month_num")
            ->selectRaw("DATE_FORMAT(COALESCE(payment_date, created_at), '%b') as month")
            ->selectRaw("SUM(amount) as total")
            ->where('project_id', $projectId)
            ->whereYear(DB::raw('COALESCE(payment_date, created_at)'), $selectedYear)
            ->groupByRaw("MONTH(COALESCE(payment_date, created_at)), DATE_FORMAT(COALESCE(payment_date, created_at), '%b')")
            ->orderBy('month_num')
            ->get()
            ->keyBy('month_num');

        $allMonths = collect([
            1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr',
            5 => 'May', 6 => 'Jun', 7 => 'Jul', 8 => 'Aug',
            9 => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Dec'
        ]);

        $labels = [];
        $data = [];
        $labourData = [];

        foreach ($allMonths as $monthNum => $monthName) {
            $labels[] = $monthName;
            $data[] = $rawExpenses->has($monthNum) ? (float) $rawExpenses[$monthNum]->total : 0;
            $labourData[] = $rawLabourExpenses->has($monthNum) ? (float) $rawLabourExpenses[$monthNum]->total : 0;
        }
        // Get all years for dropdown
        $materialYears = Material::where('project_id', $projectId)
            ->select(DB::raw('YEAR(created_at) as year'))
            ->distinct()
            ->pluck('year');

        $paymentYears = Payment::where('project_id', $projectId)
            ->select(DB::raw('YEAR(COALESCE(payment_date, created_at)) as year'))
            ->distinct()
            ->pluck('year');

        $availableYears = $materialYears
            ->merge($paymentYears)
            ->map(fn ($year) => $year ? (int) $year : null)
            ->filter()
            ->unique()
            ->sort()
            ->values();

        return view('dashboard', compact(
            'totalWorkers',
            'totalMaterialExpenses',
            'totalPayments',
            'labels',
            'data',
            'labourData',
            'availableYears',
            'selectedYear'
        ));
    }
}
