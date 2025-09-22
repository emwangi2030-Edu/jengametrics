@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Add Material</h1>
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form action="{{ route('products.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="name">Material</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <div class="form-group">
            <label for="name">Select Unit</label>
            <select class="form-control" name="unit" id="unit">
                @foreach($units as $unit)
                    <option value="{{ $unit->abbrev }}">{{ $unit->abbrev }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary mt-3">Add</button>
        <a href="{{ route('products.index') }}" class="btn btn-secondary mt-3">Back</a>
    </form>
</div>
@endsection
