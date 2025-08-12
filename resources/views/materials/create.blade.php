@extends('layouts.appbar')

@section('content')
<div class="container py-5">
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
                    <form action="{{ route('m.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        {{-- Material Dropdown --}}
                        <div class="mb-4">
                            <label class="form-label">Materials</label>
                            <div class="input-group">
                                <select class="form-select" id="product_id" name="product_id" required>
                                    <option value="" disabled selected>Select Material</option>
                                    @foreach($requisitions as $req)
                                        <option
                                            value="{{ $req->product_id }}"
                                            data-quantity="{{ (int) $req->remaining_quantity }}"
                                            data-unit="{{ $req->material->unit_of_measurement }}"
                                        >
                                            {{ $req->material->name }} (Max Qty: {{ (int) $req->remaining_quantity }} {{ $req->material->unit_of_measurement }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- Unit Price --}}
                        <div class="form-floating mb-4">
                            <input type="text" class="form-control" id="unit_price" name="unit_price" value="{{ old('unit_price', $material->unit_price ?? '') }}" placeholder="Enter price" required>
                            <label for="unit_price" id="unit_price_label">Price per Unit</label>
                        </div>

                        {{-- Quantity --}}
                        <div class="form-floating mb-4">
                            <input type="number" step="0.01" class="form-control text-muted" 
                                id="quantity_in_stock" name="quantity_in_stock"
                                value="{{ old('quantity_in_stock', $material->quantity_in_stock ?? '') }}"
                                placeholder="Enter quantity" required>
                            <label for="quantity_in_stock" id="quantity_label">Quantity</label>
                        </div>

                        {{-- Variance --}}
                        <div class="form-floating mb-4">
                            <input type="text" class="form-control" id="variance" name="variance" placeholder="Variance" readonly>
                            <label for="variance">Variance</label>
                        </div>

                        {{-- Supplier Dropdown + Add Button --}}
                        <div class="mb-4">
                            <label class="form-label">Supplier</label>
                            <div class="input-group">
                                <select class="form-select" id="supplier_id" name="supplier_id" required>
                                    <option value="" disabled selected>Select Supplier</option>
                                    @foreach($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}" data-contact="{{ $supplier->contact_info }}">{{ $supplier->name }}</option>
                                    @endforeach
                                </select>
                                <br>
                                <button type="button" class="btn text-white" style="background-color: #027333;" data-bs-toggle="modal" data-bs-target="#addSupplierModal">Add Supplier</button>
                            </div>
                        </div>

                        {{-- Supplier Contact --}}
                        <div class="form-floating mb-4">
                            <input type="text" class="form-control" id="supplier_contact" name="supplier_contact" placeholder="Supplier contact" readonly>
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
        // Update supplier contact
        $('#supplier_id').on('change', function () {
            var contact = $(this).find('option:selected').data('contact');
            $('#supplier_contact').val(contact);
        });

        // Save new supplier via AJAX
        $('#saveNewSupplier').on('click', function () {
            var name = $('#new_supplier_name').val();
            var contact = $('#new_supplier_contact').val();

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
                            let newOption = $('<option>', {
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

        $('#product_id').on('change', function () {
            const selectedOption = $(this).find('option:selected');
            const maxQty = parseFloat(selectedOption.data('quantity'));
            const unit = selectedOption.data('unit');

            $('#unit_price_label').text('Price per ' + unit);
            $('#quantity_in_stock')
                .val('')
                .attr('placeholder', `Max approved quantity: ${maxQty} ${unit}`)
                .data('max', maxQty)
                .data('unit', unit);

            $('#variance').val('');
        });

        $('#quantity_in_stock').on('input', function () {
            const max = parseFloat($(this).data('max'));
            const unit = $(this).data('unit');
            const entered = parseFloat($(this).val());

            if (!max || isNaN(entered)) {
                $('#variance').val('');
                return;
            }

            let difference = entered - max;

            if (difference > 0) {
                $('#variance').val(`excess: ${difference} ${unit}`);
            } else if (difference < 0) {
                $('#variance').val(`remaining balance: ${Math.abs(difference)} ${unit}`);
            } else {
                $('#variance').val(0);
            }
        });
    });
</script>
@endpush
