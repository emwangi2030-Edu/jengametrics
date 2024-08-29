@extends('layouts.appbar')

@section('content')
<div class="container">
    @if(session('success'))
        <div class="alert alert-success" id="success-alert" style="display: block;">
            {{ session('success') }}
        </div>
    @endif
    
    <h1>Materials Purchased</h1>

    <a href="{{ route('materials.create') }}" class="btn btn-primary mb-3">Add New Material</a>
    <a href="{{ route('suppliers.index') }}" class="btn btn-secondary mb-3">Suppliers List</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Name</th>
                <th>Unit Price</th>
                <th>Unit of Measure</th>
                <th>Amount Purchased</th>
                <th>Total Amount</th>
                <th>Supplier Name</th>
                <th>Date Added</th>
                <th>Documents</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($materials as $material)
                <tr>
                    <td>{{ $material->name }}</td>
                    <td>{{ $material->unit_price }}</td>
                    <td>{{ $material->unit_of_measure }}</td>
                    <td>{{ $material->quantity_in_stock }}</td>
                    <td>{{ number_format($material->unit_price * $material->quantity_in_stock, 2) }}</td>
                    <td>{{ $material->supplier->name ?? 'N/A' }}</td>
                    <td>{{ $material->created_at->format('Y-m-d') }}</td>
                    <td>
                        @if($material->document)
                            <a href="{{ route('materials.viewDocument', $material->id) }}">View Document</a>
                        @else
                            None
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('materials.edit', $material->id) }}" class="btn btn-primary btn-sm">Edit</a>
                        <form action="{{ route('materials.destroy', $material->id) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        setTimeout(function() {
            if ($('#success-alert').length) {
                console.log('Success alert found. It will fade out in 4 seconds.');
                setTimeout(function() {
                    $('#success-alert').fadeOut('slow');
                }, 4000);
            } else {
                console.log('No success alert found.');
            }
        }, 1000); // Add a slight delay before checking for the alert
    });
</script>
@endpush

