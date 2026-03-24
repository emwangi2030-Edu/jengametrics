<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\BomItem;
use App\Models\BomLabour;
use App\Models\Material;
use App\Models\Payment;
use App\Support\ApiResponse;
use Illuminate\Http\Request;

class ReportingController extends Controller
{
    public function summary(Request $request)
    {
        $project = $request->attributes->get('active_project');
        $projectId = (int) $project->id;

        $totalEstimatedCost = (float) (BomItem::whereProjectId($projectId)
            ->selectRaw('SUM(quantity * rate) as total')
            ->value('total') ?? 0);
        $totalEstimatedLabour = (float) BomLabour::whereProjectId($projectId)->sum('amount');

        $materials = Material::whereProjectId($projectId)->get();
        $payments = Payment::whereProjectId($projectId)->get();

        $totalActualCost = (float) $materials->sum(fn ($m) => ((float) $m->unit_price * (float) $m->quantity_purchased));
        $totalActualPayments = (float) $payments->sum('amount');

        return ApiResponse::success([
            'estimated' => [
                'materials' => $totalEstimatedCost,
                'labour' => $totalEstimatedLabour,
            ],
            'actual' => [
                'materials' => $totalActualCost,
                'labour' => $totalActualPayments,
            ],
        ]);
    }

    public function wages(Request $request)
    {
        $project = $request->attributes->get('active_project');
        $query = Payment::with(['worker' => fn ($q) => $q->withTrashed()])
            ->whereProjectId((int) $project->id)
            ->orderByDesc('payment_date');

        if ($request->filled('year')) {
            $query->whereYear('payment_date', (int) $request->input('year'));
        }
        if ($request->filled('month')) {
            $query->whereMonth('payment_date', (int) $request->input('month'));
        }

        $items = $query->get()->map(function ($payment) {
            $worker = $payment->worker;
            return [
                'id' => $payment->id,
                'payee' => $worker->full_name ?? 'Unknown',
                'payment_date' => optional($payment->payment_date)->format('Y-m-d'),
                'amount' => (float) $payment->amount,
                'status' => ($worker && ($worker->terminated || $worker->trashed())) ? 'Terminated' : 'Active',
            ];
        })->values();

        return ApiResponse::success([
            'items' => $items,
            'count' => $items->count(),
        ]);
    }

    public function purchases(Request $request)
    {
        $project = $request->attributes->get('active_project');
        $query = Material::with('supplier')
            ->whereProjectId((int) $project->id)
            ->orderByDesc('created_at');

        if ($request->filled('year')) {
            $query->whereYear('created_at', (int) $request->input('year'));
        }
        if ($request->filled('month')) {
            $query->whereMonth('created_at', (int) $request->input('month'));
        }

        $items = $query->get()->map(function ($material) {
            return [
                'id' => $material->id,
                'material' => $material->name,
                'supplier' => optional($material->supplier)->name ?? 'N/A',
                'unit' => $material->unit_of_measure,
                'unit_price' => (float) ($material->unit_price ?? 0),
                'quantity' => (float) ($material->quantity_purchased ?? 0),
                'total_cost' => (float) ($material->unit_price ?? 0) * (float) ($material->quantity_purchased ?? 0),
                'date_purchased' => optional($material->created_at)->format('Y-m-d'),
            ];
        })->values();

        return ApiResponse::success([
            'items' => $items,
            'count' => $items->count(),
        ]);
    }
}

