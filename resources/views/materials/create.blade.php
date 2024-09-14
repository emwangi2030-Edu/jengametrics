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
            <select class="form-control" id="supplier_name" name="supplier_name" required>
                <option value="" disabled selected>Select Supplier</option>
                @foreach($suppliers as $supplier)
                    <option value="{{ $supplier->id }}" data-contact="{{ $supplier->contact_info }}">{{ $supplier->name }}</option>
                @endforeach
            </select>
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
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Populate supplier contact when supplier is selected
        $('#supplier_name').on('change', function() {
            var selectedOption = $(this).find('option:selected');
            var contact = selectedOption.data('contact');
            $('#supplier_contact').val(contact); // Populate supplier contact
        });
    });
</script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

@endpush
