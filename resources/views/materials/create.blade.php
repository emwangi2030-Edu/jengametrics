@extends('layouts.appbar')

@section('content')
<div class="container">
    <h1>Add New Material</h1>
    <form action="{{ route('materials.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @if(isset($material))
            @method('PUT')
        @endif

        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $material->name ?? '') }}" required>
        </div>

        <div class="form-group">
            <label for="unit_of_measure">Unit of Measurement</label>
            <select class="form-control" id="unit_of_measure" name="unit_of_measure" required>
                <option value="" disabled selected>Select Unit</option>
                <option value="Cubic Meter" {{ old('unit_of_measure', $material->unit_of_measure ?? '') == 'Cubic Meter' ? 'selected' : '' }}>Cubic Meter</option>
                <option value="Square Meter" {{ old('unit_of_measure', $material->unit_of_measure ?? '') == 'Square Meter' ? 'selected' : '' }}>Square Meter</option>
                <option value="Square Foot" {{ old('unit_of_measure', $material->unit_of_measure ?? '') == 'Square Foot' ? 'selected' : '' }}>Square Foot</option>
                <option value="Meter" {{ old('unit_of_measure', $material->unit_of_measure ?? '') == 'Meter' ? 'selected' : '' }}>Meter</option>
                <option value="Inch" {{ old('unit_of_measure', $material->unit_of_measure ?? '') == 'Inch' ? 'selected' : '' }}>Inch</option>
                <option value="Millimeter" {{ old('unit_of_measure', $material->unit_of_measure ?? '') == 'Millimeter' ? 'selected' : '' }}>Millimeter</option>
                <option value="Ton" {{ old('unit_of_measure', $material->unit_of_measure ?? '') == 'Ton' ? 'selected' : '' }}>Ton</option>
                <option value="Kilogram" {{ old('unit_of_measure', $material->unit_of_measure ?? '') == 'Kilogram' ? 'selected' : '' }}>Kilogram</option>
                <option value="Bag" {{ old('unit_of_measure', $material->unit_of_measure ?? '') == 'Bag' ? 'selected' : '' }}>Bag</option>
                <option value="Piece" {{ old('unit_of_measure', $material->unit_of_measure ?? '') == 'Piece' ? 'selected' : '' }}>Piece</option>
                <option value="Foot" {{ old('unit_of_measure', $material->unit_of_measure ?? '') == 'Foot' ? 'selected' : '' }}>Foot</option>
                <option value="Centimeter" {{ old('unit_of_measure', $material->unit_of_measure ?? '') == 'Centimeter' ? 'selected' : '' }}>Centimeter</option>
                <option value="Litre" {{ old('unit_of_measure', $material->unit_of_measure ?? '') == 'Litre' ? 'selected' : '' }}>Litre</option>
                <option value="Roll" {{ old('unit_of_measure', $material->unit_of_measure ?? '') == 'Roll' ? 'selected' : '' }}>Roll</option>
                <option value="Packet" {{ old('unit_of_measure', $material->unit_of_measure ?? '') == 'Packet' ? 'selected' : '' }}>Packet</option>
                <option value="carton" {{ old('unit_of_measure', $material->unit_of_measure ?? '') == 'Carton' ? 'selected' : '' }}>Carton</option>
                <option value="Bucket" {{ old('unit_of_measure', $material->unit_of_measure ?? '') == 'Bucket' ? 'selected' : '' }}>Bucket</option>
                <option value="Bundle" {{ old('unit_of_measure', $material->unit_of_measure ?? '') == 'Bundle' ? 'selected' : '' }}>Bundle</option>
                <option value="Box" {{ old('unit_of_measure', $material->unit_of_measure ?? '') == 'Box' ? 'selected' : '' }}>Box</option>
                <option value="Bale" {{ old('unit_of_measure', $material->unit_of_measure ?? '') == 'Bale' ? 'selected' : '' }}>Bale</option>
                <option value="Gallon" {{ old('unit_of_measure', $material->unit_of_measure ?? '') == 'Gallon' ? 'selected' : '' }}>Gallon</option>
                <option value="Ream" {{ old('unit_of_measure', $material->unit_of_measure ?? '') == 'Ream' ? 'selected' : '' }}>Ream</option>
                <option value="Sheet" {{ old('unit_of_measure', $material->unit_of_measure ?? '') == 'Sheet' ? 'selected' : '' }}>Sheet</option>
            </select>
        </div>

        <div class="form-group">
            <label for="unit_price">Price per Unit</label>
            <input type="text" class="form-control" id="unit_price" name="unit_price" value="{{ old('unit_price', $material->unit_price ?? '') }}" required>
        </div>

        <div class="form-group">
            <label for="quantity_in_stock">Quantity</label>
            <input type="number" class="form-control" id="quantity_in_stock" name="quantity_in_stock" value="{{ old('quantity_in_stock', $material->quantity_in_stock ?? '') }}" required>
        </div>

        <div class="form-group">
            <label for="supplier_name">Supplier Name</label>
            <div class="input-group">
                <select class="form-control" id="supplier_name" name="supplier_name" required>
                    <option value="" disabled selected>Select Supplier</option>
                    @foreach($suppliers as $supplier)
                        <option value="{{ $supplier->id }}" data-contact="{{ $supplier->contact_info }}">{{ $supplier->name }}</option>
                    @endforeach
                </select>
                <div class="input-group-append">
                    <!-- Add New Supplier Button -->
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addSupplierModal">
                        Add New Supplier
                    </button>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="supplier_contact">Supplier Contact</label>
            <input type="text" class="form-control" id="supplier_contact" name="supplier_contact" readonly>
        </div>

        <div class="form-group">
            <label for="document">Documents</label>
            <input type="file" name="document" id="document" class="form-control" placeholder="PDFs, JPEGs, PNGs etc...">
        </div>

        <button type="submit" class="btn btn-success my-4">{{ isset($material) ? 'Update Material' : 'Add Material' }}</button>
        <a href="{{ route('materials.index') }}"><button type="button" class="btn btn-secondary">Back to Materials</button></a>
    </form>
