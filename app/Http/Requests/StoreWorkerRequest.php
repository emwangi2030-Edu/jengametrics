<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreWorkerRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $workType = $this->input('work_type');
        if ($workType === 'Contract') {
            $this->merge(['work_type' => 'Under Contract']);
        }
    }

    public function authorize(): bool
    {
        return (bool) $this->user()?->project_id;
    }

    public function rules(): array
    {
        return [
            'full_name' => 'required|string|max:255',
            'id_number' => 'required|integer',
            'job_category' => 'required|string',
            'work_type' => 'required|string|in:Under Contract,Casual',
            'phone' => 'required|string|max:15',
            'email' => 'nullable|email|max:255',
            'picture' => 'nullable|image|max:4096',
            'payment_amount' => 'nullable|numeric|min:0',
            'payment_frequency' => 'nullable|string|in:per day,per month,one-time payment',
            'mode_of_payment' => 'required|string',
            'bank_name' => ['required_if:mode_of_payment,Bank', 'nullable', 'string', 'max:255'],
            'bank_account' => ['required_if:mode_of_payment,Bank', 'nullable', 'string', 'max:255'],
        ];
    }
}
