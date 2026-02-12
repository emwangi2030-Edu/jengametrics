@extends('layouts.app')

@section('content')
@php
    $canManageMaterials = auth()->check() && (!auth()->user()->isSubAccount() || auth()->user()->can_manage_materials);
@endphp
<div class="row mt-5 {{ $canManageMaterials ? '' : 'materials-readonly' }}">
    <div class="col-12">
        <h3 class="font-weight-bold" style="color:#027333;">Inventory Management</h3>
        <div class="row mt-5">
            <div class="card shadow-sm">
                <div class="card-body">
                    <table class="table table-bordered mt-3 text-center">
                        <thead class="table-light">
                            <tr>
                                <th>{{ __('Name') }}</th>
                                <th>{{ __('Unit of Measure') }}</th>
                                <th>{{ __('Total Quantity in Stock') }}</th>
                                <th>{{ __('Issue Stock') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($inventory as $item)
                                @php
                                    $inventoryIsAdhoc = empty($item->product_id);
                                    $inventoryRouteKey = $inventoryIsAdhoc
                                        ? 'adhoc-' . md5($item->name . '|' . $item->unit_of_measure)
                                        : $item->product_id;
                                @endphp
                                <tr>
                                    <td><div class="px-2">{{ $item->name }}</div></td>
                                    <td>{{ $item->unit_of_measure }}</td>
                                    <td>{{ $item->total_stock }}</td>
                                    <td>
                                        <div class="px-2">
                                            <form action="{{ route('materials.use', $inventoryRouteKey) }}" method="POST" class="issue-form d-flex gap-2">
                                                @csrf
                                                @if($inventoryIsAdhoc)
                                                    <input type="hidden" name="adhoc_name" value="{{ $item->name }}">
                                                    <input type="hidden" name="adhoc_unit" value="{{ $item->unit_of_measure }}">
                                                @endif
                                                <input type="number" name="quantity_used" class="form-control form-control-sm quantity-used" 
                                                    placeholder="Qty" step="0.01" required 
                                                    total-stock="{{ $item->total_stock }}"
                                                    unit-of-measure="{{ $item->unit_of_measure }}">
                                                <select name="section_id" class="form-select form-select-sm" required>
                                                    <option value="" disabled selected>Select Section</option>
                                                    @foreach($sections as $section)
                                                        <option value="{{ $section->id }}">{{ $section->name }}</option>
                                                    @endforeach
                                                </select>
                                                <button type="submit" class="btn btn-warning btn-sm">Issue</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">{{ __('No inventory found.') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Prevent stock over-issue (your existing check)
        document.querySelectorAll('.quantity-used').forEach(function(input) {
            input.addEventListener('input', function () {
                const totalStock = parseFloat(this.getAttribute('total-stock')) || 0;
                const entered = parseFloat(this.value) || 0;
                const unitOfMeasure = this.getAttribute('unit-of-measure') || '';
                if (entered > totalStock) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Insufficient Stock',
                        confirmButtonColor: '#027333'
                    }).then(() => {
                        this.value = totalStock;
                    });
                }
            });
        });

        // AJAX submit
        document.querySelectorAll('.issue-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(form);
                const url = form.action;

                fetch(url, {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": form.querySelector('input[name="_token"]').value
                    },
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    if (data.error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: data.error,
                            confirmButtonColor: '#027333'
                        });
                    } else {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: data.success,
                            confirmButtonColor: '#027333'
                        });

                        // Update stock in table without reloading
                        const stockCell = form.closest('tr').querySelector('td:nth-child(3)');
                        stockCell.textContent = data.remaining_stock;

                        const qtyInput = form.querySelector('.quantity-used');
                        if (qtyInput) {
                            qtyInput.setAttribute('total-stock', data.remaining_stock);
                        }

                        // Reset form
                        form.reset();
                    }
                })
            });
        });
    });
</script>

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
