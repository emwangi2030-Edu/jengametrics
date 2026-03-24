@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="jm-page-header">
        <div>
            <h2 class="jm-page-title jm-ui-title">{{ __('Costs:') }} <span class="jm-ui-muted">{{ $project->name }}</span></h2>
            <p class="jm-page-subtitle jm-ui-muted mb-0">{{ __('Material and labour spend overview.') }}</p>
        </div>
    </div>
    
    <div class="card jm-ui-card shadow-sm border-0">
        <h3 class="jm-section-title mt-3 mx-3">{{ __('Material Costs') }}</h3>
        <div class="card-body">
            <div class="table-responsive jm-ui-table-wrap">
                <table class="table text-center align-middle mb-0">
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

    <div class="card jm-ui-card shadow-sm border-0 mt-4">
        <h3 class="jm-section-title mt-3 mx-3">{{ __('Labour Costs') }}</h3>
        <div class="card-body">
            <div class="table-responsive jm-ui-table-wrap">
                <table class="table text-center align-middle mb-0">
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
                            <td>
                                @php $payee = $payment->worker; @endphp
                                {{ $payee->full_name ?? __('Unknown Worker') }}
                                @if($payee && $payee->trashed())
                                    <span class="badge bg-secondary ms-1">{{ __('Terminated') }}</span>
                                @endif
                            </td>
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
