<div class="table-responsive">
    <table class="table table-sm table-hover align-middle mb-0">
        <thead class="table-light">
            <tr>
                <th scope="col">{{ __('Description') }}</th>
                <th scope="col" class="text-end">{{ __('No. of Labourers') }}</th>
                <th scope="col" class="text-end">{{ __('Rate') }}</th>
                <th scope="col" class="text-end">{{ __('Amount') }}</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalQuantity = 0;
                $totalAmount = 0;
            @endphp
            @forelse ($labours as $item)
                @php
                    $qty = (float) ($item->quantity ?? 0);
                    $amt = (float) ($item->amount ?? 0);
                    $totalQuantity += $qty;
                    $totalAmount += $amt;
                @endphp
                <tr>
                    <td class="px-2">{{ optional($item->item)->name ?? __('Labour') }}</td>
                    <td class="text-end">{{ $qty }}</td>
                    <td class="text-end">{{ number_format((float) ($item->rate ?? 0), 2) }}</td>
                    <td class="text-end">{{ number_format($amt, 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center text-muted py-4">{{ __('No items found.') }}</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr class="table-secondary">
                <th class="text-uppercase small">{{ __('Total') }}</th>
                <td class="text-end fw-bold">{{ $totalQuantity }}</td>
                <td></td>
                <td class="text-end fw-bold">{{ number_format($totalAmount, 2) }}</td>
            </tr>
        </tfoot>
    </table>
</div>
