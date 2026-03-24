<?php

namespace App\Services;

use App\Models\Material;
use App\Models\Payment;
use App\Support\DateRangeQueries;
use Illuminate\Support\Facades\DB;

class DashboardChartService
{
    /**
     * Chart data for dashboard: material expenses and labour expenses by month, plus available years.
     */
    public static function chartDataForProject(int $projectId, int $selectedYear): array
    {
        $dateCol = 'created_at';
        $rawExpenses = Material::select(
            DB::raw(DateRangeQueries::monthColumn($dateCol) . ' as month_num'),
            DB::raw(DateRangeQueries::monthNameColumn($dateCol) . ' as month'),
            DB::raw('SUM(quantity_purchased * unit_price) as total')
        )
            ->where('project_id', $projectId)
            ->whereYear('created_at', $selectedYear)
            ->groupByRaw(DateRangeQueries::groupByMonthAndName($dateCol))
            ->orderBy('month_num')
            ->get()
            ->keyBy('month_num');

        $paymentDateCol = 'COALESCE(payment_date, created_at)';
        $rawLabourExpenses = Payment::selectRaw(DateRangeQueries::monthColumn($paymentDateCol) . ' as month_num')
            ->selectRaw(DateRangeQueries::monthNameColumn($paymentDateCol) . ' as month')
            ->selectRaw('SUM(amount) as total')
            ->where('project_id', $projectId)
            ->whereYear(DB::raw($paymentDateCol), $selectedYear)
            ->groupByRaw(DateRangeQueries::groupByMonthAndName($paymentDateCol))
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

        $materialYears = Material::where('project_id', $projectId)
            ->select(DB::raw(DateRangeQueries::yearColumn('created_at') . ' as year'))
            ->distinct()
            ->pluck('year');

        $paymentYears = Payment::where('project_id', $projectId)
            ->select(DB::raw(DateRangeQueries::yearColumn('COALESCE(payment_date, created_at)') . ' as year'))
            ->distinct()
            ->pluck('year');

        $availableYears = $materialYears
            ->merge($paymentYears)
            ->map(fn ($year) => $year ? (int) $year : null)
            ->filter()
            ->unique()
            ->sort()
            ->values();

        return [
            'labels' => $labels,
            'data' => $data,
            'labourData' => $labourData,
            'availableYears' => $availableYears,
        ];
    }
}
