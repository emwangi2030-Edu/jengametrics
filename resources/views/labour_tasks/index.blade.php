@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="jm-page-header">
        <div>
            <h2 class="jm-page-title jm-ui-title">{{ __('Labour Tasks Board') }}</h2>
            <p class="jm-page-subtitle jm-ui-muted mb-0">{{ __('Create teams, assign work, and track progress with a Kanban view.') }}</p>
        </div>
        <div class="jm-actions-bar">
            <a href="{{ route('workers.index') }}" class="btn btn-outline-secondary" aria-label="Back" title="Back"><span data-feather="arrow-left-circle"></span></a>
            <button type="button" class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#createGroupModal">
                {{ __('Create Team') }}
            </button>
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createTaskModal">
                {{ __('Create Task') }}
            </button>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-12">
            <div class="card jm-ui-card shadow-sm border-0">
                <div class="card-header bg-white border-0 pb-0">
                    <h5 class="jm-section-title mb-0">{{ __('Teams') }}</h5>
                </div>
                <div class="card-body">
                    @if($groups->isEmpty())
                        <p class="text-muted mb-0">{{ __('No teams created yet.') }}</p>
                    @else
                        <ul class="mb-0">
                            @foreach($groups as $group)
                                <li>
                                    <a href="{{ route('labour_tasks.groups.show', $group) }}" class="fw-semibold text-decoration-none">
                                        {{ $group->name }}
                                    </a>
                                    <small class="text-muted">({{ $group->workers->count() }} members)</small>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card jm-ui-card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-0 pb-0 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 text-warning">Pending Tasks</h5>
                    <span id="pendingTasksCount" class="badge bg-warning text-dark">{{ $pendingTasks->count() }}</span>
                </div>
                <div id="pendingTasksColumn" class="card-body">
                    @forelse($pendingTasks as $task)
                        @php
                            $assigneeLabel = $task->assignee_type === 'group'
                                ? optional($task->group)->name
                                : optional($task->worker)->full_name;
                            $sectionLabel = optional($task->section)->name;
                        @endphp
                        <div id="task-card-{{ $task->id }}" class="card jm-ui-surface mb-3 border-start border-warning border-4 task-card"
                             role="button"
                             data-bs-toggle="modal"
                             data-bs-target="#taskDetailsModal"
                             data-task-title="{{ $task->title }}"
                             data-task-description="{{ $task->description }}"
                             data-task-assignee="{{ $assigneeLabel }}"
                             data-task-section="{{ $sectionLabel }}"
                             data-task-due="{{ $task->due_date ? $task->due_date->format('M d, Y') : 'Not set' }}">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start gap-2">
                                    <div>
                                        <h6 class="mb-1">{{ $task->title }}</h6>
                                        @if(!empty($task->description))
                                            <small class="text-muted d-block">{{ \Illuminate\Support\Str::limit($task->description, 90) }}</small>
                                        @endif
                                        <small class="text-muted d-block mt-1">
                                            {{ __('Assigned to:') }} {{ $assigneeLabel ?: __('Not assigned') }}
                                        </small>
                                        <small class="text-muted d-block">
                                            Section: {{ $sectionLabel ?: 'N/A' }}
                                        </small>
                                    </div>
                                    <form method="POST" action="{{ route('labour_tasks.tasks.complete', $task) }}" class="js-complete-task-form" onclick="event.stopPropagation();">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-success js-complete-task-btn">
                                            Details
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted mb-0">{{ __('No pending tasks.') }}</p>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card jm-ui-card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-0 pb-0 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 text-success">Completed Tasks</h5>
                    <span id="completedTasksCount" class="badge bg-success">{{ $completedTasks->count() }}</span>
                </div>
                <div id="completedTasksColumn" class="card-body">
                    @forelse($completedTasks as $task)
                        @php
                            $assigneeLabel = $task->assignee_type === 'group'
                                ? optional($task->group)->name
                                : optional($task->worker)->full_name;
                            $sectionLabel = optional($task->section)->name;
                        @endphp
                        <div class="card jm-ui-surface mb-3 border-start border-success border-4">
                            <div class="card-body">
                                <h6 class="mb-1 text-decoration-line-through">{{ $task->title }}</h6>
                                <small class="text-muted d-block">Assigned to: {{ $assigneeLabel ?: 'N/A' }}</small>
                                <small class="text-muted d-block">Section: {{ $sectionLabel ?: 'N/A' }}</small>
                                <small class="text-muted d-block">Completed: {{ $task->completed_at ? $task->completed_at->diffForHumans() : 'N/A' }}</small>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted mb-0">{{ __('No completed tasks yet.') }}</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="createGroupModal" tabindex="-1" aria-labelledby="createGroupModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('labour_tasks.groups.store') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="createGroupModalLabel">{{ __('Create Team') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">{{ __('Team Name') }}</label>
                        <input type="text" class="form-control" name="name" maxlength="255" required>
                    </div>
                    <div class="mb-2 fw-semibold">{{ __('Add Workers') }}</div>
                    <div class="jm-scroll-y-sm">
                        @forelse($workers as $worker)
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="worker_ids[]" id="group-worker-{{ $worker->id }}" value="{{ $worker->id }}">
                                <label class="form-check-label" for="group-worker-{{ $worker->id }}">
                                    {{ $worker->full_name }}
                                    <small class="text-muted d-block">
                                        {{ $worker->job_category }}{{ $worker->work_type ? ' - ' . $worker->work_type : '' }}
                                        @if(!empty($worker->details) && $worker->details !== '>')
                                            | {{ $worker->details }}
                                        @endif
                                    </small>
                                </label>
                            </div>
                        @empty
                            <small class="text-muted">{{ __('No active workers available.') }}</small>
                        @endforelse
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">{{ __('Create Team') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="createTaskModal" tabindex="-1" aria-labelledby="createTaskModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('labour_tasks.tasks.store') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="createTaskModalLabel">{{ __('Create Task') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Task Title</label>
                        <input type="text" class="form-control" name="title" maxlength="255" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" name="description" rows="3" maxlength="2000"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Section</label>
                        <select class="form-select" name="section_id" required>
                            <option value="" selected disabled>Select section</option>
                            @foreach($sections as $section)
                                <option value="{{ $section->id }}">{{ $section->name }}</option>
                            @endforeach
                        </select>
                        @if($sections->isEmpty())
                            <small class="text-muted">No sections found for this project yet.</small>
                        @endif
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Assign To</label>
                        <select class="form-select" id="assigneeType" name="assignee_type" required>
                            <option value="">Select</option>
                            <option value="group">Group</option>
                            <option value="worker">Individual Worker</option>
                        </select>
                    </div>
                    <div class="mb-3 d-none" id="groupSelectWrapper">
                        <label class="form-label">Group</label>
                        <select class="form-select" name="worker_group_id" id="groupSelect">
                            <option value="">Select group</option>
                            @foreach($groups as $group)
                                <option value="{{ $group->id }}">{{ $group->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3 d-none" id="workerSelectWrapper">
                        <label class="form-label">Worker</label>
                        <select class="form-select" name="worker_id" id="workerSelect">
                            <option value="">Select worker</option>
                            @foreach($workers as $worker)
                                <option value="{{ $worker->id }}">{{ $worker->full_name }} - 
                                    <small class="text-muted d-block">{{ $worker->job_category }}</small>
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Due Date (optional)</label>
                        <input type="date" class="form-control" name="due_date">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Create Task</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="taskDetailsModal" tabindex="-1" aria-labelledby="taskDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="taskDetailsModalLabel">Task Assignment Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h6 id="detailTaskTitle" class="mb-2"></h6>
                <p id="detailTaskDescription" class="text-muted"></p>
                <p class="mb-1"><strong>Assigned To:</strong> <span id="detailAssignee"></span></p>
                <p class="mb-1"><strong>Section:</strong> <span id="detailSection"></span></p>
                <p class="mb-0"><strong>Due Date:</strong> <span id="detailDue"></span></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" id="confirmCompleteTaskBtn" class="btn btn-success" disabled>Mark as Complete</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const csrfToken = @json(csrf_token());
        const assigneeType = document.getElementById('assigneeType');
        const groupWrapper = document.getElementById('groupSelectWrapper');
        const workerWrapper = document.getElementById('workerSelectWrapper');
        const groupSelect = document.getElementById('groupSelect');
        const workerSelect = document.getElementById('workerSelect');
        const pendingColumn = document.getElementById('pendingTasksColumn');
        const completedColumn = document.getElementById('completedTasksColumn');
        const pendingCount = document.getElementById('pendingTasksCount');
        const completedCount = document.getElementById('completedTasksCount');
        const taskDetailsModal = document.getElementById('taskDetailsModal');
        const confirmCompleteTaskBtn = document.getElementById('confirmCompleteTaskBtn');
        let pendingCompletionForm = null;

        const populateTaskDetails = function (trigger) {
            if (!trigger) {
                return;
            }

            document.getElementById('detailTaskTitle').textContent = trigger.dataset.taskTitle || 'N/A';
            document.getElementById('detailTaskDescription').textContent = trigger.dataset.taskDescription || 'No description';
            document.getElementById('detailAssignee').textContent = trigger.dataset.taskAssignee || 'N/A';
            document.getElementById('detailSection').textContent = trigger.dataset.taskSection || 'N/A';
            document.getElementById('detailDue').textContent = trigger.dataset.taskDue || 'Not set';
        };

        const completeTaskViaAjax = async function (form) {
            const taskButton = form.querySelector('.js-complete-task-btn');
            if (taskButton) {
                taskButton.disabled = true;
            }

            try {
                const response = await fetch(form.action, {
                    method: 'PATCH',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                if (!response.ok) {
                    throw new Error('Failed to complete task');
                }

                const payload = await response.json();
                const taskId = payload?.task?.id;
                const taskCard = document.getElementById(`task-card-${taskId}`);

                if (taskCard && completedColumn) {
                    taskCard.removeAttribute('id');
                    taskCard.removeAttribute('role');
                    taskCard.removeAttribute('data-bs-toggle');
                    taskCard.removeAttribute('data-bs-target');
                    taskCard.classList.remove('border-warning');
                    taskCard.classList.add('border-success');

                    const title = taskCard.querySelector('h6');
                    if (title) {
                        title.classList.add('text-decoration-line-through');
                    }

                    const actionForm = taskCard.querySelector('.js-complete-task-form');
                    if (actionForm) {
                        actionForm.remove();
                    }

                    const footerSmall = document.createElement('small');
                    footerSmall.className = 'text-muted d-block';
                    footerSmall.textContent = `Completed: ${payload?.task?.completed_human || 'just now'}`;

                    const body = taskCard.querySelector('.card-body');
                    if (body) {
                        body.appendChild(footerSmall);
                    }

                    completedColumn.prepend(taskCard);
                }

                if (pendingCount && payload?.counts) {
                    pendingCount.textContent = payload.counts.pending;
                }
                if (completedCount && payload?.counts) {
                    completedCount.textContent = payload.counts.completed;
                }

                return true;
            } catch (error) {
                if (taskButton) {
                    taskButton.disabled = false;
                }
                return false;
            }
        };

        if (assigneeType) {
            assigneeType.addEventListener('change', function () {
                const value = this.value;
                groupWrapper.classList.toggle('d-none', value !== 'group');
                workerWrapper.classList.toggle('d-none', value !== 'worker');

                if (value !== 'group' && groupSelect) {
                    groupSelect.value = '';
                }
                if (value !== 'worker' && workerSelect) {
                    workerSelect.value = '';
                }
            });
        }

        if (taskDetailsModal) {
            taskDetailsModal.addEventListener('show.bs.modal', function (event) {
                const trigger = event.relatedTarget;
                if (trigger) {
                    populateTaskDetails(trigger);

                    if (!pendingCompletionForm && trigger.classList.contains('task-card')) {
                        pendingCompletionForm = trigger.querySelector('.js-complete-task-form');
                    }
                }

                if (confirmCompleteTaskBtn) {
                    confirmCompleteTaskBtn.disabled = !pendingCompletionForm;
                }
            });

            taskDetailsModal.addEventListener('hidden.bs.modal', function () {
                pendingCompletionForm = null;
                if (confirmCompleteTaskBtn) {
                    confirmCompleteTaskBtn.disabled = true;
                }
            });
        }

        if (confirmCompleteTaskBtn) {
            confirmCompleteTaskBtn.addEventListener('click', async function () {
                if (!pendingCompletionForm) {
                    return;
                }

                confirmCompleteTaskBtn.disabled = true;
                const completed = await completeTaskViaAjax(pendingCompletionForm);

                if (completed && window.bootstrap) {
                    const modalInstance = bootstrap.Modal.getOrCreateInstance(taskDetailsModal);
                    modalInstance.hide();
                } else {
                    confirmCompleteTaskBtn.disabled = false;
                }
            });
        }

        document.querySelectorAll('.js-complete-task-form').forEach((form) => {
            form.addEventListener('submit', function (event) {
                event.preventDefault();

                const taskCard = form.closest('.task-card');
                pendingCompletionForm = form;

                if (taskCard) {
                    populateTaskDetails(taskCard);
                }

                if (confirmCompleteTaskBtn) {
                    confirmCompleteTaskBtn.disabled = false;
                }

                if (window.bootstrap && taskDetailsModal) {
                    const modalInstance = bootstrap.Modal.getOrCreateInstance(taskDetailsModal);
                    modalInstance.show();
                    return;
                }
            });
        });
    });
</script>
@endpush
