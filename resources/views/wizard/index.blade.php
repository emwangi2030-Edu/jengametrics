@extends('layouts.app')

@section('content')
<style>
    .wizard-steps {
        position: relative;
        margin: 0.5rem 0 0.25rem;
        padding-top: 0.25rem;
    }

    .wizard-steps-track {
        position: absolute;
        top: 22px;
        left: 18%;
        right: 18%;
        height: 4px;
        background: #e5e7eb;
        border-radius: 999px;
        overflow: hidden;
        z-index: 0;
    }

    .wizard-steps-fill {
        height: 100%;
        width: 100%;
        background: linear-gradient(90deg, #198754 0%, #20c997 100%);
        transform-origin: left center;
        transform: scaleX(0);
        transition: transform 0.45s ease;
    }

    .wizard-steps-items {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        position: relative;
        z-index: 1;
    }

    .wizard-step-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        width: 40%;
        color: #6c757d;
        transition: color 0.25s ease;
    }

    .wizard-step-icon {
        width: 44px;
        height: 44px;
        border-radius: 999px;
        border: 2px solid #d1d5db;
        background: #fff;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }

    .wizard-step-icon svg {
        width: 20px;
        height: 20px;
        stroke: #6c757d;
        transition: stroke 0.3s ease;
    }

    .wizard-step-label {
        margin-top: 0.5rem;
        font-size: 0.85rem;
        font-weight: 600;
        text-align: center;
    }

    .wizard-step-item.active,
    .wizard-step-item.completed {
        color: #198754;
    }

    .wizard-step-item.active .wizard-step-icon,
    .wizard-step-item.completed .wizard-step-icon {
        border-color: #198754;
        background: #e8f7ef;
        box-shadow: 0 0 0 4px rgba(25, 135, 84, 0.12);
    }

    .wizard-step-item.active .wizard-step-icon svg,
    .wizard-step-item.completed .wizard-step-icon svg {
        stroke: #198754;
    }

    .wizard-step-item.active .wizard-step-icon {
        transform: scale(1.06);
    }
</style>
<div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="col-md-9 col-lg-7">
        <div class="card shadow-lg">
            <div class="card-body">
                <div class="d-flex justify-content-end">
                    <a href="{{ route('dashboard') }}" class="btn btn-outline-danger btn-sm">X</a>
                </div>

                <div class="mb-4">
                    <h2 class="text-center mb-3">{{ __('Create Project') }}</h2>
                    <div class="wizard-steps" aria-label="Project creation steps">
                        <div class="wizard-steps-track">
                            <div id="wizardProgressFill" class="wizard-steps-fill" style="transform: scaleX({{ $step === 2 ? '1' : '0' }});"></div>
                        </div>
                        <div class="wizard-steps-items">
                            <div id="wizardStepItem1" class="wizard-step-item {{ $step === 1 ? 'active' : 'completed' }}">
                                <span class="wizard-step-icon" aria-hidden="true">
                                    <svg viewBox="0 0 24 24" fill="none" stroke-width="2">
                                        <path d="M12 5v14M5 12h14" stroke-linecap="round" stroke-linejoin="round"></path>
                                        <circle cx="12" cy="12" r="9"></circle>
                                    </svg>
                                </span>
                                <span id="wizardStepLabel1" class="wizard-step-label">Create Project</span>
                            </div>
                            <div id="wizardStepItem2" class="wizard-step-item {{ $step === 2 ? 'active' : '' }}">
                                <span class="wizard-step-icon" aria-hidden="true">
                                    <svg viewBox="0 0 24 24" fill="none" stroke-width="2">
                                        <path d="M5 12l4 4 10-10" stroke-linecap="round" stroke-linejoin="round"></path>
                                        <circle cx="12" cy="12" r="9"></circle>
                                    </svg>
                                </span>
                                <span id="wizardStepLabel2" class="wizard-step-label">Confirm Project</span>
                            </div>
                        </div>
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
            const progressFill = document.getElementById('wizardProgressFill');
            const stepItem1 = document.getElementById('wizardStepItem1');
            const stepItem2 = document.getElementById('wizardStepItem2');
            const projectIdCheckUrl = "{{ route('projects.check_uid') }}";

            if (!container) {
                return;
            }

            function attachBudgetDisplayFormatting(scope) {
                const form = scope.querySelector('form');
                const budgetDisplay = scope.querySelector('#budget_display');
                const budgetHidden = scope.querySelector('input[name="budget"]');

                if (!form || !budgetDisplay || !budgetHidden) {
                    return;
                }

                const normalizeRawValue = (value) => {
                    return (value || '').toString().replace(/,/g, '').trim();
                };

                const formatWithThousands = (value) => {
                    const raw = normalizeRawValue(value);
                    if (!raw || !/^-?\d+(\.\d+)?$/.test(raw)) {
                        return value;
                    }

                    const parts = raw.split('.');
                    parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ',');
                    return parts.join('.');
                };

                const syncBudgetRaw = () => {
                    budgetHidden.value = normalizeRawValue(budgetDisplay.value);
                };

                budgetDisplay.addEventListener('input', syncBudgetRaw);

                budgetDisplay.addEventListener('blur', function () {
                    syncBudgetRaw();
                    if (budgetHidden.value) {
                        budgetDisplay.value = formatWithThousands(budgetHidden.value);
                    }
                });

                form.addEventListener('submit', syncBudgetRaw);

                if (budgetDisplay.value) {
                    syncBudgetRaw();
                    budgetDisplay.value = formatWithThousands(budgetHidden.value);
                }
            }

            function attachProjectIdValidation(scope) {
                const form = scope.querySelector('form');
                const projectIdInput = scope.querySelector('input[name="project_uid"]');
                if (!form || !projectIdInput) {
                    return;
                }

                let uidExists = false;

                const checkAvailability = async () => {
                    const value = (projectIdInput.value || '').trim();
                    uidExists = false;

                    if (!value) {
                        return true;
                    }

                    try {
                        const response = await fetch(`${projectIdCheckUrl}?project_uid=${encodeURIComponent(value)}`, {
                            headers: { 'X-Requested-With': 'XMLHttpRequest' }
                        });

                        if (!response.ok) {
                            return true;
                        }

                        const payload = await response.json();
                        uidExists = !!payload.exists;

                        if (uidExists) {
                            window.alert('This ID already exists');
                            projectIdInput.focus();
                        }

                        return !uidExists;
                    } catch (error) {
                        return true;
                    }
                };

                projectIdInput.addEventListener('blur', checkAvailability);

                form.addEventListener('submit', async function (event) {
                    const isAvailable = await checkAvailability();
                    if (!isAvailable) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                });
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
                    progressFill.style.transform = isStep2 ? 'scaleX(1)' : 'scaleX(0)';
                    stepItem1.classList.toggle('active', !isStep2);
                    stepItem1.classList.toggle('completed', isStep2);
                    stepItem2.classList.toggle('active', isStep2);
                    attachBudgetDisplayFormatting(container);
                    attachProjectIdValidation(container);
                })
                .catch(() => {
                    container.innerHTML = '<div class="text-danger text-center py-3">Failed to load step content.</div>';
                });
        })();
    </script>
@endpush
