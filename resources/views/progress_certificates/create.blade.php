@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="jm-page-header">
        <div>
            <h2 class="jm-page-title jm-ui-title">{{ __('New Progress Certificate') }}</h2>
            <p class="jm-page-subtitle jm-ui-muted mb-0">{{ __('Project: :name', ['name' => $project->name]) }}</p>
        </div>
    </div>

    <div class="card jm-ui-card shadow-sm border-0 w-100 jm-form-wrap-sm">
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

            <form method="POST" action="{{ route('progress_certificates.store') }}">
                @csrf

                <div class="mb-3">
                    <label for="period_start" class="form-label">Period start</label>
                    <input type="date" name="period_start" id="period_start" class="form-control" value="{{ old('period_start') }}" required>
                </div>

                <div class="mb-3">
                    <label for="period_end" class="form-label">Period end</label>
                    <input type="date" name="period_end" id="period_end" class="form-control" value="{{ old('period_end') }}" required>
                </div>

                <div class="mb-3">
                    <label for="reference_number" class="form-label">Reference number (optional)</label>
                    <input type="text" name="reference_number" id="reference_number" class="form-control" value="{{ old('reference_number') }}" maxlength="64">
                </div>

                <div class="mb-3">
                    <label for="amount" class="form-label">Amount (KES)</label>
                    <input type="number" name="amount" id="amount" class="form-control" value="{{ old('amount', '0') }}" min="0" step="0.01" required>
                </div>

                <div class="mb-4">
                    <label for="notes" class="form-label">Notes (optional)</label>
                    <textarea name="notes" id="notes" class="form-control" rows="3" maxlength="2000">{{ old('notes') }}</textarea>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        {{ __('Create Certificate') }}
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
