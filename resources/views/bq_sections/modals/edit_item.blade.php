<!-- Edit Item Modal -->
<div class="modal fade" id="editItemModal{{ $item->id }}" tabindex="-1" aria-labelledby="editItemModalLabel{{ $item->id }}" aria-hidden="true">
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
                    <h6 class="text-muted mb-3">Section: {{ $bqSection->name }}</h6>

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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function () {
    const sectionId = "{{ $bqSection->id }}";

    $('.element-dropdown').each(function () {
        const elementDropdown = $(this);
        const itemDropdown = elementDropdown.closest('.modal-body').find('.item-dropdown');
        const selectedElementId = elementDropdown.data('selected-element');
        const selectedItemId = elementDropdown.closest('.modal-body').find('.item-dropdown').data('selected-item');
        const selectElementText = "{{ __('Select Element') }}";
        const selectItemText = "{{ __('Select Item') }}";

        // Populate element dropdown
        $.ajax({
            url: '{{ route('get.elements.by.section') }}',
            type: 'GET',
            data: { section_id: sectionId },
            success: function (data) {
                elementDropdown.empty().append('<option value="" disabled selected style="color: gray;">' + selectElementText + '</option>');
                $.each(data, function (key, value) {
                    elementDropdown.append('<option value="' + key + '">' + value + '</option>');
                });

                // Preselect element if available
                if (selectedElementId) {
                    elementDropdown.val(selectedElementId).trigger('change');
                }
            },
            error: function () {
                alert('Failed to load elements.');
            }
        });

        // When element is changed
        elementDropdown.on('change', function () {
            const elementId = $(this).val();
            itemDropdown.empty().append('<option value="">{{ __("Loading...") }}</option>');

            if (elementId) {
                $.ajax({
                    url: '{{ route('get.items.by.elements') }}',
                    type: 'GET',
                    data: { element_id: elementId },
                    success: function (data) {
                        itemDropdown.empty().append('<option value="" disabled selected style="color: gray;">' + selectItemText + '</option>');
                        $.each(data, function (key, value) {
                            itemDropdown.append('<option value="' + key + '">' + value + '</option>');
                        });

                        // Preselect item if available
                        if (selectedItemId) {
                            itemDropdown.val(selectedItemId);
                        }
                    },
                    error: function () {
                        alert('Failed to load items.');
                    }
                });
            }
        });
    });

    // Live amount calculation
    $('.rate-input, .quantity-input').on('input', function () {
        let modalBody = $(this).closest('.modal-body');
        let rate = parseFloat(modalBody.find('.rate-input').val()) || 0;
        let quantity = parseFloat(modalBody.find('.quantity-input').val()) || 0;
        modalBody.find('.amount-input').val((rate * quantity).toFixed(2));
    });
});
</script>
