<!-- Edit Item Modal -->
<div class="modal fade" id="editItemModal{{ $item->id }}" tabindex="-1" aria-labelledby="editItemModalLabel{{ $item->id }}" aria-hidden="true" data-section-id="{{ $section->id }}">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editItemModalLabel{{ $item->id }}">Edit Item</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('bq_items.update', ['id' => $item->id]) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <input type="hidden" name="id" value="{{ $item->id }}">

                    <!-- Section Title -->
                    <h6 class="text-muted mb-3">Section: {{ $section->name }}</h6>

                    <!-- Element Dropdown -->
                    <div class="form-floating mb-4">
                        <select name="element_id" id="element{{ $item->id }}"
                                class="form-select element-dropdown"
                                data-selected-element="{{ $item->element_id }}" required>
                            <option value="">{{ __('Choose Element') }}</option>
                            {{-- Populated via JS --}}
                        </select>
                        <label for="element{{ $item->id }}">{{ __('Select Element') }}</label>
                    </div>

                    <!-- Item Dropdown -->
                    <div class="form-floating mb-4">
                        <select name="item_id" id="item{{ $item->id }}"
                                class="form-select item-dropdown"
                                data-selected-item="{{ $item->item_id }}" required>
                            <option value="">{{ __('Choose Item') }}</option>
                            {{-- Populated via JS --}}
                        </select>
                        <label for="item{{ $item->id }}">{{ __('Select Item') }}</label>
                    </div>

                    <!-- Rate Input -->
                    <div class="form-floating mb-4">
                        <input type="number" name="rate" id="rate{{ $item->id }}" class="form-control rate-input" step="0.01" value="{{ $item->rate }}" required>
                        <label for="rate{{ $item->id }}">{{ __('Rate') }}</label>
                    </div>

                    <!-- Quantity Input -->
                    <div class="form-floating mb-4">
                        <input type="number" name="quantity" id="quantity{{ $item->id }}" class="form-control quantity-input" value="{{ $item->quantity }}" required>
                        <label for="quantity{{ $item->id }}">{{ __('Quantity') }}</label>
                    </div>

                    <!-- Amount (Readonly) -->
                    <div class="form-floating mb-4">
                        <input type="number" class="form-control amount-input" step="0.01" value="{{ $item->amount }}" readonly>
                        <label for="amount{{ $item->id }}">{{ __('Amount') }}</label>
                    </div>

                    <div class="d-flex justify-content-center">
                        <button type="submit" class="btn btn-primary w-50 py-2">{{ __('Save Changes') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        (function initEditItemModal{{ $item->id }}() {
            const modal = document.getElementById('editItemModal{{ $item->id }}');
            if (!modal) {
                return;
            }

            const sectionId = modal.dataset.sectionId;
            const elementSelect = modal.querySelector('#element{{ $item->id }}');
            const itemSelect = modal.querySelector('#item{{ $item->id }}');
            const rateInput = modal.querySelector('#rate{{ $item->id }}');
            const quantityInput = modal.querySelector('#quantity{{ $item->id }}');
            const amountInput = modal.querySelector('.amount-input');

            const selectElementText = "{{ __('Select Element') }}";
            const selectItemText = "{{ __('Select Item') }}";
            const loadingText = "{{ __('Loading...') }}";

            const initialElementId = elementSelect.dataset.selectedElement ? elementSelect.dataset.selectedElement.toString() : '';
            const initialItemId = itemSelect.dataset.selectedItem ? itemSelect.dataset.selectedItem.toString() : '';

            const isPresent = (value) => value !== null && value !== undefined && value !== '';

            const renderOptions = (select, placeholder, data, selected) => {
                select.innerHTML = '';
                const defaultOption = document.createElement('option');
                defaultOption.value = '';
                defaultOption.textContent = placeholder;
                defaultOption.disabled = true;
                defaultOption.selected = true;
                defaultOption.style.color = 'gray';
                select.appendChild(defaultOption);

                Object.entries(data || {}).forEach(([value, label]) => {
                    const option = document.createElement('option');
                    option.value = value;
                    option.textContent = label;
                    if (isPresent(selected) && value.toString() === selected.toString()) {
                        option.selected = true;
                        defaultOption.selected = false;
                    }
                    select.appendChild(option);
                });
            };

            const loadItems = (elementId, selectedItem) => {
                renderOptions(itemSelect, selectItemText, {}, null);

                if (!elementId) {
                    return;
                }

                itemSelect.innerHTML = `<option value="" disabled selected style="color: gray;">${loadingText}</option>`;

                $.ajax({
                    url: '{{ route('get.items.by.elements') }}',
                    type: 'GET',
                    data: { element_id: elementId },
                }).done(function (data) {
                    renderOptions(itemSelect, selectItemText, data, selectedItem);
                });
            };

            const loadElements = (selectedElement, selectedItem) => {
                elementSelect.innerHTML = `<option value="" disabled selected style="color: gray;">${loadingText}</option>`;

                $.ajax({
                    url: '{{ route('get.elements.by.section') }}',
                    type: 'GET',
                    data: { section_id: sectionId },
                }).done(function (data) {
                    renderOptions(elementSelect, selectElementText, data, selectedElement);

                    const activeElement = elementSelect.value;
                    loadItems(activeElement, selectedItem);
                });
            };

            elementSelect.addEventListener('change', function () {
                loadItems(this.value, null);
            });

            const updateAmount = () => {
                if (!amountInput) {
                    return;
                }

                const rate = rateInput ? parseFloat(rateInput.value) || 0 : 0;
                const quantity = quantityInput ? parseFloat(quantityInput.value) || 0 : 0;
                amountInput.value = (rate * quantity).toFixed(2);
            };

            if (rateInput) {
                rateInput.addEventListener('input', updateAmount);
            }

            if (quantityInput) {
                quantityInput.addEventListener('input', updateAmount);
            }

            loadElements(initialElementId, initialItemId);
            updateAmount();
        })();
    });
</script>
