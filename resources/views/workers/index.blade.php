@extends('layouts.app')

@section('content')
@php
    $canManageLabour = auth()->check() && (!auth()->user()->isSubAccount() || auth()->user()->can_manage_labour);
@endphp
<div class="container py-4 {{ $canManageLabour ? '' : 'labour-readonly' }}">
    <div class="jm-page-header mb-2">
        <div>
            <h2 class="jm-page-title">{{ __('Manage Labour:') }} <span class="text-dark">{{ $project->name }}</span></h2>
            <p class="jm-page-subtitle mb-0">{{ __('Workers, attendance, and labour assignments.') }}</p>
        </div>
            <div class="jm-actions-bar">
                <a href="{{ route('labour_tasks.index') }}" class="btn btn-secondary">
                    {{ __('Assign Tasks') }}
                </a>
                <a href="{{ route('workers.create') }}" class="btn btn-success">
                    {{ __('Add Worker') }}
                </a>
                <a href="{{ route('attendance.create') }}" class="btn btn-info">
                    {{ __('Daily Attendance') }} 
                </a>
            </div>
    </div>

    <div class="d-flex justify-content-center">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <form method="GET" class="row g-3 align-items-end mb-3" id="statusFilterForm">
                        <div class="col-md-4 col-lg-3">
                            <label for="status-filter" class="form-label mb-1">{{ __('Status') }}</label>
                            <select id="status-filter" name="status" class="form-select">
                                <option value="active" @selected(($status ?? '') === 'active')>{{ __('Active') }}</option>
                                <option value="terminated" @selected(($status ?? '') === 'terminated')>{{ __('Terminated') }}</option>
                                <option value="all" @selected(($status ?? '') === 'all')>{{ __('All') }}</option>
                            </select>
                        </div>
                    </form>
                    <div id="workers-table-wrapper">
                        @include('workers.partials.table', ['workers' => $workers])
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const alertBox = document.getElementById('success-alert');
        if (alertBox) {
            setTimeout(() => {
                alertBox.classList.remove('show');
                alertBox.classList.add('fade');
            }, 4000);
        }

        const statusSelect = document.getElementById('status-filter');
        const filterForm = document.getElementById('statusFilterForm');
        if (statusSelect && filterForm) {
            statusSelect.addEventListener('change', () => {
                const wrapper = document.getElementById('workers-table-wrapper');
                if (!wrapper) {
                    filterForm.submit();
                    return;
                }
                const params = new URLSearchParams(new FormData(filterForm));
                fetch(`{{ route('workers.index') }}?${params.toString()}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    cache: 'no-store'
                })
                    .then(res => res.text())
                    .then(html => {
                        wrapper.innerHTML = html;
                    })
                    .catch(() => filterForm.submit());
            });
        }
    });
</script>
@endpush

@if(!$canManageLabour)
    @push('styles')
        <style>
            .labour-readonly .btn {
                opacity: 0.55;
                cursor: not-allowed;
            }

            .labour-readonly .btn.allow-readonly {
                opacity: 1;
                cursor: pointer;
            }

            .labour-readonly a.btn:hover,
            .labour-readonly button:hover {
                transform: none !important;
            }

            .labour-readonly .btn.allow-readonly:hover {
                cursor: pointer;
            }

            .labour-readonly .btn:hover {
                cursor: not-allowed;
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            (function () {
                const container = document.querySelector('.labour-readonly');
                if (!container) {
                    return;
                }

                const allowedLabels = ['back', 'cancel', 'close', 'return', 'reset'];
                container.querySelectorAll('.btn').forEach((btn) => {
                    const text = (btn.textContent || '').trim().toLowerCase();
                    if (allowedLabels.includes(text) || btn.hasAttribute('data-allow-readonly')) {
                        btn.classList.add('allow-readonly');
                    }
                });

                container.querySelectorAll('[data-bs-toggle="modal"]').forEach((trigger) => {
                    if (trigger.classList.contains('allow-readonly')) {
                        return;
                    }
                    trigger.setAttribute('data-bs-toggle-disabled', 'true');
                    if (trigger.hasAttribute('data-bs-target')) {
                        trigger.setAttribute('data-bs-target-disabled', trigger.getAttribute('data-bs-target'));
                        trigger.removeAttribute('data-bs-target');
                    }
                    trigger.removeAttribute('data-bs-toggle');
                });

                let hoverToast = null;
                function showNoAccessToast() {
                    const toastContainer = document.getElementById('toast-container');
                    if (!toastContainer || hoverToast) {
                        return;
                    }

                    const toast = document.createElement('div');
                    toast.className = 'toast show';
                    toast.setAttribute('role', 'alert');
                    toast.setAttribute('aria-live', 'assertive');
                    toast.setAttribute('aria-atomic', 'true');

                    toast.innerHTML = `
                        <div class="toast-header">
                            <strong class="me-auto">System</strong>
                            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                        </div>
                        <div class="toast-body">
                            <span class="badge bg-warning">Warning</span>
                            You do not have access
                        </div>
                    `;

                    toastContainer.appendChild(toast);
                    hoverToast = toast;
                }

                function hideNoAccessToast() {
                    if (!hoverToast) {
                        return;
                    }
                    hoverToast.remove();
                    hoverToast = null;
                }

                container.addEventListener('mouseenter', function (event) {
                    const target = event.target.closest('.btn');
                    if (!target) {
                        return;
                    }
                    if (target.classList.contains('allow-readonly')) {
                        return;
                    }
                    showNoAccessToast();
                }, true);

                container.addEventListener('mouseleave', function (event) {
                    const target = event.target.closest('.btn');
                    if (!target) {
                        return;
                    }
                    if (target.classList.contains('allow-readonly')) {
                        return;
                    }
                    hideNoAccessToast();
                }, true);

                container.addEventListener('click', function (event) {
                    const target = event.target.closest('.btn');
                    if (!target) {
                        return;
                    }
                    if (target.classList.contains('allow-readonly')) {
                        return;
                    }
                    event.preventDefault();
                    event.stopPropagation();
                    event.stopImmediatePropagation();
                }, true);
            })();
        </script>
    @endpush
@endif
