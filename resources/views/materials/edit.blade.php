@extends('layouts.appbar')

@section('content')
<div class="container">
    <h1>Edit Material</h1>
    <form action="{{ route('materials.update', $material->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

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
            <label for="quantity_in_stock">Amount Purchased</label>
            <input type="number" class="form-control" id="quantity_in_stock" name="quantity_in_stock" value="{{ old('quantity_in_stock', $material->quantity_in_stock ?? '') }}" required>
        </div>

        <div class="form-group">
            <label for="supplier_name">Supplier Name</label>
            <input type="text" class="form-control" id="supplier_name" name="supplier_name" value="{{ old('supplier_name', $material->supplier->name ?? '') }}" required>
        </div>

        <div class="form-group">
            <label for="supplier_contact">Supplier Contact</label>
            <input type="text" class="form-control" id="supplier_contact" name="supplier_contact" value="{{ old('supplier_contact', $material->supplier->contact_info ?? '') }}" required>
        </div>

        <!-- Read-Only Supplier ID Field -->
        <input type="hidden" id="supplier_id" name="supplier_id" value="{{ old('supplier_id', $material->supplier_id ?? '') }}">

        <div class="form-group">
            <label for="document">Document</label>
            <input type="file" class="form-control" id="document" name="document">
            @if(isset($material) && $material->document)
                <a href="{{ route('materials.viewDocument', $material->id) }}"><button type="button" class="btn btn-primary my-4">View Current Document</button></a>
            @endif
        </div>
        <a href="{{ route('materials.index') }}"><button type="button" class="btn btn-secondary">Back to Materials</button></a>
        <button type="submit" class="btn btn-success">Update Material</button>
    </form>
</div>

<!-- Modals -->

<div class="modal fade" id="noDocumentModal" tabindex="-1" role="dialog" aria-labelledby="noDocumentModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="noDocumentModalLabel">Notification</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                No Document Uploaded.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
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
                        response($.map(data, function(item) {
                            return {
                                label: item.name,
                                value: item.name,
                                id: item.id
                            };
                        }));
                    }
                });
            },
            minLength: 2,
            select: function(event, ui) {
                $('#supplier_id').val(ui.item.id);
            }
        });
    });
</script>
@endpush
