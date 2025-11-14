@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="row mb-3 align-items-center">
            <div class="col">
                <h2 class="fw-bold m-0" style="color:#027333">BoM Section: <span class="text-dark">{{ $bqSection->name }}</span></h2>
            </div>
            <div class="col-auto d-flex gap-2">
                @if(isset($bqDocumentForSection) && $bqDocumentForSection && $bqSection->bq_level_id)
                    <a href="{{ route('bq_levels.show', [$bqDocumentForSection, $bqSection->bq_level_id]) }}" class="btn btn-outline-primary btn-sm">View BoQ Level</a>
                @endif
                <a href="{{ route('boms.index') }}" class="btn btn-outline-secondary btn-sm">Back to Sections</a>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-12">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-semibold" style="color:#027333">Materials Summary</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col" class="text-nowrap">{{ __('Product / Material') }}</th>
                                        <th scope="col" class="text-end">{{ __('Quantity') }}</th>
                                        <th scope="col" class="text-nowrap">{{ __('Unit') }}</th>
                                        <th scope="col" class="text-end">{{ __('Rate (KES)') }}</th>
                                        <th scope="col" class="text-end">{{ __('Amount (KES)') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($items as $item)
                                        <tr>
                                            <td class="px-2">{{ $item->product->name ?? ($item->item_material->name ?? '') }}</td>
                                            <td class="text-end">{{ number_format($item->total_quantity, 2) }}</td>
                                            <td class="text-nowrap">{{ $item->unit ?? ($item->product->unit ?? ($item->item_material->unit_of_measurement ?? '')) }}</td>
                                            <td class="text-end">{{ number_format($item->rate, 2) }}</td>
                                            <td class="text-end">{{ number_format($item->total_quantity * $item->rate, 2) }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted py-4">{{ __('No items found.') }}</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                                @php
                                    $totalAmount = 0;
                                    foreach($items as $item){
                                        $totalAmount += $item->total_quantity * $item->rate;
                                    }
                                @endphp
                                <tfoot>
                                    <tr class="table-secondary">
                                        <th class="text-uppercase small">{{ __('Total') }}</th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th class="text-end fw-bold">KES {{ number_format($totalAmount, 2) }}</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-semibold" style="color:#027333">{{ __('Labour Summary') }}</span>
                        </div>
                    </div>
                    <div class="card-body">
                        @include('boms.labours')
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        let maxQty = 0;
        let unit = 'unit';
        const qtyInput = document.getElementById('quantity_requested');
        const materialSelect = document.getElementById('bom_item_id');

        function handleQtyInput(e) {
            const value = parseFloat(e.target.value);
            if (value > maxQty) {
                e.target.value = '';
                Swal.fire({
                    icon: 'warning',
                    title: 'Quantity Too High',
                    text: `Limit is (${maxQty} ${unit}).`,
                    confirmButtonColor: '#027333'
                });
            }
        }

        if (materialSelect) {
            materialSelect.addEventListener('change', function () {
                const selected = materialSelect.options[materialSelect.selectedIndex];
                maxQty = parseFloat(selected.dataset.max) || 0;
                unit = selected.dataset.unit || 'unit';

                qtyInput.setAttribute('max', maxQty);
                qtyInput.setAttribute('placeholder', `Max: ${maxQty} ${unit}`);
                qtyInput.value = '';

                qtyInput.removeEventListener('input', handleQtyInput);
                qtyInput.addEventListener('input', handleQtyInput);
            });
        }
    });
</script>
@endpush
