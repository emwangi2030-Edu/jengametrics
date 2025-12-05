<div class="table-responsive">
    <table class="table table-sm align-middle">
        <thead class="table-light">
            <tr>
                <th>{{ __('Payee') }}</th>
                <th>{{ __('Payment Date') }}</th>
                <th>{{ __('Amount (KES)') }}</th>
                <th>{{ __('Period Start') }}</th>
                <th>{{ __('Period End') }}</th>
                <th>{{ __('Status') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse($payments as $payment)
                @php
                    $worker = $payment->worker;
                    $isTerminated = $worker && ($worker->terminated || $worker->trashed());
                @endphp
                <tr>
                    <td>
                        {{ $worker->full_name ?? __('Unknown') }}
                        @if($isTerminated)
                            <span class="badge bg-secondary ms-1">{{ __('Terminated') }}</span>
                        @endif
                    </td>
                    <td>{{ $payment->payment_date ? $payment->payment_date->format('d M Y') : __('N/A') }}</td>
                    <td>{{ number_format((float) $payment->amount, 2) }}</td>
                    <td>{{ $payment->period_start ? $payment->period_start->format('d M Y') : __('N/A') }}</td>
                    <td>{{ $payment->period_end ? $payment->period_end->format('d M Y') : __('N/A') }}</td>
                    <td>{{ $isTerminated ? __('Terminated') : __('Active') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center text-muted py-4">
                        {{ __('No payments found.') }}
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
