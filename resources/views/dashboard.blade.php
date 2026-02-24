@extends('layouts.app')

@section('content')
@php
    $circleRadius = 54;
    $circleCircumference = 2 * pi() * $circleRadius;
    $circleOffset = $circleCircumference - (($projectCompletionPercent / 100) * $circleCircumference);
@endphp

<div class="container-fluid">
    <style>
        .project-completion-circle {
            width: 140px;
            height: 140px;
            margin: 0 auto;
            position: relative;
        }

        .project-completion-circle svg {
            transform: rotate(-90deg);
        }

        .project-completion-circle .progress-value {
            position: absolute;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            color: #027333;
            font-size: 1.25rem;
        }

        .project-step-item {
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 0.6rem 0.75rem;
            margin-bottom: 0.5rem;
            background: #fff;
        }
    </style>

    <div class="d-flex align-items-center justify-content-between my-4">
        <h1 class="mb-0" style="color:#027333">Dashboard</h1>
        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#projectStepsModal">
            Project Steps
        </button>
    </div>

    <div class="row justify-content-center g-4">
        <!-- Total Workers Card -->
        <div class="col-md-3">
            <div class="card shadow border-0">
                <div class="card-body text-center">
                    <h5 class="card-title" style="color:#027333">Total Workers</h5>
                    <h3 class="text-dark">{{ $totalWorkers}}</h3>
                </div>
            </div>
        </div>

        <!-- Total Material Expenses Card -->
        <div class="col-md-3">
            <div class="card shadow border-0">
                <div class="card-body text-center">
                    <h5 class="card-title" style="color:#027333">Total Material Expenses</h5>
                    <h3 class="text-dark">KES {{ number_format($totalMaterialExpenses, 2) }}</h3>
                </div>
            </div>
        </div>

        <!-- Total Labour Expenses Card -->
        <div class="col-md-3">
            <div class="card shadow border-0">
                <div class="card-body text-center">
                    <h5 class="card-title" style="color:#027333">Total Labour Expenses</h5>
                    <h3 class="text-dark">KES {{ number_format($totalPayments, 2) }}</h3>
                </div>
            </div>
        </div>

        <!-- Project Duration Card -->
        <div class="col-md-3">
            <div class="card shadow border-0">
                <div class="card-body text-center">
                    <h5 class="card-title" style="color:#027333">Project Duration</h5>
                    <h3 class="{{ $projectDurationColorClass }}">{{ $projectRunningWeeks }} weeks</h3>
                    @if($projectDurationExceeded)
                        <small class="text-danger">estimated project duration exceeded!</small>
                    @elseif(!$projectEstimatedWeeks)
                        <small class="text-muted">Estimated duration not set.</small>
                    @else
                        <small class="text-muted">Estimated: {{ $projectEstimatedWeeks }} weeks</small>
                    @endif
                    <small class="d-block text-muted mt-1">
                        Created: {{ $projectCreatedDate ?? 'N/A' }}
                    </small>
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center g-4 mt-2">
        <div class="col-md-4">
            <div class="card shadow border-0">
                <div class="card-body text-center">
                    <h5 class="card-title mb-3" style="color:#027333">Project Completion</h5>
                    <div class="project-completion-circle">
                        <svg width="140" height="140" viewBox="0 0 140 140" role="img" aria-label="Project completion radial progress">
                            <circle cx="70" cy="70" r="{{ $circleRadius }}" fill="none" stroke="#e9ecef" stroke-width="12"></circle>
                            <circle
                                id="projectCompletionCircleProgress"
                                cx="70"
                                cy="70"
                                r="{{ $circleRadius }}"
                                fill="none"
                                stroke="#20c997"
                                stroke-width="12"
                                stroke-linecap="round"
                                stroke-dasharray="{{ $circleCircumference }}"
                                stroke-dashoffset="{{ $circleOffset }}"
                                data-circumference="{{ $circleCircumference }}"
                                style="transition: stroke-dashoffset 0.6s ease;"
                            ></circle>
                        </svg>
                        <div id="projectCompletionPercentText" class="progress-value">{{ $projectCompletionPercent }}%</div>
                    </div>
                    <small id="projectCompletionSummaryText" class="text-muted d-block mt-2">
                        {{ $completedProjectSteps }} of {{ $totalProjectSteps }} steps completed
                    </small>
                </div>
            </div>
        </div>
    </div>

    <br>

    <form method="GET" action="{{ route('dashboard') }}" class="mb-3">
        <label for="year" style="color:#027333">Filter by Year:</label>
        <select name="year" id="year" onchange="this.form.submit()" class="form-select w-auto d-inline-block">
            @foreach ($availableYears as $year)
                <option value="{{ $year }}" {{ $year == $selectedYear ? 'selected' : '' }}>
                    {{ $year }}
                </option>
            @endforeach
        </select>
    </form>

    <div class="row mt-4">
        <div class="col-md-12">
            <h3 style="color:#027333">Material & Labour Expense Trends</h3>
            <canvas id="expenseChart"></canvas>
        </div>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('expenseChart').getContext('2d');

    const expenseChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($labels),
            datasets: [
                {
                    label: 'Material Expenses',
                    data: @json($data),
                    borderColor: 'rgba(75, 192, 192, 1)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.3
                },
                {
                    label: 'Labour Expenses',
                    data: @json($labourData),
                    borderColor: 'rgba(255, 159, 64, 1)',
                    backgroundColor: 'rgba(255, 159, 64, 0.2)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.3
                }
            ]
        },
        options: {
        responsive: true,
        animation: {
            duration: 1000,
            easing: 'easeOutQuart'
        },
        animations: {
            tension: {
                duration: 1000,
                easing: 'easeOutQuart',
                from: 0.5,
                to: 0.3,
                loop: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 20000,
                    callback: function(value) {
                        return value.toLocaleString();
                    }
                },
                title: {
                    display: true,
                    text: 'Expense (KES)',
                    color: '#333',
                    font: {
                        size: 14,
                        weight: 'bold'
                    }
                }
            }
        },
        plugins: {
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return 'KES ' + context.parsed.y.toLocaleString();
                    }
                }
            }
        }
    }
    });
