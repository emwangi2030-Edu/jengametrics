@extends('layouts.appbar')

@section('content')
<div class="container">
    <h1>{{ isset($material) ? 'Edit Material' : 'Add New Material' }}</h1>
    <form action="{{ isset($material) ? route('materials.update', $material->id) : route('materials.store') }}" method="POST">
        @csrf
        @if(isset($material))
            @method('PUT')
        @endif

        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $material->name ?? '') }}" required>
        </div>

        <div class="form-group">
            <label for="unit_price">Price per Unit</label>
            <input type="text" class="form-control" id="unit_price" name="unit_price" value="{{ old('unit_price', $material->unit_price ?? '') }}" required>
        </div>

        <div class="form-group">
            <label for="unit_of_measure">Unit of Measurement</label>
            <input type="text" class="form-control" id="unit_of_measure" name="unit_of_measure" value="{{ old('unit_of_measure', $material->unit_of_measure ?? '') }}" required>
        </div>

        <div class="form-group">
            <label for="quantity_in_stock">Quantity in Stock</label>
            <input type="number" class="form-control" id="quantity_in_stock" name="quantity_in_stock" value="{{ old('quantity_in_stock', $material->quantity_in_stock ?? '') }}" required>
        </div>

        <div class="form-group">
            <label for="supplier_id">Supplier</label>
            <select id="supplier_id" name="supplier_id" class="form-control">
                @foreach ($suppliers as $supplier)
                    <option value="{{ $supplier->id }}" {{ $material->supplier_id == $supplier->id ? 'selected' : '' }}>
                        {{ $supplier->name }}
                    </option>
                @endforeach
            </select>
        </div>


        <button type="submit" class="btn btn-success">{{ isset($material) ? 'Update Material' : 'Add Material' }}</button>
    </form>
</div>
@endsection
@push('scripts')
<script>
    $(document).ready(function() {
        $('#supplier_name').autocomplete({
            source: function(request, response) {
                $.ajax({
                    url: "{{ route('suppliers.autocomplete') }}",
                    data: {
                        term: request.term
                    },
                    success: function(data) {
                        response(data);
                    }
                });
            },
            minLength: 2,
        });
    });
</script>
@endpush
