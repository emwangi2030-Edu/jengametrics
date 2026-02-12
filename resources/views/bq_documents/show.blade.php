@extends('layouts.app')

@push('styles')
<link rel="preload" href="{{ asset('assets/metrics/assets/css/theme.min.css') }}" as="style">
<link rel="preload" href="{{ asset('assets/metrics/assets/css/user.min.css') }}" as="style">
@endpush

@section('content')
    @php
        $canManageBoq = auth()->check() && (!auth()->user()->isSubAccount() || auth()->user()->can_manage_boq);
    @endphp
    <div class="container mt-4 {{ $canManageBoq ? '' : 'boq-readonly' }}">
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
                    <p class="text-muted mb-0">{{ __('BoQ total') }}</p>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-12 d-flex flex-column flex-lg-row align-items-start gap-2">
                <button type="button"
                    class="btn text-white"
                    style="background-color:#027333"
                    data-bs-toggle="modal"
                    data-bs-target="#createLevelModal">
                    {{ __('Add Level') }}
                </button>
                @if($levels->isNotEmpty() && $libraries->isNotEmpty())
                    <button type="button"
                        class="btn btn-outline-primary"
                        data-bs-toggle="modal"
                        data-bs-target="#importLibraryModal">
                        {{ __('Import from Library') }}
                    </button>
                @elseif($libraries->isNotEmpty())
                    <button type="button" class="btn btn-outline-primary" disabled>
                        {{ __('Import from Library') }}
                    </button>
                @endif
                <a href="{{ route('bq_documents.index') }}" class="btn btn-outline-secondary ms-lg-2 allow-readonly" data-allow-readonly>
                    {{ __('Back to Master BoQ') }}
                </a>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-10">
                @if($levels->isEmpty())
                    <div class="card shadow-sm border-0">
                        <div class="card-body text-center py-5">
                            <p class="text-muted mb-0">{{ __('No levels have been added to this BoQ yet.') }}</p>
                        </div>
                    </div>
                @else
                    @foreach($levels as $levelData)
                        @php
                            $level = $levelData['level'];
                        @endphp
                        <div class="card shadow-sm border-0 mb-4">
                            <div class="card-body">
                                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center">
                                    <div>
                                        <h5 class="fw-bold mb-1">
                                            <a href="{{ route('bq_levels.show', [$bqDocument, $level]) }}" class="text-decoration-none">
                                                {{ $level->name }}
                                            </a>
                                        </h5>
                                        @if($level->description)
                                            <p class="text-muted mb-0">{{ $level->description }}</p>
                                        @endif
                                    </div>
                                    <div class="text-end">
                                        <p class="fw-bold fs-5 mb-1">KES {{ number_format($levelData['total'], 2) }}</p>
                                        <p class="text-muted small mb-2">{{ trans_choice(':count item|:count items', $levelData['items_count'], ['count' => $levelData['items_count']]) }}</p>
                                        <div class="col-12 d-flex flex-column flex-lg-row align-items-start gap-2 justify-content-end">
                                            <a href="{{ route('bq_levels.items.create', [$bqDocument, $level]) }}" class="btn btn-success btn-sm text-white">
                                                {{ __('Add Item') }}
                                            </a>
                                            <button
                                                type="button"
                                                class="btn btn-outline-success btn-sm"
                                                data-bs-toggle="modal"
                                                data-bs-target="#copyLevelModal{{ $level->id }}">
                                                {{ __('+') }}
                                            </button>
                                            <button
                                                type="button"
                                                class="btn btn-outline-secondary btn-sm"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editLevelModal{{ $level->id }}">
                                                {{ __('Edit') }}
                                            </button>
                                            <form
                                                method="POST"
                                                action="{{ route('bq_levels.destroy', [$bqDocument, $level]) }}"
                                                class="d-inline"
                                                data-confirm-message="{{ __('Do you wish to delete this level?') }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger btn-sm">
                                                    {{ __('Delete') }}
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>

    @foreach($levels as $levelData)
        @php $level = $levelData['level']; @endphp
        <div class="modal fade" id="editLevelModal{{ $level->id }}" tabindex="-1" aria-labelledby="editLevelModalLabel{{ $level->id }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="POST" action="{{ route('bq_levels.update', [$bqDocument, $level]) }}">
                        @csrf
                        @method('PUT')
                        <div class="modal-header">
                            <h5 class="modal-title" id="editLevelModalLabel{{ $level->id }}">{{ __('Edit Level') }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('Close') }}"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label" for="level-name-{{ $level->id }}">{{ __('Level Name') }}</label>
                                <input type="text" class="form-control" id="level-name-{{ $level->id }}" name="name" value="{{ $level->name }}" required maxlength="255">
                            </div>
                            <div class="mb-0">
                                <label class="form-label" for="level-description-{{ $level->id }}">{{ __('Description') }}</label>
                                <textarea class="form-control" id="level-description-{{ $level->id }}" name="description" rows="3">{{ $level->description }}</textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                            <button type="submit" class="btn btn-primary">{{ __('Save Changes') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal fade" id="copyLevelModal{{ $level->id }}" tabindex="-1" aria-labelledby="copyLevelModalLabel{{ $level->id }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="POST" action="{{ route('bq_levels.copy', [$bqDocument, $level]) }}">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="copyLevelModalLabel{{ $level->id }}">{{ __('Copy Level') }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('Close') }}"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label" for="copy-level-name-{{ $level->id }}">{{ __('Level Name') }}</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    id="copy-level-name-{{ $level->id }}"
                                    name="name"
                                    value="{{ trim($level->name . ' Copy') }}"
                                    required
                                    maxlength="255">
                            </div>
                            <div class="mb-0">
                                <label class="form-label" for="copy-level-description-{{ $level->id }}">{{ __('Description (optional)') }}</label>
                                <textarea
                                    class="form-control"
                                    id="copy-level-description-{{ $level->id }}"
                                    name="description"
                                    rows="3">{{ $level->description }}</textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                            <button type="submit" class="btn btn-primary">{{ __('Copy Level') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach

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
                                <div class="col-md-6">
                                    <label for="import-level-select" class="form-label">{{ __('Target Level') }}</label>
                                    <select id="import-level-select"
                                        name="bq_level_id"
                                        class="form-select"
                                        @if($levels->isEmpty()) disabled @else required @endif>
                                        <option value="">{{ __('Select a level') }}</option>
                                        @foreach($levels as $levelData)
                                            <option value="{{ $levelData['level']->id }}">{{ $levelData['level']->name }}</option>
                                        @endforeach
                                    </select>
                                    @if($levels->isEmpty())
                                        <small class="text-danger">{{ __('Create a level before importing items.') }}</small>
                                    @endif
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

@if(!$canManageBoq)
    @push('styles')
        <style>
            .boq-readonly .btn {
                opacity: 0.55;
                cursor: not-allowed;
            }

            .boq-readonly .btn.allow-readonly {
                opacity: 1;
                cursor: pointer;
                pointer-events: auto;
            }

            .boq-readonly a.btn:hover,
            .boq-readonly button:hover {
                transform: none !important;
            }

            .boq-readonly .btn.allow-readonly:hover {
                cursor: pointer;
            }

            .boq-readonly .btn:hover {
                cursor: not-allowed;
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            (function () {
                const container = document.querySelector('.boq-readonly');
                if (!container) {
                    return;
                }

                const allowedLabels = ['back', 'cancel', 'close', 'return'];
                container.querySelectorAll('.btn').forEach((btn) => {
                    const text = (btn.textContent || '').trim().toLowerCase();
                    if (allowedLabels.includes(text)) {
                        btn.classList.add('allow-readonly');
                    }
                });

                container.querySelectorAll('[data-bs-toggle="modal"]').forEach((trigger) => {
                    if (trigger.classList.contains('allow-readonly')) {
                        return;
                    }
                    trigger.setAttribute('data-bs-toggle-disabled', 'true');
                    if (trigger.hasAttribute('data-bs-target')) {
                        trigger.setAttribute('data-bs-target-disabled', trigger.getAttribute('data-bs-target'));
                        trigger.removeAttribute('data-bs-target');
                    }
                    trigger.removeAttribute('data-bs-toggle');
                });

                let hoverToast = null;
                function ensureToastContainer() {
                    return document.getElementById('toast-container');
                }

                function showNoAccessToast() {
                    const toastContainer = ensureToastContainer();
                    if (!toastContainer) {
                        return;
                    }
                    if (hoverToast) {
                        return;
                    }

                    const toast = document.createElement('div');
                    toast.className = 'toast show';
                    toast.setAttribute('role', 'alert');
                    toast.setAttribute('aria-live', 'assertive');
                    toast.setAttribute('aria-atomic', 'true');

                    toast.innerHTML = `
                        <div class="toast-header">
                            <strong class="me-auto">System</strong>
                            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                        </div>
                        <div class="toast-body">
                            <span class="badge bg-warning">Warning</span>
                            You do not have access
                        </div>
                    `;

                    toastContainer.appendChild(toast);
                    hoverToast = toast;
                }

                function hideNoAccessToast() {
                    if (!hoverToast) {
                        return;
                    }
                    hoverToast.remove();
                    hoverToast = null;
                }

                container.addEventListener('mouseenter', function (event) {
                    const target = event.target.closest('.btn');
                    if (!target) {
                        return;
                    }
                    if (target.classList.contains('allow-readonly')) {
                        return;
                    }
                    showNoAccessToast();
                }, true);

                container.addEventListener('mouseleave', function (event) {
                    const target = event.target.closest('.btn');
                    if (!target) {
                        return;
                    }
                    if (target.classList.contains('allow-readonly')) {
                        return;
                    }
                    hideNoAccessToast();
                }, true);

                container.addEventListener('click', function (event) {
                    const target = event.target.closest('.btn');
                    if (!target) {
                        return;
                    }
                    if (target.classList.contains('allow-readonly')) {
                        return;
                    }
                    if (target.hasAttribute('data-bs-toggle-disabled')) {
                        event.preventDefault();
                        event.stopPropagation();
                        event.stopImmediatePropagation();
                        return;
                    }
                    event.preventDefault();
                    event.stopPropagation();
                }, true);
            })();
        </script>
    @endpush
@endif

<!-- Create Level Modal -->
<div class="modal fade" id="createLevelModal" tabindex="-1" aria-labelledby="createLevelModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('bq_levels.store', $bqDocument) }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="createLevelModalLabel">{{ __('Create Level') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('Close') }}"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="level_name" class="form-label">{{ __('Level Name') }}</label>
                        <input type="text" class="form-control" id="level_name" name="name" required maxlength="255">
                    </div>
                    <div class="mb-0">
                        <label for="level_description" class="form-label">{{ __('Description (optional)') }}</label>
                        <textarea class="form-control" id="level_description" name="description" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                    <button type="submit" class="btn btn-primary">{{ __('Save Level') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

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

            function formatQuantity(value) {
                if (value === '' || value === null || value === undefined) {
                    return '';
                }

                const numeric = parseFloat(value);
                if (!Number.isFinite(numeric) || numeric <= 0) {
                    return '';
                }

                const rounded = Math.round(numeric * 100) / 100;
                return rounded.toFixed(2);
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
                        min: '0',
                        inputmode: 'decimal',
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

                    const sanitizeQuantity = function () {
                        const formatted = formatQuantity($(this).val());
                        if (formatted) {
                            $(this).val(formatted);
                        } else {
                            $(this).val('');
                        }
                    };

                    quantityInput.on('blur change', sanitizeQuantity);

                    checkbox.on('change', function () {
                        const checked = $(this).is(':checked');
                        quantityInput.prop('disabled', !checked);
                        rateInput.prop('disabled', !checked);

                        if (checked) {
                            if (!quantityInput.val()) {
                                quantityInput.val(formatQuantity('1'));
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