</div>

<!-- Modal for Adding New Supplier -->
<div class="modal fade" id="addSupplierModal" tabindex="-1" role="dialog" aria-labelledby="addSupplierModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addSupplierModalLabel">Add New Supplier</h5>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="new_supplier_name">Enter New Supplier Name</label>
                    <input type="text" class="form-control" id="new_supplier_name">
                </div>
                <div class="form-group">
                    <label for="new_supplier_contact">Enter New Supplier Contact</label>
                    <input type="text" class="form-control" id="new_supplier_contact">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="saveNewSupplier">Save Supplier</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
    $(document).ready(function() {
        // Populate supplier contact when supplier is selected
        $('#supplier_name').on('change', function() {
            var selectedOption = $(this).find('option:selected');
            var contact = selectedOption.data('contact');
            $('#supplier_contact').val(contact); // Populate supplier contact
        });

        // Save new supplier using AJAX
        $('#saveNewSupplier').on('click', function() {
            var supplierName = $('#new_supplier_name').val();
            var supplierContact = $('#new_supplier_contact').val();

            if (supplierName && supplierContact) {
                $.ajax({
                    url: "{{ route('suppliers.ajaxStore') }}", // URL to send the request
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}', // CSRF token for security
                        name: supplierName,
                        contact_info: supplierContact
                    },
                    success: function(response) {
                        if(response.id && response.name) {
                            // Add the new supplier to the dropdown
                            var newOption = $('<option>', {
                                value: response.id, // The newly created supplier's ID
                                text: response.name,
                                'data-contact': response.contact_info
                            });

                            // Append the new supplier to the dropdown
                            $('#supplier_name').append(newOption);

                            // Select the newly added supplier
                            newOption.prop('selected', true);

                            // Populate the contact field with the new contact info
                            $('#supplier_contact').val(response.contact_info);

                            // Close the modal
                            $('#addSupplierModal').modal('hide');

                            // Clear modal inputs
                            $('#new_supplier_name').val('');
                            $('#new_supplier_contact').val('');
                        } else {
                            alert('Error: Supplier could not be added.');
                        }
                    },
                    error: function(xhr, status, error) {
                        // Log the error details
                        console.log('AJAX Error:', xhr.responseText);

                        // Display an alert with the error message
                        alert('Failed to add new supplier: ' + xhr.responseText);
                    }
                });
            } else {
                alert('Please fill in both fields.');
            }
        });
    });
</script>
@endpush
