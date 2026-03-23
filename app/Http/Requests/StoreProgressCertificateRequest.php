<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProgressCertificateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user();
    }

    public function rules(): array
    {
        return [
            'period_start' => 'required|date',
            'period_end' => 'required|date|after_or_equal:period_start',
            'reference_number' => 'nullable|string|max:64',
            'amount' => 'required|numeric|min:0',
            'notes' => 'nullable|string|max:2000',
        ];
    }
}
