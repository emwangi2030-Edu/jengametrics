<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Worker;
use App\Support\ApiResponse;
use Illuminate\Http\Request;

class WorkerWriteController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'id_number' => ['required', 'integer'],
            'job_category' => ['required', 'string'],
            'work_type' => ['required', 'string'],
            'phone' => ['required', 'string', 'max:15'],
            'email' => ['nullable', 'email', 'max:255'],
            'payment_amount' => ['nullable', 'numeric', 'min:0'],
            'payment_frequency' => ['nullable', 'string', 'in:per day,per month,one-time payment'],
            'mode_of_payment' => ['required', 'string'],
            'bank_name' => ['nullable', 'string', 'max:255'],
            'bank_account' => ['nullable', 'string', 'max:255'],
        ]);

        if (($validated['work_type'] ?? '') === 'Contract') {
            $validated['work_type'] = 'Under Contract';
        }

        if (($validated['work_type'] ?? '') !== 'Under Contract' && ($validated['work_type'] ?? '') !== 'Casual') {
            return ApiResponse::error(
                code: 'VALIDATION_ERROR',
                message: 'Invalid work_type value.',
                status: 422,
                details: ['work_type' => ['Allowed values are Under Contract or Casual.']]
            );
        }

        if (($validated['mode_of_payment'] ?? '') !== 'Bank') {
            $validated['bank_name'] = null;
            $validated['bank_account'] = null;
        }

        $project = $request->attributes->get('active_project');
        $validated['project_id'] = (int) $project->id;

        $worker = Worker::create($validated);

        return ApiResponse::success([
            'id' => $worker->id,
            'full_name' => $worker->full_name,
            'project_id' => $worker->project_id,
        ], message: 'Worker created.', status: 201);
    }
}

