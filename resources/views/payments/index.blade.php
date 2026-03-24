@extends('layouts.app')

@section('content')
    <div class="card shadow-sm mt-4 p-4">
        <div class="card-body">
            <h2 class="jm-page-title">{{ __('Payment History for :name', ['name' => $worker->full_name]) }}</h2>

            <a href="{{ route('workers.show', $worker->id) }}" class="btn btn-secondary mb-3">
                Worker Profile
            </a>

            @if($payments->isEmpty())
                <p class="text-center mt-4 text-muted">No payments recorded yet.</p>
            @else
                <table class="table text-center">
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
            @endif
        </div>
    </div>
@endsection
