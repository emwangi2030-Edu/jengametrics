@extends('layouts.appbar')

@section('content')
<div class="container py-4">

    <h2 class="font-weight-bold mb-3" style="color:#027333">
        Material Costs: <span class="text-black">{{ $project->name }}</spanclass>
    </h2>
    
    <div class="card shadow">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Material Name</th>
                            <th>Unit of Measure</th>
                            <th>Price per Unit (KES)</th>
                            <th>Quantity in Stock</th>
                            <th>Total Cost (KES)</th>
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
            </div>

            <div class="text-end mt-4">
                <h5>Total Material Cost: <span class="text-success">KES {{ number_format($totalCost, 2) }}</span></h5>
            </div>
        </div>
    </div>
</div>
@endsection
