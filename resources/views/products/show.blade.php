@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Material Details</h1>
    <p><strong>ID:</strong> {{ $product->id }}</p>
    <p><strong>Name:</strong> {{ $product->name }}</p>
    <a href="{{ route('products.index') }}" class="btn btn-secondary">Back to List of Materials</a>
</div>
@endsection
