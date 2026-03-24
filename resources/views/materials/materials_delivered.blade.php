@extends('layouts.app')

@section('content')
@php
    $canManageMaterials = auth()->check() && (!auth()->user()->isSubAccount() || auth()->user()->can_manage_materials);
@endphp
<div class="container py-4 {{ $canManageMaterials ? '' : 'materials-readonly' }}">
    <div class="jm-page-header">
        <div>
            <h2 class="jm-page-title jm-ui-title">{{ __('Material Deliveries') }}</h2>
            <p class="jm-page-subtitle jm-ui-muted mb-0">{{ __('Review deliveries against requisitions and monitor supplier performance.') }}</p>
        </div>
        <div class="jm-actions-bar">
        <div>
            <button type="button" class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#requisitionModal">
                {{ __('Requisition Material') }}
            </button>
        </div>
        <div>
            <a href="{{ route('materials.create') }}" class="btn btn-success me-2">
                {{ __('Receive Approved Materials') }}
            </a>
        </div>
        </div>
    </div>

<div class="row justify-content-center">
    <div class="col-md-12">
        <h3 class="jm-section-title">{{ __('Delivered Materials') }}</h3>
        <div class="card jm-ui-card shadow-sm border-0">
            <form method="GET" action="{{ route('materials.delivered') }}" class="row g-2 mt-2 justify-content-center jm-ui-surface mx-3 p-3" id="materials-delivered-filters">
                <div class="col-md-3">
                    <select name="filter" class="form-select">
                        <option value="">All Time</option>
                        <option value="week" {{ request('filter') == 'week' ? 'selected' : '' }}>This Week</option>
                        <option value="month" {{ request('filter') == 'month' ? 'selected' : '' }}>This Month</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="year" class="form-select">
                        @foreach($availableYears as $y)
                            <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="supplier_id" class="form-select">
                        <option value="">All Suppliers</option>
                        @foreach($suppliers as $s)
                            <option value="{{ $s->id }}" {{ request('supplier_id') == $s->id ? 'selected' : '' }}>
                                {{ $s->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </form>
            <div class="card-body" id="materials-delivered-results">
                @include('materials.partials.delivered_table', ['materials' => $materials])
            </div>
        </div>
    </div>
</div>

@include('requisitions.requisition_modal')
@include('requisitions.adhoc_modal')

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const form = document.getElementById('materials-delivered-filters');
        const results = document.getElementById('materials-delivered-results');

        if (!form || !results) {
            return;
        }

        const fetchMaterials = () => {
            const params = new URLSearchParams(new FormData(form));
            const url = `${form.action}?${params.toString()}`;

            results.innerHTML = '<div class="py-5 text-center text-muted">{{ __('Loading...') }}</div>';

            fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then((response) => {
                    if (!response.ok) {
                        throw new Error('Network problem');
                    }
                    return response.json();
                })
                .then((data) => {
                    results.innerHTML = data.table ?? '';
                })
                .catch(() => {
                    results.innerHTML = '<div class="alert alert-danger" role="alert">{{ __('Failed to load data. Please try again.') }}</div>';
                });
        };

        form.addEventListener('change', fetchMaterials);
        form.addEventListener('submit', (event) => {
            event.preventDefault();
            fetchMaterials();
        });
    });
</script>
@endpush
@endsection

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
