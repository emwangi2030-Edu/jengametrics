@php
    $formatNumeric = function ($value) {
        if ($value === null) {
            return null;
        }

        $numeric = round((float) $value, 2);

        return abs($numeric - round($numeric)) < 0.005
            ? (int) round($numeric)
            : number_format($numeric, 2);
    };
@endphp

<div class="table-responsive jm-ui-table-wrap">
    <table class="table table-bordered text-center align-middle mb-0">
        <thead class="table-light">
            <tr>
                <th>{{ __('Name') }}</th>
                <th>{{ __('Requisitioned Quantity') }}</th>
                <th>{{ __('Quantity Received') }}</th>
                <th>{{ __('Variance') }}</th>
                <th>{{ __('UoM') }}</th>
                <th>{{ __('Unit Price') }}</th>
                <th>{{ __('Total Amount') }}</th>
                <th>{{ __('Supplier Name') }}</th>
                <th>{{ __('Date') }}</th>
                <th>{{ __('Receipt') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse($materials as $material)
                @php
                    $requisitionedQty = $material->requisitioned_quantity;
                    if ($requisitionedQty === null && $material->variance !== null && $material->quantity_purchased !== null) {
                        $requisitionedQty = (float) $material->quantity_purchased - (float) $material->variance;
                    }
                    $variance = (float) ($material->variance ?? 0);
                    $displayRequisitioned = $requisitionedQty !== null ? $formatNumeric($requisitionedQty) : null;
                    $displayQuantity = $formatNumeric($material->quantity_purchased);
                    $displayVariance = $formatNumeric($variance);
                @endphp
                <tr>
                    <td><div class="px-2">{{ $material->product->name ?? $material->name }}</div></td>
                    <td>{{ $displayRequisitioned !== null ? $displayRequisitioned : 'N/A' }}</td>
                    <td>{{ $displayQuantity }}</td>
                    <td class="{{ $variance > 0 ? 'text-success' : ($variance < 0 ? 'text-danger' : 'text-secondary') }}">
                        {{ $variance > 0 ? '+' : '' }}{{ $displayVariance }}
                    </td>
                    <td>{{ $material->unit_of_measure }}</td>
                    <td>{{ number_format($material->unit_price, 2) }}</td>
                    <td>{{ number_format($material->unit_price * $material->quantity_purchased, 2) }}</td>
                    <td>{{ $material->supplier->name }}</td>
                    <td>{{ $material->created_at->format('d-m-Y') }}</td>
                    <td>
                        @if($material->document)
                            <a href="{{ route('materials.viewDocument', $material->id) }}" class="text-decoration-underline">
                                {{ __('View') }}
                            </a>
                        @else
                            <span class="text-muted">None</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" class="text-center py-4">{{ __('No materials found.') }}</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
