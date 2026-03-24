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
            <div class="table-responsive jm-ui-table-wrap">
                <table class="table table-bordered align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Requisition No.</th>
                            <th>Material</th>
                            <th>BoQ</th>
                            <th>Quantity Requested</th>
                            <th>Status</th>
                            <th>Section</th>
                            <th>Requested By</th>
                            <th>Requested At</th>
                            <th>Approved By</th>
                            <th>Approved At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($requisitions as $req)
                            <tr>
                                <td>{{ $req->requisition_no }}</td>
                                <td>{{ $req->bomItem->item_material->name ?? $req->extra_material_name }}</td>
                                <td>{{ $req->bomItem->bqDocument->title ?? ($req->extra_material_name ? __('Ad-hoc Request') : __('Unknown')) }}</td>
                                <td>
                                    {{ (int) $req->quantity_requested }} {{ $req->bomItem->item_material->unit_of_measurement ?? $req->extra_unit }}
                                </td>
                                <td>
                                    <span class="badge bg-{{ $req->status == 'approved' ? 'success' : ($req->status == 'rejected' ? 'danger' : 'secondary') }}">
                                    {{ ucfirst($req->status) }}
                                    </span>
                                </td>
                                <td>{{ $req->section->name }}</td>
                                <td>{{ $req->requester->name ?? 'N/A' }}</td>
                                <td>{{ $req->requested_at ? \Carbon\Carbon::parse($req->requested_at)->format('d-m-Y') : '-' }}</td>
                                <td>{{ $req->approver->name ?? '-' }}</td>
                                <td>{{ $req->approved_at ? \Carbon\Carbon::parse($req->approved_at)->format('d-m-Y') : '-' }}</td>
                                <td>
                                    @if($req->status === 'pending')
                                        <form action="{{ route('requisitions.approve', $req->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button class="btn btn-sm btn-success">Approve</button>
                                        </form>
                                        <form action="{{ route('requisitions.reject', $req->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button class="btn btn-sm btn-danger">Reject</button>
                                        </form>
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center">No requisitions found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
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
