@extends('layouts.appbar')

@section('content')
    <div class="container">
        <h2>{{ $material->name }}</h2>
        <p><strong>Description:</strong> {{ $material->description }}</p>
        <p><strong>Unit Price:</strong> {{ $material->unit_price }}</p>
        <p><strong>Unit of Measure:</strong> {{ $material->unit_of_measure }}</p>
        <p><strong>Quantity in Stock:</strong> {{ $material->quantity_in_stock }}</p>
        <p><strong>Supplier:</strong> {{ $material->supplier->name }}</p>

        <a href="{{ route('materials.index') }}" class="btn btn-primary">Back to List</a>
    </div>
@endsection
