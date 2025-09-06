<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Worker;

class PaymentController extends Controller
{
    public function index($workerId)
    {
        $worker = Worker::findOrFail($workerId);

        // Get all payments for this worker, most recent first
        $payments = $worker->payments()
            ->orderBy('payment_date', 'desc')
            ->get();

        return view('payments.index', compact('worker', 'payments'));
    }

    public function store(Request $request, Worker $worker)
    {
        $amount = $request->input('amount');

        if ($amount > 0) {
            $worker->payments()->create([
                'amount' => $amount,
                'payment_date' => now(),
                'period_start' => now()->startOfMonth()->toDateString(),
                'period_end' => now()->endOfMonth()->toDateString(),
                'project_id' => $worker->project_id,
            ]);
        }

        return back()->with('success', 'Payment recorded successfully!');
    }
}
