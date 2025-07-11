@extends('layouts.appbar')

@section('content')
    <div class="container py-4">
        <div class="row mb-4">
            <div class="col-12">
                <h2 class="font-weight-bold" style="color:#027333">
                    Section: <span class="text-black">{{ $bqSection->name }}</span>
                </h2>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-body">
                        <!-- Table to display items -->
                        <h3 class="text-lg font-weight-bold mt-6">{{ __('Items List') }}</h3>
                        <table class="table mt-4">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col">{{ __('Description') }}</th>
                                    <th scope="col">{{ __('Quantity') }}</th>
                                    <th scope="col">{{ __('Unit') }}</th>
                                    <th scope="col">{{ __('Rate') }}</th>
                                    <th scope="col">{{ __('Amount') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                               @forelse ($items as $item)
                                    <tr>
                                        <td>{{ $item->item_material->name ?? '' }}</td>
                                        <td>{{ $item->total_quantity }}</td>
                                        <td>{{ $item->item_material->unit_of_measurement ?? '' }}</td>
                                        <td>{{ number_format($item->rate, 2) }}</td>
                                        <td>{{ number_format($item->total_quantity * $item->rate, 2) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">{{ __('No items found.') }}</td>
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
                                <tr>
                                    <th colspan="1">{{ __('Total') }}</th>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td><b>{{ number_format($totalAmount, 2) }}</b></td>
                                </tr>
                            </tfoot>
                        </table>
                        <!-- Requisition Buttons -->
                        <div class="d-flex gap-2 mt-3">
                            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#requisitionModal">
                                Requisition Material
                            </button>

                            <a href="{{ route('requisitions.index', ['section_id' => $bqSection->id]) }}" class="btn btn-secondary">
                                Requisition List
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @include('boms.labours')
        @include('requisitions.requisition_modal')
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

