@extends('layouts.appbar')

@section('content')
    <div class="container mt-4">
        <h2>Add New Supplier</h2>
        
        <form method="POST" action="{{ route('suppliers.store') }}">
            @csrf
            
            <div class="form-group">
                <label for="name">Supplier Name</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>

            <div class="form-group">
                <label for="contact_info">Contact Information</label>
                <input type="text" class="form-control" id="contact_info" name="contact_info">
            </div>

            <div class="form-group">
                <label for="address">Address</label>
                <input type="text" class="form-control" id="address" name="address">
            </div>

            <button type="submit" class="btn btn-primary">Add Supplier</button>
            <a href="{{ route('suppliers.index') }}" class="btn btn-secondary">Back</a>
        </form>
    </div>
@endsection
