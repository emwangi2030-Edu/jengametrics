<div class="table-responsive">
    <table class="table table-sm align-middle">
        <thead class="table-light">
            <tr>
                <th>{{ __('Material') }}</th>
                <th>{{ __('Supplier') }}</th>
                <th>{{ __('Unit') }}</th>
                <th class="text-end">{{ __('Unit Price (KES)') }}</th>
                <th class="text-end">{{ __('Quantity Purchased') }}</th>
                <th class="text-end">{{ __('Total Cost (KES)') }}</th>
                <th>{{ __('Date Purchased') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse($materials as $material)
                <tr>
                    <td>{{ $material->name }}</td>
                    <td>{{ $material->supplier->name ?? __('N/A') }}</td>
                    <td>{{ $material->unit_of_measure ?? 'N/A' }}</td>
                    <td class="text-end">{{ number_format((float) $material->unit_price, 2) }}</td>
                    <td class="text-end">{{ number_format((float) $material->quantity_purchased, 2) }}</td>
                    <td class="text-end">
                        {{ number_format((float) $material->unit_price * (float) $material->quantity_purchased, 2) }}
                    </td>
                    <td>{{ optional($material->created_at)->format('d M Y') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center text-muted py-4">
                        {{ __('No purchases found.') }}
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
