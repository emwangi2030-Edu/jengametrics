@extends('layouts.appbar')

@section('content')
<div class="container">
    <h1>Material Costs</h1>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Material Name</th>
                <th>Unit of Measure</th>
                <th>Price per Unit</th>
                <th>Quantity in Stock</th>
                <th>Total Cost</th>
            </tr>
        </thead>
        <tbody>
            @foreach($materials as $material)
            <tr>
                <td>{{ $material->name }}</td>
                <td>{{ $material->unit_of_measure }}</td>
                <td>{{ number_format($material->unit_price, 2) }}</td>
                <td>{{ $material->quantity_in_stock }}</td>
                <td>{{ number_format($material->unit_price * $material->quantity_in_stock, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <h3>Total Material Cost: KES {{ number_format($totalCost, 2) }}</h3>
</div>
@endsection
