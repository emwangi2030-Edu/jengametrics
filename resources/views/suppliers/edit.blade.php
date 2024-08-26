@extends('layouts.appbar')

@section('content')
    <h2>Edit Supplier</h2>

    <form method="POST" action="{{ route('suppliers.update', $supplier->id) }}">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" name="name" class="form-control" value="{{ $supplier->name }}" required>
        </div>

        <div class="form-group">
            <label for="contact_info">Contact Info</label>
            <input type="text" name="contact_info" class="form-control" value="{{ $supplier->contact_info }}">
        </div>

        <div class="form-group">
            <label for="address">Address</label>
            <input type="text" name="address" class="form-control" value="{{ $supplier->address }}">
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('suppliers.index') }}" class="btn btn-secondary">Back</a>
    </form>
@endsection
