<div class="table-responsive jm-ui-table-wrap">
    <table class="table table-hover align-middle mb-0">
        <thead class="table-light">
            <tr>
                <th scope="col">{{ __('Section') }}</th>
                <th scope="col">{{ __('Name') }}</th>
                <th scope="col">{{ __('Unit') }}</th>
                <th scope="col" class="text-end">{{ __('Quantity') }}</th>
                <th scope="col" class="text-end">{{ __('Rate') }}</th>
                <th scope="col" class="text-end">{{ __('Amount') }}</th>
                <th scope="col" class="text-center">{{ __('Actions') }}</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalQuantity = 0;
                $totalAmount = 0;
            @endphp
            @forelse ($items as $item)
                @php
                    $totalQuantity += (float) ($item->quantity ?? 0);
                    $totalAmount += (float) ($item->amount ?? 0);
                @endphp
                <tr>
                    <td>{{ optional($item->section)->name ?? __('Unassigned') }}</td>
                    <td>{{ $item->item_name }}</td>
                    <td>{{ $item->units }}</td>
                    <td class="text-end">{{ number_format((float) $item->quantity, 2) }}</td>
                    <td class="text-end">{{ number_format((float) $item->rate, 2) }}</td>
                    <td class="text-end">{{ number_format((float) $item->amount, 2) }}</td>
                    <td>
                        <div class="d-flex gap-2 justify-content-center">
                            <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editItemModal{{ $item->id }}">
                                {{ __('Edit') }}
                            </button>
                            @include('bq_sections.modals.edit_item')

                            <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteItemModal{{ $item->id }}">
                                {{ __('Delete') }}
                            </button>
                            @include('bq_sections.modals.delete_item')
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">{{ __('No items found.') }}</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr class="bg-secondary bg-opacity-10 border-0 rounded">
                <th>{{ __('Total') }}</th>
                <td colspan="4"></td>
                <td class="fw-bold text-end">{{ number_format($totalAmount, 2) }}</td>
                <td></td>
            </tr>
        </tfoot>
    </table>
</div>
