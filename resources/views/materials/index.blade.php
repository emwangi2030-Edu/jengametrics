@extends('layouts.appbar')

@section('content')
<div class="container">
    @if(session('success'))
        <div class="alert alert-success" id="success-alert">
            {{ session('success') }}
        </div>
    @endif
    <h1>Materials</h1>

    <a href="{{ route('materials.create') }}" class="btn btn-primary mb-3">Add New Material</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Name</th>
                <th>Unit Price</th>
                <th>Unit of Measure</th>
                <th>Amount Purchased</th>
                <th>Supplier Name</th>
                <th>Date Added</th>
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
                    <td>{{ $material->supplier->name ?? 'N/A' }}</td>
                    <td>{{ $material->created_at->format('Y-m-d') }}</td>
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
        // Hide the alert after 5 seconds with a fade-out effect
        setTimeout(function() {
            $('#success-alert').fadeOut('slow');
        }, 5000);
    });
</script>
@endpush
