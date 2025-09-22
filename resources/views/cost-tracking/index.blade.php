@extends('layouts.app')

@section('content')
<div class="container py-4">

    <h2 class="font-weight-bold mb-3" style="color:#027333">
        Costs: <span class="text-black">{{ $project->name }}</spanclass>
    </h2>
    
    <div class="card shadow">
        <h3 class="mt-3 mx-3" style="color:#027333">Material Costs</h3>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table text-center align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Material Name</th>
                            <th>Unit of Measure</th>
                            <th>Price per Unit (KES)</th>
                            <th>Quantity Purchased</th>
                            <th>Total Cost (KES)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($materials as $material)
                        <tr>
                            <td>{{ $material->name }}</td>
                            <td>{{ $material->unit_of_measure }}</td>
                            <td>{{ number_format($material->unit_price, 2) }}</td>
                            <td>{{ $material->quantity_purchased }}</td>
                            <td>{{ number_format($material->unit_price * $material->quantity_purchased, 2) }}</td>
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

    <div class="card shadow mt-4">
        <h3 class="mt-3 mx-3" style="color:#027333">Labour Costs</h3>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table text-center align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Payee</th>
                            <th>Payment Date</th>
                            <th>Amount (KES)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($payments as $payment)
                        <tr>
                            <td>{{ $payment->worker->full_name }}</td>
                            <td>{{ \Carbon\Carbon::parse($payment->payment_date)->format('d M Y') }}</td>
                            <td>{{ number_format($payment->amount, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="text-end mt-4">
                <h5>Total Labour Cost: <span class="text-success">KES {{ number_format($totalPayments, 2) }}</span></h5>
            </div>
        </div>
    </div>    
</div>
@endsection
