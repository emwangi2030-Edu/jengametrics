@extends('layouts.app')

@section('content')
@php
    $canManageMaterials = auth()->check() && (!auth()->user()->isSubAccount() || auth()->user()->can_manage_materials);
@endphp
<div class="container mt-4 {{ $canManageMaterials ? '' : 'materials-readonly' }}">
    <div class="jm-page-header">
        <div>
            <h2 class="jm-page-title jm-ui-title">{{ __('Material Requisitions') }}</h2>
            <p class="jm-page-subtitle jm-ui-muted mb-0">{{ __('Track requests, approvals, and consumption by section.') }}</p>
        </div>
        <div class="jm-actions-bar">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#requisitionModal">
                {{ __('Requisition Material') }}
            </button>
        </div>
    </div>
    <div class="card jm-ui-card shadow-sm border-0">
        <div class="card-body">
            <form method="GET" action="{{ route('requisitions.index') }}" class="row g-2 mb-3" id="requisitions-status-filter">
                <div class="col-sm-6 col-md-4 col-lg-3">
                    <select name="status" class="form-select">
                        <option value="all" {{ ($statusFilter ?? 'all') === 'all' ? 'selected' : '' }}>All Statuses</option>
                        <option value="pending" {{ ($statusFilter ?? 'all') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ ($statusFilter ?? 'all') === 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ ($statusFilter ?? 'all') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>
                <div class="col-sm-6 col-md-4 col-lg-3">
                    <a href="{{ route('requisitions.index') }}" class="btn btn-outline-secondary w-100" id="requisitions-filter-reset" data-allow-readonly>Reset Filter</a>
                </div>
            </form>
            <div id="requisitions-table-results">
                @include('requisitions.partials.requisitions_table', ['requisitions' => $requisitions])
            </div>
        </div>
    </div>        
    <h3 class="jm-section-title mt-4">{{ __('Summary of Approved Requisitions') }}</h3>
    <div class="card jm-ui-card shadow-sm border-0">
        <div class="card-body">
            <div class="table-responsive jm-ui-table-wrap">
                <table class="table table-bordered text-center mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Material</th>
                            <th>Quantity Requested</th>
                            <th>UoM</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($approvedSummary as $summary)
                            <tr>
                                <td>{{ $summary->material }}</td>
                                <td>{{ (int) $summary->total_quantity }}</td>
                                <td>{{ $summary->unit }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center">No approved requisitions found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@include('requisitions.requisition_modal')
@include('requisitions.adhoc_modal')
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const filterForm = document.getElementById('requisitions-status-filter');
            const results = document.getElementById('requisitions-table-results');
            const statusSelect = filterForm ? filterForm.querySelector('select[name="status"]') : null;
            const resetBtn = document.getElementById('requisitions-filter-reset');

            if (!filterForm || !results || !statusSelect) {
                return;
            }

            const fetchTable = function () {
                const params = new URLSearchParams(new FormData(filterForm));
                const url = filterForm.action + '?' + params.toString();

                results.innerHTML = '<div class="py-5 text-center text-muted">{{ __('Loading...') }}</div>';

                fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                    .then(function (response) {
                        if (!response.ok) {
                            throw new Error('Network error');
                        }
                        return response.json();
                    })
                    .then(function (data) {
                        results.innerHTML = data.table || '';
                        const status = statusSelect.value || 'all';
                        const nextUrl = new URL(filterForm.action, window.location.origin);
                        if (status !== 'all') {
                            nextUrl.searchParams.set('status', status);
                        }
                        window.history.replaceState({}, '', nextUrl.toString());
                    })
                    .catch(function () {
                        results.innerHTML = '<div class="alert alert-danger mb-0">{{ __('Failed to load requisitions. Please try again.') }}</div>';
                    });
            };

            statusSelect.addEventListener('change', function () {
                fetchTable();
            });

            filterForm.addEventListener('submit', function (event) {
                event.preventDefault();
                fetchTable();
            });

            if (resetBtn) {
                resetBtn.addEventListener('click', function (event) {
                    event.preventDefault();
                    statusSelect.value = 'all';
                    fetchTable();
                });
            }
        });
    </script>
@endpush

@if(!$canManageMaterials)
    @push('styles')
        <style>
            .materials-readonly .btn {
                opacity: 0.55;
                cursor: not-allowed;
            }

            .materials-readonly .btn.allow-readonly {
                opacity: 1;
                cursor: pointer;
            }

            .materials-readonly a.btn:hover,
            .materials-readonly button:hover {
                transform: none !important;
            }

            .materials-readonly .btn.allow-readonly:hover {
                cursor: pointer;
            }

            .materials-readonly .btn:hover {
                cursor: not-allowed;
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            (function () {
                const container = document.querySelector('.materials-readonly');
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
