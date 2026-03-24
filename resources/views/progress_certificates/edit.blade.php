@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="jm-page-header">
        <div>
            <h2 class="jm-page-title">{{ __('Edit Progress Certificate') }}</h2>
            <p class="jm-page-subtitle mb-0">{{ __('Project: :project · Period: :start - :end', ['project' => $project->name, 'start' => $progressCertificate->period_start->format('d M Y'), 'end' => $progressCertificate->period_end->format('d M Y')]) }}</p>
        </div>
    </div>

    <div class="card shadow-sm w-100 jm-form-wrap-sm">
        <div class="card-body p-4">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('progress_certificates.update', $progressCertificate) }}">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="period_start" class="form-label">Period start</label>
                    <input type="date" name="period_start" id="period_start" class="form-control" value="{{ old('period_start', $progressCertificate->period_start->format('Y-m-d')) }}" required>
                </div>

                <div class="mb-3">
                    <label for="period_end" class="form-label">Period end</label>
                    <input type="date" name="period_end" id="period_end" class="form-control" value="{{ old('period_end', $progressCertificate->period_end->format('Y-m-d')) }}" required>
                </div>

                <div class="mb-3">
                    <label for="reference_number" class="form-label">Reference number (optional)</label>
                    <input type="text" name="reference_number" id="reference_number" class="form-control" value="{{ old('reference_number', $progressCertificate->reference_number) }}" maxlength="64">
                </div>

                <div class="mb-3">
                    <label for="amount" class="form-label">Amount (KES)</label>
                    <input type="number" name="amount" id="amount" class="form-control" value="{{ old('amount', $progressCertificate->amount) }}" min="0" step="0.01" required>
                </div>

                <div class="mb-4">
                    <label for="notes" class="form-label">Notes (optional)</label>
                    <textarea name="notes" id="notes" class="form-control" rows="3" maxlength="2000">{{ old('notes', $progressCertificate->notes) }}</textarea>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        {{ __('Save Changes') }}
                    </button>
                    <a href="{{ route('progress_certificates.index') }}" class="btn btn-outline-secondary">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
