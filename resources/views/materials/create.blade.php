@extends('layouts.appbar')

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
            <h2 class="display-6 fw-bold" style="color:#027333;">Record New Material Purchase</h2>
            <p class="text-muted">Fill in the material details and supplier information below.</p>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0 rounded">
                <div class="card-body p-5">
                    <form action="{{ route('materials.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        {{-- Material Dropdown --}}
                        <div class="mb-4">
                            <label class="form-label">Materials (from Approved Requisitions)</label>
                            <div class="input-group">
                                <select class="form-select" id="material_key" name="material_key" required {{ $requisitions->isEmpty() ? 'disabled' : '' }}>
                                    <option value="" disabled {{ old('material_key') ? '' : 'selected' }}>Select Material</option>
                                    @foreach($requisitions as $req)
                                        <option
                                            value="{{ $req->key }}"
                                            data-type="{{ $req->type }}"
                                            data-product="{{ $req->product_id }}"
                                            data-name="{{ $req->name }}"
                                            data-unit="{{ $req->unit }}"
                                            data-quantity="{{ (float) $req->remaining_quantity }}"
                                            data-requested="{{ (float) $req->requested_quantity }}"
                                            {{ old('material_key') === $req->key ? 'selected' : '' }}
                                        >
                                            {{ $req->name }} (Remaining: {{ (float) $req->remaining_quantity }} {{ $req->unit }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <input type="hidden" id="material_type" name="material_type" value="{{ old('material_type') }}">
                        <input type="hidden" id="product_id" name="product_id" value="{{ old('product_id') }}">
                        <input type="hidden" id="adhoc_name" name="adhoc_name" value="{{ old('adhoc_name') }}">
                        <input type="hidden" id="adhoc_unit" name="adhoc_unit" value="{{ old('adhoc_unit') }}">
                        <input type="hidden" id="requisitioned_quantity" name="requisitioned_quantity" value="{{ old('requisitioned_quantity') }}">
                        <input type="hidden" id="expected_quantity" name="expected_quantity" value="{{ old('expected_quantity') }}">

                        @if($requisitions->isEmpty())
                            <p class="text-muted small">No approved requisitions available to receive.</p>
                        @endif

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
                                <button type="button" class="btn text-white" style="background-color: #027333;" data-bs-toggle="modal" data-bs-target="#addSupplierModal">Add Supplier</button>
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
                            <button type="submit" class="btn w-50 py-2 text-white" style="background-color:#027333;">
                                {{ isset($material) ? 'Update Material' : 'Add Material' }}
                            </button>
                        </div>

                        {{-- Back Button --}}
                        <div class="d-flex justify-content-center mt-3">
                            <a href="{{ route('materials.index') }}" class="btn btn-dark">Back</a>
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
      <div class="modal-header" style="background-color:#027333;">
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
        <button type="button" id="saveNewSupplier" class="btn text-white" style="background-color:#027333;">Save Supplier</button>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        const $materialSelect = $('#material_key');
        const $materialType = $('#material_type');
        const $productIdInput = $('#product_id');
        const $adhocNameInput = $('#adhoc_name');
        const $adhocUnitInput = $('#adhoc_unit');
        const $quantityInput = $('#quantity_in_stock');
        const $unitPriceLabel = $('#unit_price_label');
        const $varianceDisplay = $('#variance_display');
        const $varianceInput = $('#variance');
        const $requisitionedQuantity = $('#requisitioned_quantity');
        const $expectedQuantity = $('#expected_quantity');
        const initialQuantity = $quantityInput.val();
        const hasInitialQuantity = initialQuantity !== '';

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

        $materialSelect.on('change', function () {
            const selectedOption = $(this).find('option:selected');
            const type = selectedOption.data('type') || '';
            const rawMax = selectedOption.data('quantity');
            const maxQty = parseFloat(rawMax);
            const hasMaxQty = !Number.isNaN(maxQty);
            const unit = selectedOption.data('unit') || '';
            const productId = selectedOption.data('product') || '';
            const name = selectedOption.data('name') || '';
            const requestedRaw = selectedOption.data('requested');
            const requested = requestedRaw !== undefined ? parseFloat(requestedRaw) : NaN;
            const hasRequested = !Number.isNaN(requested);

            $materialType.val(type);

            if (type === 'bom') {
                $productIdInput.val(productId);
                $adhocNameInput.val('');
                $adhocUnitInput.val('');
            } else if (type === 'adhoc') {
                $productIdInput.val('');
                $adhocNameInput.val(name);
                $adhocUnitInput.val(unit);
            } else {
                $productIdInput.val('');
                $adhocNameInput.val('');
                $adhocUnitInput.val('');
            }

            $unitPriceLabel.text(unit ? 'Price per ' + unit : 'Price per Unit');
            $quantityInput
                .attr('placeholder', hasMaxQty ? ('Approved quantity: ' + maxQty + ' ' + unit + ' (can record more)') : 'Enter quantity')
                .data('max', hasMaxQty ? maxQty : null)
                .data('unit', unit);

            if (!hasInitialQuantity) {
                $quantityInput.val('');
            }

            if (hasMaxQty) {
                $requisitionedQuantity.val(maxQty);
            } else if (hasRequested) {
                $requisitionedQuantity.val(requested);
            } else {
                $requisitionedQuantity.val('');
            }

            if (hasMaxQty) {
                $expectedQuantity.val(maxQty);
            } else if (hasRequested) {
                $expectedQuantity.val(requested);
            } else {
                $expectedQuantity.val('');
            }

            $varianceDisplay.val('');
            $varianceInput.val('');
        });

        $quantityInput.on('input', function () {
            const maxRaw = $(this).data('max');
            const max = typeof maxRaw === 'number' ? maxRaw : parseFloat(maxRaw);
            const unit = $(this).data('unit') || '';
            const entered = parseFloat($(this).val());

            if (Number.isNaN(entered) || Number.isNaN(max)) {
                $varianceDisplay.val('');
                $varianceInput.val('');
                return;
            }

            let difference = Number((entered - max).toFixed(2));
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
        });

        if ($materialSelect.val()) {
            $materialSelect.trigger('change');
            if (hasInitialQuantity) {
                $quantityInput.val(initialQuantity);
                $quantityInput.trigger('input');
            }
        }
    });
</script>
@endpush

