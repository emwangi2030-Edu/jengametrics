@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="card jm-ui-card shadow-sm border-0 p-4">
            <div class="card-body">
                <h2 class="jm-page-title jm-ui-title">{{ __('Payment History for :name', ['name' => $worker->full_name]) }}</h2>

                <a href="{{ route('workers.show', $worker->id) }}" class="btn btn-outline-secondary mb-3">
                    Worker Profile
                </a>

                @if($payments->isEmpty())
                    <p class="text-center mt-4 text-muted">No payments recorded yet.</p>
                @else
                    <div class="table-responsive jm-ui-table-wrap">
                        <table class="table text-center align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th></th>
                                    <th>Amount Paid</th>
                                    <th>Payment Date</th>
                                    <th>Recorded At</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($payments as $payment)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>Ksh {{ number_format($payment->amount, 2) }}</td>
                                        <td>{{ \Carbon\Carbon::parse($payment->payment_date)->format('M d, Y') }}</td>
                                        <td>{{ $payment->created_at->format('M d, Y H:i') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
