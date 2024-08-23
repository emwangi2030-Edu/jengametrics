@extends('layouts.appbar')

@section('content')
    <h2>Supplier Details</h2>

    <div class="card">
        <div class="card-body">
            <h4>{{ $supplier->name }}</h4>
            <p><strong>Contact Info:</strong> {{ $supplier->contact_info }}</p>
            <p><strong>Address:</strong> {{ $supplier->address }}</p>
        </div>
    </div>

    <a href="{{ route('suppliers.index') }}" class="btn btn-primary mt-4">Back to Suppliers</a>
    <a href="{{ route('suppliers.edit', $supplier->id) }}" class="btn btn-secondary mt-4">Edit</a>

    <form action="{{ route('suppliers.destroy', $supplier->id) }}" method="POST" style="display:inline-block;">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger mt-4" onclick="return confirm('Are you sure you want to delete this supplier?')">Delete</button>
    </form>

@endsection
