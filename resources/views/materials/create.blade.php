@extends('layouts.app')

@section('content')
<div class="container py-5">
    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="row mb-5 text-center">
        <div class="col-12">
            <h2 class="jm-page-title jm-ui-title">{{ __('Receive Approved Materials') }}</h2>
            <p class="jm-page-subtitle jm-ui-muted">{{ __('Capture delivered quantities, pricing, and supplier records.') }}</p>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card jm-ui-card shadow-sm border-0 rounded">
                <div class="card-body p-5">
                    <form action="{{ route('materials.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        {{-- Requisition Dropdown --}}
                        <div class="mb-4">
                            <label class="form-label">Approved Requisition ID</label>
                            <div class="input-group">
                                <select class="form-select" id="requisition_id" name="requisition_id" required {{ $requisitions->isEmpty() ? 'disabled' : '' }}>
                                    <option value="" disabled {{ old('requisition_id') ? '' : 'selected' }}>Select Requisition</option>
                                    @foreach($requisitions as $req)
                                        <option
                                            value="{{ $req->id }}"
                                            data-unit="{{ $req->unit }}"
                                            data-remaining="{{ (float) $req->remaining_quantity }}"
                                            data-requested="{{ (float) $req->requested_quantity }}"
                                            data-material="{{ $req->material_name }}"
                                            data-requisition-no="{{ $req->requisition_no }}"
                                            {{ (string) old('requisition_id') === (string) $req->id ? 'selected' : '' }}
                                        >
                                            #{{ $req->id }} - {{ $req->requisition_no }} - {{ $req->material_name }} (Remaining: {{ (float) $req->remaining_quantity }} {{ $req->unit }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <input type="hidden" id="requisitioned_quantity" name="requisitioned_quantity" value="{{ old('requisitioned_quantity') }}">

                        @if($requisitions->isEmpty())
                            <p class="text-muted small mb-0">No approved requisitions pending delivery.</p>
                        @endif

                        <div id="requisitionSummary" class="alert alert-light border mb-4 d-none">
                            <div class="small text-muted">Selected Requisition</div>
                            <div class="fw-semibold" id="requisitionSummaryTitle"></div>
                            <div class="small text-muted" id="requisitionSummaryMeta"></div>
                        </div>

                        {{-- Unit Price --}}
                        <div class="form-floating mb-4">
                            <input type="text" class="form-control" id="unit_price" name="unit_price" value="{{ old('unit_price') }}" placeholder="Enter price" required>
                            <label for="unit_price" id="unit_price_label">Price per Unit</label>
                        </div>

                        {{-- Quantity --}}
                        <div class="form-floating mb-4">
                            <input type="number" step="0.01" class="form-control text-muted" 
                                id="quantity_in_stock" name="quantity_in_stock"
                                value="{{ old('quantity_in_stock') }}"
                                placeholder="Enter quantity" required>
                            <label for="quantity_in_stock" id="quantity_label">Quantity</label>
                        </div>

                        {{-- Variance --}}
                        <div class="form-floating mb-4">
                            <input type="text" class="form-control" id="variance_display" placeholder="Variance" value="{{ old('variance') }}" readonly>
                            <label for="variance_display">Variance</label>
                        </div>

                        <input type="hidden" id="variance" name="variance" value="{{ old('variance') }}">

                        {{-- Supplier Dropdown + Add Button --}}
                        <div class="mb-4">
                            <label class="form-label">Supplier</label>
                            <div class="input-group">
                                <select class="form-select" id="supplier_id" name="supplier_id" required>
                                    <option value="" disabled selected>Select Supplier</option>
                                    @foreach($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}" data-contact="{{ $supplier->contact_info }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>{{ $supplier->name }}</option>
                                    @endforeach
                                </select>
                                <br>
                                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addSupplierModal">{{ __('Add Supplier') }}</button>
                            </div>
                        </div>

                        {{-- Supplier Contact --}}
                        <div class="form-floating mb-4">
                            <input type="text" class="form-control" id="supplier_contact" name="supplier_contact" placeholder="Supplier contact" value="{{ old('supplier_contact') }}" readonly>
                            <label for="supplier_contact">Supplier Contact</label>
                        </div>

                        {{-- Upload Document --}}
                        <div class="mb-4">
                            <label for="document" class="form-label">Upload Receipt (PDF, PNG, JPG)</label>
                            <input type="file" name="document" id="document" class="form-control">
                        </div>

                        {{-- Submit Button --}}
                        <div class="d-flex justify-content-center">
                            <button type="submit" class="btn btn-success w-50 py-2" {{ $requisitions->isEmpty() ? 'disabled' : '' }}>
                                {{ __('Save Material Delivery') }}
                            </button>
                        </div>

                        {{-- Back Button --}}
                        <div class="d-flex justify-content-center mt-3">
                            <a href="{{ route('materials.delivered') }}" class="btn btn-outline-secondary" aria-label="Back" title="Back"><span data-feather="arrow-left-circle"></span></a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Supplier Modal -->
<div class="modal fade" id="addSupplierModal" tabindex="-1" aria-labelledby="addSupplierModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title text-white" id="addSupplierModalLabel">Add New Supplier</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-4">
        <div class="mb-3">
            <label for="new_supplier_name" class="form-label">Supplier Name</label>
            <input type="text" class="form-control" id="new_supplier_name" placeholder="Enter supplier name">
        </div>
        <div class="mb-3">
            <label for="new_supplier_contact" class="form-label">Contact Information</label>
            <input type="text" class="form-control" id="new_supplier_contact" placeholder="Enter contact details">
        </div>
      </div>
      <div class="modal-footer border-0 d-flex justify-content-between px-4 pb-4">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" id="saveNewSupplier" class="btn btn-success">Save Supplier</button>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function () {
        const $requisitionSelect = $('#requisition_id');
        const $quantityInput = $('#quantity_in_stock');
        const $unitPriceLabel = $('#unit_price_label');
        const $quantityLabel = $('#quantity_label');
        const $varianceDisplay = $('#variance_display');
        const $varianceInput = $('#variance');
        const $requisitionedQuantity = $('#requisitioned_quantity');
        const $summary = $('#requisitionSummary');
        const $summaryTitle = $('#requisitionSummaryTitle');
        const $summaryMeta = $('#requisitionSummaryMeta');

        $('#supplier_id').on('change', function () {
            const contact = $(this).find('option:selected').data('contact');
            $('#supplier_contact').val(contact);
        });

        $('#saveNewSupplier').on('click', function () {
            const name = $('#new_supplier_name').val();
            const contact = $('#new_supplier_contact').val();

            if (name && contact) {
                $.ajax({
                    url: "{{ route('suppliers.ajaxStore') }}",
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        name: name,
                        contact_info: contact
                    },
                    success: function (response) {
                         if (response.id && response.name) {
                             const newOption = $('<option>', {
                                 value: response.id,
                                 text: response.name,
                                 'data-contact': response.contact_info
                             });
                             $('#supplier_id').append(newOption);
                             newOption.prop('selected', true);
                             $('#supplier_contact').val(response.contact_info);
                             $('#addSupplierModal').modal('hide');
                             $('#new_supplier_name, #new_supplier_contact').val('');
                         } else {
                             alert('Failed to add supplier.');
                         }
                    },
                    error: function (xhr) {
                        alert('Error: ' + xhr.responseText);
                    }
                });
            } else {
                alert('Please fill out both name and contact.');
            }
        });

        const setVariance = function () {
            const selectedOption = $requisitionSelect.find('option:selected');
            const remaining = parseFloat(selectedOption.data('remaining'));
            const unit = selectedOption.data('unit') || '';
            const entered = parseFloat($quantityInput.val());

            if (Number.isNaN(entered) || Number.isNaN(remaining)) {
                $varianceDisplay.val('');
                $varianceInput.val('');
                return;
            }

            let difference = Number((entered - remaining).toFixed(2));
            if (Math.abs(difference) < 0.005) {
                difference = 0;
            }

            let varianceText = '0';
            if (difference > 0) {
                varianceText = 'excess: ' + difference + ' ' + unit;
            } else if (difference < 0) {
                varianceText = 'remaining balance: ' + Math.abs(difference) + ' ' + unit;
            }

            $varianceDisplay.val(varianceText);
            $varianceInput.val(difference);
        };

        $requisitionSelect.on('change', function () {
            const selectedOption = $(this).find('option:selected');
            const remaining = parseFloat(selectedOption.data('remaining'));
            const requested = parseFloat(selectedOption.data('requested'));
            const unit = selectedOption.data('unit') || '';
            const material = selectedOption.data('material') || '';
            const requisitionNo = selectedOption.data('requisition-no') || '';
            const requisitionId = selectedOption.val() || '';

            $unitPriceLabel.text(unit ? 'Price per ' + unit : 'Price per Unit');
            $quantityLabel.text(unit ? 'Quantity Delivered (' + unit + ')' : 'Quantity Delivered');

            if (!Number.isNaN(remaining)) {
                $quantityInput.attr('placeholder', 'Remaining: ' + remaining + ' ' + unit);
                $requisitionedQuantity.val(remaining);
            } else {
                $quantityInput.attr('placeholder', 'Enter quantity delivered');
                $requisitionedQuantity.val('');
            }

            if (requisitionId) {
                $summary.removeClass('d-none');
                $summaryTitle.text('#' + requisitionId + ' - ' + requisitionNo + ' - ' + material);
                $summaryMeta.text(
                    'Requested: ' + (Number.isNaN(requested) ? 'N/A' : requested) + ' ' + unit +
                    ' | Remaining: ' + (Number.isNaN(remaining) ? 'N/A' : remaining) + ' ' + unit
                );
            } else {
                $summary.addClass('d-none');
                $summaryTitle.text('');
                $summaryMeta.text('');
            }

            setVariance();
        });

        $quantityInput.on('input', function () {
            setVariance();
        });

        if ($requisitionSelect.val()) {
            $requisitionSelect.trigger('change');
        }
    });
</script>
@endpush
