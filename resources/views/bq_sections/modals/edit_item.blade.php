<!-- Edit Item Modal -->
<div class="modal fade edit-item-modal" id="editItemModal{{ $item->id }}" tabindex="-1" aria-labelledby="editItemModalLabel{{ $item->id }}" aria-hidden="true" data-section-id="{{ $item->section_id }}">
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
                    <h6 class="text-muted mb-3">Section: {{ optional($item->section)->name ?? __('Unassigned') }}</h6>

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

                    <div class="form-floating mb-4">
                        <input type="text" class="form-control unit-input" id="unit{{ $item->id }}" value="{{ $item->units }}" readonly>
                        <label for="unit{{ $item->id }}">{{ __('Unit') }}</label>
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
