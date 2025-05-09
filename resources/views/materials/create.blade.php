@extends('layouts.appbar')

@section('content')
<div class="container py-5">
    <div class="row mb-5 text-center">
        <div class="col-12">
            <h2 class="display-6 fw-bold" style="color:#027333;">Add New Material</h2>
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
                        <div class="form-floating mb-4">
                            <select class="form-select" id="bom_item_id" name="bom_item_id" required>
                                <option value="" disabled selected>Select Material</option>
                                @foreach($items as $item)
                                    <option value="{{ $item->item_material->id ?? '' }}" data-unit="{{ $item->item_material->unit_of_measurement ?? 'Unit' }}">
                                        {{ $item->item_material->name ?? 'No Name Available' }}
                                    </option>
                                @endforeach
                            </select>
                            <label for="bom_item_id">Material</label>
                            @error('bom_item_id')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Unit Price --}}
                        <div class="form-floating mb-4">
                            <input type="text" class="form-control" id="unit_price" name="unit_price" value="{{ old('unit_price', $material->unit_price ?? '') }}" placeholder="Enter price" required>
                            <label for="unit_price" id="unit_price_label">Price per Unit</label>
                        </div>

                        {{-- Quantity --}}
                        <div class="form-floating mb-4">
                            <input type="number" class="form-control" id="quantity_in_stock" name="quantity_in_stock" value="{{ old('quantity_in_stock', $material->quantity_in_stock ?? '') }}" placeholder="Enter quantity" required>
                            <label for="quantity_in_stock">Quantity</label>
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
                            <label for="document" class="form-label">Upload Document (PDF, PNG, JPG)</label>
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
                            <a href="{{ route('materials.index') }}" class="btn btn-dark">Back to Materials</a>
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

        // Update "Price per Unit" label dynamically
        $('#bom_item_id').on('change', function () {
            var selectedOption = $(this).find('option:selected');
            var unit = selectedOption.data('unit') || 'Unit';
            $('#unit_price_label').text('Price per ' + unit);
        });
    });
</script>
@endpush
