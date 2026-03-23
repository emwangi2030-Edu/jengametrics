<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Worker;
use App\Support\ApiResponse;
use Illuminate\Http\Request;

class PaymentWriteController extends Controller
{
    public function store(Request $request, Worker $worker)
    {
        $validated = $request->validate([
            'amount' => ['required', 'numeric', 'min:0.01'],
        ]);

        $project = $request->attributes->get('active_project');
        if ((int) $worker->project_id !== (int) $project->id) {
            return ApiResponse::error(
                code: 'PROJECT_FORBIDDEN',
                message: 'Worker does not belong to the active project.',
                status: 403
            );
        }

        $payment = $worker->payments()->create([
            'amount' => $validated['amount'],
            'payment_date' => now(),
            'period_start' => now()->startOfMonth()->toDateString(),
            'period_end' => now()->endOfMonth()->toDateString(),
            'project_id' => (int) $project->id,
        ]);

        return ApiResponse::success([
            'id' => $payment->id,
            'worker_id' => $worker->id,
            'amount' => (float) $payment->amount,
        ], message: 'Payment recorded.', status: 201);
    }
}

