@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="row mb-3 align-items-center">
            <div class="col">
                <h2 class="fw-bold m-0" style="color:#027333">BoQ • <span class="text-dark">{{ $bqSection->name }}</span></h2>
            </div>
            <div class="col-auto">
                <a href="{{ route('boq') }}" class="btn btn-outline-secondary btn-sm">Back to BoQ</a>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-11">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <div class="d-flex gap-2 mb-3">
                            <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#addItemModal">
                                {{ __('Add New Item') }}
                            </button>
                            <a href="{{ route('bq_sections.bulk_create', ['section_id' => $bqSection->id]) }}" class="btn btn-outline-success btn-sm">
                                {{ __('Bulk Add (Full Page)') }}
                            </a>
                            <a href="{{ route('boms.show', $bqSection->id) }}" class="btn btn-outline-primary btn-sm">
                                {{ __('View BoM for this Section') }}
                            </a>
                            <form action="{{ route('boms.sections.rebuild', $bqSection->id) }}" method="POST" onsubmit="return confirm('Rebuild BoM from BoQ for this section? This will overwrite existing BoM entries.');">
                                @csrf
                                <button type="submit" class="btn btn-warning btn-sm">{{ __('Rebuild BoM') }}</button>
                            </form>
                        </div>

                        <!-- Add Item Modal -->
                        <div class="modal fade" id="addItemModal" tabindex="-1" aria-labelledby="addItemModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="addItemModalLabel">{{ __('Add New BoQ Item') }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form method="POST" action="{{ route('bq_sections.store') }}">
                                        @csrf
                                        <input type="hidden" name="section_id" value="{{ $bqSection->id }}">
                                        <div class="modal-body">
                                            <div class="row g-3">
                                                <div class="col-12">
                                                    <label for="modal_element" class="form-label">{{ __('Element') }}</label>
                                                    <select name="element_id" id="modal_element" class="form-select" required>
                                                        <option value="">{{ __('Choose Element') }}</option>
                                                    </select>
                                                </div>
                                                <div class="col-12">
                                                    <label for="modal_item_id" class="form-label">{{ __('Item') }}</label>
                                                    <select name="item_id" id="modal_item_id" class="form-select" required>
                                                        <option value="">{{ __('Choose Item') }}</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="modal_rate" class="form-label">{{ __('Rate') }}</label>
                                                    <input type="number" step="0.01" name="rate" id="modal_rate" class="form-control" placeholder="0.00" required>
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="modal_quantity" class="form-label">{{ __('Quantity') }}</label>
                                                    <input type="number" step="0.01" name="quantity" id="modal_quantity" class="form-control" placeholder="0" required>
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="modal_amount" class="form-label">{{ __('Amount') }}</label>
                                                    <input type="number" step="0.01" name="amount" id="modal_amount" class="form-control" placeholder="0.00" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                                            <button type="submit" class="btn btn-success">{{ __('Save to BoQ') }}</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-sm table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col">{{ __('Name') }}</th>
                                        <th scope="col">{{ __('Unit') }}</th>
                                        <th scope="col" class="text-end">{{ __('Quantity') }}</th>
                                        <th scope="col" class="text-end">{{ __('Rate (KES)') }}</th>
                                        <th scope="col" class="text-end">{{ __('Amount (KES)') }}</th>
                                        <th scope="col" class="text-end">{{ __('Actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $totalQuantity = 0;
                                        $totalAmount = 0;
                                    @endphp
                                    @forelse ($items as $item)
                                        @php
                                            $totalQuantity += $item->quantity;
                                            $totalAmount += $item->amount;
                                        @endphp
                                        <tr>
                                            <td class="p-2">{{ $item->item_name }}</td>
                                            <td class="text-nowrap">{{ $item->units }}</td>
                                            <td class="text-end">{{ number_format($item->quantity, 2) }}</td>
                                            <td class="text-end">{{ number_format($item->rate, 2) }}</td>
                                            <td class="text-end">{{ number_format($item->amount, 2) }}</td>
                                            <td class="text-end">
                                                <div class="d-inline-flex gap-2">
                                                    <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editItemModal{{ $item->id }}">
                                                        Edit
                                                    </button>
                                                    @include('bq_sections.modals.edit_item')

                                                    <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteItemModal{{ $item->id }}">
                                                        Delete
                                                    </button>
                                                    @include('bq_sections.modals.delete_item')
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center text-muted py-4">{{ __('No items found.') }}</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                                <tfoot>
                                    <tr class="table-secondary">
                                        <th colspan="2" class="text-end text-uppercase small">{{ __('Total') }}</th>
                                        <th class="text-end fw-bold">{{ number_format($totalQuantity, 2) }}</th>
                                        <th></th>
                                        <th class="text-end fw-bold">KES {{ number_format($totalAmount, 2) }}</th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                            </table>
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
        var addItemModal = document.getElementById('addItemModal');
        if (!addItemModal) return;

        var elementSelect = document.getElementById('modal_element');
        var itemSelect = document.getElementById('modal_item_id');
        var rateInput = document.getElementById('modal_rate');
        var qtyInput = document.getElementById('modal_quantity');
        var amountInput = document.getElementById('modal_amount');
        var sectionId = {{ $bqSection->id }};

        function computeAmount() {
            var r = parseFloat(rateInput.value) || 0;
            var q = parseFloat(qtyInput.value) || 0;
            amountInput.value = (r * q).toFixed(2);
        }

        rateInput && rateInput.addEventListener('input', computeAmount);
        qtyInput && qtyInput.addEventListener('input', computeAmount);

        addItemModal.addEventListener('shown.bs.modal', function () {
            // Load elements for this section
            fetch(`{{ route('get.elements') }}?section_id=${sectionId}`)
                .then(r => r.json())
                .then(data => {
                    elementSelect.innerHTML = '<option value="">{{ __('Choose Element') }}</option>';
                    Object.keys(data).forEach(function (id) {
                        var opt = document.createElement('option');
                        opt.value = id;
                        opt.textContent = data[id];
                        elementSelect.appendChild(opt);
                    });
                    itemSelect.innerHTML = '<option value="">{{ __('Choose Item') }}</option>';
                })
                .catch(() => {
                    elementSelect.innerHTML = '<option value="">{{ __('Failed to load elements') }}</option>';
                });
        });

        elementSelect && elementSelect.addEventListener('change', function () {
            var elementId = this.value;
            itemSelect.innerHTML = '<option value="">{{ __('Choose Item') }}</option>';
            if (!elementId) return;
            fetch(`{{ route('get.items') }}?element_id=${elementId}`)
                .then(r => r.json())
                .then(data => {
                    itemSelect.innerHTML = '<option value="">{{ __('Choose Item') }}</option>';
                    Object.keys(data).forEach(function (id) {
                        var opt = document.createElement('option');
                        opt.value = id;
                        opt.textContent = data[id];
                        itemSelect.appendChild(opt);
                    });
                })
                .catch(() => {
                    itemSelect.innerHTML = '<option value="">{{ __('Failed to load items') }}</option>';
                });
        });
    });
</script>
@endpush
