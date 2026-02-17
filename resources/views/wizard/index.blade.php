@extends('layouts.app')

@section('content')
<div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="col-md-9 col-lg-7">
        <div class="card shadow-lg">
            <div class="card-body">
                <div class="d-flex justify-content-end">
                    <a href="{{ route('dashboard') }}" class="btn btn-outline-danger btn-sm">X</a>
                </div>

                <div class="mb-4">
                    <h2 class="text-center mb-3">{{ __('Create Project') }}</h2>
                    <div class="progress" style="height: 8px;">
                        <div id="wizardProgressBar" class="progress-bar bg-success" role="progressbar" style="width: {{ $step === 2 ? '100%' : '50%' }};" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <div class="d-flex justify-content-between small text-muted mt-2">
                        <span id="wizardStepLabel1" class="{{ $step === 1 ? 'fw-bold text-success' : '' }}">Step 1</span>
                        <span id="wizardStepLabel2" class="{{ $step === 2 ? 'fw-bold text-success' : '' }}">Step 2</span>
                    </div>
                </div>

                <div id="wizardStepContent" data-step="{{ $step }}">
                    <div class="text-center text-muted py-4">{{ __('Loading...') }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script>
        (function () {
            const container = document.getElementById('wizardStepContent');
            const progressBar = document.getElementById('wizardProgressBar');
            const stepLabel1 = document.getElementById('wizardStepLabel1');
            const stepLabel2 = document.getElementById('wizardStepLabel2');

            if (!container) {
                return;
            }

            const currentStep = container.dataset.step === '2' ? 2 : 1;
            const url = currentStep === 2
                ? "{{ route('wizard.step2.fragment') }}"
                : "{{ route('wizard.step1.fragment') }}";

            fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                .then(response => response.text())
                .then(html => {
                    container.innerHTML = html;

                    const isStep2 = currentStep === 2;
                    progressBar.style.width = isStep2 ? '100%' : '50%';
                    stepLabel1.classList.toggle('fw-bold', !isStep2);
                    stepLabel1.classList.toggle('text-success', !isStep2);
                    stepLabel2.classList.toggle('fw-bold', isStep2);
                    stepLabel2.classList.toggle('text-success', isStep2);
                })
                .catch(() => {
                    container.innerHTML = '<div class="text-danger text-center py-3">Failed to load step content.</div>';
                });
        })();
    </script>
@endpush
