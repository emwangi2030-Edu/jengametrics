@extends('layouts.app')

@section('content')
    @php
        $canManageBoq = auth()->check() && (!auth()->user()->isSubAccount() || auth()->user()->can_manage_boq);
    @endphp
    <div class="container py-4 {{ $canManageBoq ? '' : 'boq-readonly' }}">
        <div class="row mb-4">
            <div class="col-12 d-flex flex-column flex-md-row justify-content-between align-items-md-center">
                <div>
                    <h2 class="font-weight-bold" style="color:#027333">
                        {{ $bqLevel->name }}
                    </h2>
                    <p class="text-muted mb-0">{{ $bqDocument->title }}</p>
                </div>
                <div class="mt-3 mt-md-0">
                    <a href="{{ route('bq_levels.items.create', [$bqDocument, $bqLevel]) }}" class="btn text-white" style="background-color:#027333">
                        {{ __('Add Item') }}
                    </a>
                    <a href="{{ route('bq_documents.show', $bqDocument) }}" class="btn btn-outline-secondary ms-2 allow-readonly" data-allow-readonly>
                        {{ __('Back to BoQ') }}
                    </a>
                </div>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-11">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h3 class="text-lg font-weight-bold mb-3">{{ __('Items') }}</h3>
                        <form method="GET" id="bq-items-filter" class="row g-3 align-items-end mb-3">
                            <div class="col-md-6 col-lg-4">
                                <label for="section-filter" class="form-label mb-1">{{ __('Section') }}</label>
                                <select name="section_id" id="section-filter" class="form-select">
                                    <option value="">{{ __('All Sections') }}</option>
                                    @foreach($sections as $section)
                                        <option value="{{ $section->id }}" @selected((string)$selectedSectionId === (string)$section->id)>
                                            {{ $section->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 col-lg-4 d-flex gap-2">
                                <a href="{{ route('bq_levels.show', [$bqDocument, $bqLevel]) }}" id="filter-reset" class="btn btn-outline-secondary mt-auto">
                                    {{ __('Reset') }}
                                </a>
                            </div>
                        </form>
                        <div id="items-table-container">
                            @include('bq_sections.partials.items_table', ['items' => $items])
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
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

                const allowedLabels = ['back', 'cancel', 'close', 'return', 'reset'];
                container.querySelectorAll('.btn').forEach((btn) => {
                    const text = (btn.textContent || '').trim().toLowerCase();
                    if (allowedLabels.includes(text) || btn.hasAttribute('data-allow-readonly')) {
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
                function showNoAccessToast() {
                    const toastContainer = document.getElementById('toast-container');
                    if (!toastContainer || hoverToast) {
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
                    event.preventDefault();
                    event.stopPropagation();
                    event.stopImmediatePropagation();
                }, true);
            })();
        </script>
    @endpush
@endif

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const filterForm = document.getElementById('bq-items-filter');
        const sectionSelect = document.getElementById('section-filter');
        const resetButton = document.getElementById('filter-reset');
        const tableContainer = document.getElementById('items-table-container');

        const routes = {
            filter: "{{ route('bq_levels.show', [$bqDocument, $bqLevel]) }}",
            elementsBySection: "{{ route('get.elements.by.section') }}",
            itemsByElement: "{{ route('get.items.by.elements') }}"
        };

        if (filterForm && sectionSelect && tableContainer) {
            const renderLoading = () => {
                tableContainer.innerHTML = '<div class="py-4 text-center text-muted">{{ __("Loading...") }}</div>';
            };

            const renderError = () => {
                tableContainer.innerHTML = '<div class="alert alert-danger mb-0">{{ __("Failed to load items. Please try again.") }}</div>';
            };

            const buildUrl = (sectionId) => {
                const url = new URL(routes.filter, window.location.origin);
                if (sectionId) {
                    url.searchParams.set('section_id', sectionId);
                }
                return url.toString();
            };

            const fetchFiltered = (sectionId) => {
                renderLoading();
                const url = buildUrl(sectionId);
                fetch(url, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                })
                    .then((response) => response.ok ? response.text() : Promise.reject())
                    .then((html) => {
                        tableContainer.innerHTML = html;
                        window.history.replaceState({}, '', url);
                    })
                    .catch(() => {
                        renderError();
                    });
            };

            filterForm.addEventListener('submit', function (event) {
                event.preventDefault();
                fetchFiltered(sectionSelect.value);
            });

            sectionSelect.addEventListener('change', function () {
                fetchFiltered(sectionSelect.value);
            });

            if (resetButton) {
                resetButton.addEventListener('click', function (event) {
                    event.preventDefault();
                    sectionSelect.value = '';
                    fetchFiltered('');
                });
            }
        }

        if (!window.__bqEditModalInit) {
            window.__bqEditModalInit = true;
            document.addEventListener('shown.bs.modal', function (event) {
                const modal = event.target;
                if (!modal || !modal.classList.contains('edit-item-modal')) {
                    return;
                }

                if (modal.dataset.initDone === '1') {
                    return;
                }
                modal.dataset.initDone = '1';

                const sectionId = modal.dataset.sectionId;
                const elementSelect = modal.querySelector('.element-dropdown');
                const itemSelect = modal.querySelector('.item-dropdown');
                const rateInput = modal.querySelector('.rate-input');
                const quantityInput = modal.querySelector('.quantity-input');
                const amountInput = modal.querySelector('.amount-input');
                const unitInput = modal.querySelector('.unit-input');
                const initialUnitValue = unitInput ? unitInput.value : '';

                const selectElementText = "{{ __('Select Element') }}";
                const selectItemText = "{{ __('Select Item') }}";
                const loadingText = "{{ __('Loading...') }}";

                const initialElementId = elementSelect?.dataset.selectedElement ? elementSelect.dataset.selectedElement.toString() : '';
                const initialItemId = itemSelect?.dataset.selectedItem ? itemSelect.dataset.selectedItem.toString() : '';

                const isPresent = (value) => value !== null && value !== undefined && value !== '';

                const normalizeOptions = (data) => {
                    if (Array.isArray(data)) {
                        return data.map((item) => ({
                            id: item.id ?? item.value ?? '',
                            name: item.name ?? item.label ?? '',
                            unit: item.unit ?? ''
                        }));
                    }
                    return Object.entries(data || {}).map(([id, name]) => ({ id, name, unit: '' }));
                };

                const renderOptions = (select, placeholder, data, selected) => {
                    if (!select) {
                        return;
                    }
                    select.innerHTML = '';
                    const defaultOption = document.createElement('option');
                    defaultOption.value = '';
                    defaultOption.textContent = placeholder;
                    defaultOption.disabled = true;
                    defaultOption.selected = true;
                    defaultOption.style.color = 'gray';
                    select.appendChild(defaultOption);

                    normalizeOptions(data).forEach((item) => {
                        const option = document.createElement('option');
                        option.value = item.id;
                        option.textContent = item.name;
                        if (item.unit) {
                            option.dataset.unit = item.unit;
                        }
                        if (isPresent(selected) && item.id?.toString() === selected.toString()) {
                            option.selected = true;
                            defaultOption.selected = false;
                        }
                        select.appendChild(option);
                    });
                };

                const setUnitFromSelect = (optionEl) => {
                    if (!unitInput) {
                        return;
                    }
                    if (optionEl && optionEl.dataset && optionEl.dataset.unit) {
                        unitInput.value = optionEl.dataset.unit;
                        return;
                    }
                    if (initialUnitValue) {
                        unitInput.value = initialUnitValue;
                    }
                };

                const loadItems = (elementId, selectedItem) => {
                    renderOptions(itemSelect, selectItemText, {}, null);
                    if (!elementId) {
                        return;
                    }
                    if (itemSelect) {
                        itemSelect.innerHTML = `<option value="" disabled selected style="color: gray;">${loadingText}</option>`;
                    }

                    const url = new URL(routes.itemsByElement, window.location.origin);
                    url.searchParams.set('element_id', elementId);
                    fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                        .then((response) => response.ok ? response.json() : Promise.reject())
                        .then((data) => {
                            renderOptions(itemSelect, selectItemText, data, selectedItem);
                            const selectedOpt = itemSelect?.options[itemSelect.selectedIndex];
                            setUnitFromSelect(selectedOpt);
                        });
                };

                const loadElements = (selectedElement, selectedItem) => {
                    if (!sectionId) {
                        renderOptions(elementSelect, selectElementText, {}, null);
                        return;
                    }
                    if (elementSelect) {
                        elementSelect.innerHTML = `<option value="" disabled selected style="color: gray;">${loadingText}</option>`;
                    }

                    const url = new URL(routes.elementsBySection, window.location.origin);
                    url.searchParams.set('section_id', sectionId);
                    fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                        .then((response) => response.ok ? response.json() : Promise.reject())
                        .then((data) => {
                            renderOptions(elementSelect, selectElementText, data, selectedElement);
                            loadItems(elementSelect?.value, selectedItem);
                        });
                };

                const updateAmount = () => {
                    if (!amountInput) {
                        return;
                    }
                    const rate = rateInput ? parseFloat(rateInput.value) || 0 : 0;
                    const quantity = quantityInput ? parseFloat(quantityInput.value) || 0 : 0;
                    amountInput.value = (rate * quantity).toFixed(2);
                };

                if (elementSelect) {
                    elementSelect.addEventListener('change', function () {
                        loadItems(this.value, null);
                    });
                }

                if (itemSelect) {
                    itemSelect.addEventListener('change', function () {
                        const selectedOpt = itemSelect.options[itemSelect.selectedIndex];
                        setUnitFromSelect(selectedOpt);
                    });
                }

                if (rateInput) {
                    rateInput.addEventListener('input', updateAmount);
                }

                if (quantityInput) {
                    quantityInput.addEventListener('input', updateAmount);
                }

                loadElements(initialElementId, initialItemId);
                updateAmount();
            });
        }
    });
</script>
@endpush
