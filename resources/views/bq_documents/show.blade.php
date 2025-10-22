@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <div class="row mb-4">
            <div class="col-12 d-flex flex-column flex-md-row justify-content-between align-items-md-center">
                <div>
                    <h2 class="fw-bold" style="color:#027333">
                        {{ $bqDocument->title }}
                    </h2>
                    <p class="text-muted mb-0">{{ $bqDocument->description }}</p>
                </div>
                <div class="text-end mt-3 mt-md-0">
                    <p class="fs-4 fw-bold mb-1">KES {{ number_format($totalAmount, 2) }}</p>
                    <p class="text-muted mb-0">{{ __('Sub BoQ total') }}</p>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-12 d-flex flex-column flex-lg-row align-items-start gap-2">
                <a href="{{ route('bq_sections.create', $bqDocument) }}" class="btn text-white" style="background-color:#027333">
                    {{ __('Add BoQ Item') }}
                </a>
                @if($libraries->isNotEmpty())
                    <button type="button"
                        class="btn btn-outline-primary"
                        data-bs-toggle="modal"
                        data-bs-target="#importLibraryModal">
                        {{ __('Import from Library') }}
                    </button>
                @else
                    <button type="button" class="btn btn-outline-primary" disabled>
                        {{ __('Import from Library') }}
                    </button>
                @endif
                <a href="{{ route('bq_documents.index') }}" class="btn btn-outline-secondary ms-lg-2">
                    {{ __('Back to Master BoQ') }}
                </a>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-10">
                @if($sectionGroups->isEmpty())
                    <div class="card shadow-sm border-0">
                        <div class="card-body text-center py-5">
                            <p class="text-muted mb-0">{{ __('No sections added to this BoQ yet.') }}</p>
                        </div>
                    </div>
                @else
                    @foreach($sectionGroups as $sectionId => $group)
                        <div class="card shadow-sm border-0 mb-4">
                            <div class="card-body">
                                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center">
                                    <div>
                                        <h5 class="fw-bold mb-1">{{ optional($group['section'])->name ?? __('Unnamed Section') }}</h5>
                                    </div>
                                    <div class="text-end">
                                        <p class="fw-bold fs-5 mb-1">KES {{ number_format($group['total'], 2) }}</p>
                                        @if($sectionId)
                                            <a href="{{ route('bq_sections.show', [$bqDocument, $sectionId]) }}" class="btn btn-outline-primary btn-sm">
                                                {{ __('Manage Items') }}
                                            </a>
                                        @endif
                                    </div>
                                </div>

                                <div class="table-responsive mt-3">
                                    <table class="table table-sm align-middle">
                                        <thead class="table-light">
                                            <tr>
                                                <th>{{ __('Item') }}</th>
                                                <th>{{ __('Unit') }}</th>
                                                <th class="text-end">{{ __('Quantity') }}</th>
                                                <th class="text-end">{{ __('Rate (KES)') }}</th>
                                                <th class="text-end">{{ __('Amount (KES)') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($group['items'] as $item)
                                                @php
                                                    $displayQuantity = $item->quantity ?? 0;
                                                @endphp
                                                <tr>
                                                    <td>{{ $item->item_name }}</td>
                                                    <td>{{ $item->units ?? 'N/A' }}</td>
                                                    <td class="text-end">{{ is_int($displayQuantity) ? $displayQuantity : number_format($displayQuantity, 2) }}</td>
                                                    <td class="text-end">{{ number_format((float) ($item->rate ?? 0), 2) }}</td>
                                                    <td class="text-end">{{ number_format((float) ($item->amount ?? 0), 2) }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>

    @if($libraries->isNotEmpty())
        <div class="modal fade" id="importLibraryModal" tabindex="-1" aria-labelledby="importLibraryModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-centered">
                <div class="modal-content">
                    <form action="{{ route('bq_documents.import-library', $bqDocument) }}" method="POST">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="importLibraryModalLabel">{{ __('Import Items from Library') }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('Close') }}"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label for="import-library-select" class="form-label">{{ __('Library') }}</label>
                                    <select id="import-library-select"
                                        name="library_id"
                                        class="form-select"
                                        required
                                        data-items-url="{{ route('libraries.items', ['library' => '__LIBRARY__']) }}">
                                        <option value="">{{ __('Select a library') }}</option>
                                        @foreach($libraries as $library)
                                            <option value="{{ $library->id }}">{{ $library->name }} ({{ $library->items_count }})</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div id="import-library-items" class="border rounded p-3" style="max-height: 420px; overflow-y: auto;">
                                <p class="text-muted mb-0">{{ __('Select a library to load its items.') }}</p>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                            <button type="submit" class="btn btn-primary">{{ __('Import Selected Items') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@endsection

@if($libraries->isNotEmpty())
    @push('scripts')
    <script>
        (function () {
            const modal = $('#importLibraryModal');
            if (!modal.length) {
                return;
            }

            const librarySelect = $('#import-library-select');
            const itemsContainer = $('#import-library-items');
            const itemsRouteTemplate = librarySelect.data('items-url');

            function resetItemsContainer(message) {
                itemsContainer.html(
                    $('<p>', { class: 'text-muted mb-0', text: message || '{{ __('Select a library to load its items.') }}' })
                );
            }

            function buildUrl(libraryId) {
                return itemsRouteTemplate.replace('__LIBRARY__', libraryId);
            }

            function renderItems(items) {
                if (!items || items.length === 0) {
                    resetItemsContainer('{{ __('No items found in the selected library.') }}');
                    return;
                }

                const table = $('<table>', { class: 'table table-sm align-middle mb-0' });
                const thead = $('<thead>').append(
                    $('<tr>')
                        .append($('<th>', { text: '{{ __('Select') }}' }))
                        .append($('<th>', { text: '{{ __('Section') }}' }))
                        .append($('<th>', { text: '{{ __('Element') }}' }))
                        .append($('<th>', { text: '{{ __('Item') }}' }))
                        .append($('<th>', { text: '{{ __('Unit') }}' }))
                        .append($('<th>', { class: 'text-end', text: '{{ __('Quantity') }}' }))
                        .append($('<th>', { class: 'text-end', text: '{{ __('Rate (KES)') }}' }))
                );

                const tbody = $('<tbody>');

                items.forEach(function (item) {
                    const rowId = `import-library-item-${item.id}`;
                    const checkbox = $('<input>', {
                        type: 'checkbox',
                        class: 'form-check-input import-library-checkbox',
                        id: rowId,
                        'data-item-id': item.id
                    });

                    const quantityInput = $('<input>', {
                        type: 'number',
                        name: `items[${item.id}][quantity]`,
                        class: 'form-control form-control-sm text-end',
                        step: '0.01',
                        min: '0.0001',
                        disabled: true
                    });

                    const rateInput = $('<input>', {
                        type: 'number',
                        name: `items[${item.id}][rate]`,
                        class: 'form-control form-control-sm text-end',
                        step: '0.01',
                        min: '0',
                        disabled: true
                    });

                    checkbox.on('change', function () {
                        const checked = $(this).is(':checked');
                        quantityInput.prop('disabled', !checked);
                        rateInput.prop('disabled', !checked);

                        if (checked) {
                            if (!quantityInput.val()) {
                                quantityInput.val('1');
                            }
                            if (!rateInput.val()) {
                                rateInput.val('0');
                            }
                        } else {
                            quantityInput.val('');
                            rateInput.val('');
                        }
                    });

                    const row = $('<tr>')
                        .append($('<td>').append(
                            $('<div>', { class: 'form-check mb-0' }).append(checkbox)
                        ))
                        .append($('<td>', { text: item.section || '-' }))
                        .append($('<td>', { text: item.element || '-' }))
                        .append($('<td>', { text: item.item || '-' }))
                        .append($('<td>', { text: item.unit || '-' }))
                        .append($('<td>', { class: 'text-end', html: quantityInput }))
                        .append($('<td>', { class: 'text-end', html: rateInput }));

                    tbody.append(row);
                });

                table.append(thead).append(tbody);
                itemsContainer.html(table);
            }

            librarySelect.on('change', function () {
                const libraryId = $(this).val();

                if (!libraryId) {
                    resetItemsContainer();
                    return;
                }

                resetItemsContainer('{{ __('Loading items...') }}');

                $.get(buildUrl(libraryId))
                    .done(function (response) {
                        renderItems(response.items);
                    })
                    .fail(function () {
                        resetItemsContainer('{{ __('Unable to load items for the selected library.') }}');
                    });
            });

            modal.on('hidden.bs.modal', function () {
                librarySelect.val('');
                resetItemsContainer();
            });
        })();
    </script>
    @endpush
@endif