</script>

<div class="modal fade" id="projectStepsModal" tabindex="-1" aria-labelledby="projectStepsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="projectStepsModalLabel">Project Steps</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="projectStepsList">
                @if($projectSteps->isEmpty())
                    <p class="text-muted mb-0">No project steps added yet.</p>
                @else
                    @foreach($projectSteps as $step)
                        <div class="project-step-item d-flex align-items-center justify-content-between" draggable="true" data-step-id="{{ $step->id }}">
                            <div class="d-flex align-items-center gap-3">
                                <span class="text-muted" title="Drag to reorder" style="cursor: grab;">&#9776;</span>
                                <input
                                    class="form-check-input js-step-toggle"
                                    type="checkbox"
                                    data-url="{{ route('dashboard.project_steps.toggle', $step) }}"
                                    {{ $step->is_completed ? 'checked' : '' }}
                                >
                                <span class="js-step-title {{ $step->is_completed ? 'text-decoration-line-through text-muted' : '' }}">
                                    {{ $step->title }}
                                </span>
                            </div>
                            <small class="text-muted js-step-completed-at">
                                @if($step->is_completed && $step->completed_at)
                                    Done {{ $step->completed_at->diffForHumans() }}
                                @endif
                            </small>
                        </div>
                    @endforeach
                @endif
                </div>
            </div>
            <div class="modal-footer d-flex justify-content-between">
                <button type="button" class="btn btn-primary" data-bs-target="#addProjectStepsModal" data-bs-toggle="modal">
                    Add Project Steps
                </button>
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addProjectStepsModal" tabindex="-1" aria-labelledby="addProjectStepsModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('dashboard.project_steps.store') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="addProjectStepsModalLabel">Add Project Steps</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="projectStepsInputList">
                        <div class="input-group mb-2">
                            <input type="text" class="form-control" name="steps[]" maxlength="255" placeholder="Enter project step" required>
                            <button type="button" class="btn btn-outline-danger remove-step-input" disabled>Remove</button>
                        </div>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-success" id="addAnotherStepBtn">+ Add Another Step</button>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Save Steps</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const csrfToken = @json(csrf_token());
        const stepsContainer = document.getElementById('projectStepsInputList');
        const addStepButton = document.getElementById('addAnotherStepBtn');
        const projectStepsList = document.getElementById('projectStepsList');
        const progressCircle = document.getElementById('projectCompletionCircleProgress');
        const percentText = document.getElementById('projectCompletionPercentText');
        const summaryText = document.getElementById('projectCompletionSummaryText');
        const reorderUrl = @json(route('dashboard.project_steps.reorder'));

        if (!stepsContainer || !addStepButton) {
            return;
        }

        const createStepInputRow = () => {
            const row = document.createElement('div');
            row.className = 'input-group mb-2';
            row.innerHTML = `
                <input type="text" class="form-control" name="steps[]" maxlength="255" placeholder="Enter project step" required>
                <button type="button" class="btn btn-outline-danger remove-step-input">Remove</button>
            `;
            return row;
        };

        addStepButton.addEventListener('click', function () {
            stepsContainer.appendChild(createStepInputRow());
            const removeButtons = stepsContainer.querySelectorAll('.remove-step-input');
            removeButtons.forEach((btn) => btn.disabled = false);
        });

        stepsContainer.addEventListener('click', function (event) {
            const target = event.target;
            if (!target.classList.contains('remove-step-input')) {
                return;
            }

            const row = target.closest('.input-group');
            if (!row) {
                return;
            }

            row.remove();

            const rows = stepsContainer.querySelectorAll('.input-group');
            if (rows.length === 1) {
                const onlyRemoveButton = rows[0].querySelector('.remove-step-input');
                if (onlyRemoveButton) {
                    onlyRemoveButton.disabled = true;
                }
            }
        });

        const updateRadialProgress = (stats) => {
            if (!progressCircle || !percentText || !summaryText || !stats) {
                return;
            }

            const circumference = parseFloat(progressCircle.dataset.circumference || '0');
            const percent = Number(stats.projectCompletionPercent || 0);
            const completed = Number(stats.completedProjectSteps || 0);
            const total = Number(stats.totalProjectSteps || 0);

            const offset = circumference - ((percent / 100) * circumference);
            progressCircle.setAttribute('stroke-dashoffset', String(offset));
            percentText.textContent = `${percent}%`;
            summaryText.textContent = `${completed} of ${total} steps completed`;
        };

        if (projectStepsList) {
            projectStepsList.addEventListener('change', async function (event) {
                const checkbox = event.target;
                if (!checkbox.classList.contains('js-step-toggle')) {
                    return;
                }

                const url = checkbox.dataset.url;
                const row = checkbox.closest('.project-step-item');
                const title = row ? row.querySelector('.js-step-title') : null;
                const completedAt = row ? row.querySelector('.js-step-completed-at') : null;
                const intendedState = checkbox.checked;

                try {
                    const response = await fetch(url, {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({ is_completed: intendedState ? 1 : 0 })
                    });

                    if (!response.ok) {
                        throw new Error('Failed to update step');
                    }

                    const payload = await response.json();
                    const isCompleted = !!payload?.step?.is_completed;

                    if (title) {
                        title.classList.toggle('text-decoration-line-through', isCompleted);
                        title.classList.toggle('text-muted', isCompleted);
                    }
                    if (completedAt) {
                        completedAt.textContent = isCompleted && payload?.step?.completed_at_human
                            ? `Done ${payload.step.completed_at_human}`
                            : '';
                    }

                    updateRadialProgress(payload?.stats);
                } catch (error) {
                    checkbox.checked = !intendedState;
                }
            });

            let draggedRow = null;
            let originalOrder = [];

            const getCurrentOrder = () => {
                return Array.from(projectStepsList.querySelectorAll('.project-step-item[data-step-id]'))
                    .map((row) => Number(row.dataset.stepId));
            };

            projectStepsList.addEventListener('dragstart', function (event) {
                const row = event.target.closest('.project-step-item[data-step-id]');
                if (!row) {
                    return;
                }

                draggedRow = row;
                originalOrder = getCurrentOrder();
                row.classList.add('opacity-50');
                event.dataTransfer.effectAllowed = 'move';
            });

            projectStepsList.addEventListener('dragover', function (event) {
                event.preventDefault();
                const target = event.target.closest('.project-step-item[data-step-id]');
                if (!target || !draggedRow || target === draggedRow) {
                    return;
                }

                const rect = target.getBoundingClientRect();
                const insertBefore = event.clientY < rect.top + (rect.height / 2);
                if (insertBefore) {
                    projectStepsList.insertBefore(draggedRow, target);
                } else {
                    projectStepsList.insertBefore(draggedRow, target.nextSibling);
                }
            });

            projectStepsList.addEventListener('dragend', async function () {
                if (!draggedRow) {
                    return;
                }

                draggedRow.classList.remove('opacity-50');
                draggedRow = null;

                const newOrder = getCurrentOrder();
                if (JSON.stringify(originalOrder) === JSON.stringify(newOrder) || newOrder.length === 0) {
                    return;
                }

                try {
                    const response = await fetch(reorderUrl, {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({ steps: newOrder })
                    });

                    if (!response.ok) {
                        throw new Error('Failed to reorder steps');
                    }
                } catch (error) {
                    const rowsById = {};
                    Array.from(projectStepsList.querySelectorAll('.project-step-item[data-step-id]')).forEach((row) => {
                        rowsById[Number(row.dataset.stepId)] = row;
                    });

                    originalOrder.forEach((id) => {
                        if (rowsById[id]) {
                            projectStepsList.appendChild(rowsById[id]);
                        }
                    });
                }
            });
        }
    });
</script>
@endsection
