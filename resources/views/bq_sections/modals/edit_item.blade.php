<!-- Edit Item Modal -->
<div class="modal fade" id="editItemModal{{ $item->id }}" tabindex="-1" aria-labelledby="editItemModalLabel{{ $item->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editItemModalLabel{{ $item->id }}">Edit Item</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{  route('bq_items.update', ['id' => $item->id])  }}" method="POST">
                    @csrf
                    @method('PUT')

                    <input type="hidden" name="id" value="{{ $item->id }}">

                    <!-- Name Dropdown -->
                    <div class="form-group">
                        <label for="editItemNameInput{{ $item->id }}">Name</label>
                        <select class="form-control" id="editItemNameInput{{ $item->id }}" name="item_name" required>
                            <option value="" disabled>Select an item</option>
                            @foreach($bq_sections as $section)
                                <option value="{{ $section->name }}" 
                                    {{ $item->item_name == $section->name ? 'selected' : '' }}>
                                    {{ $section->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Quantity Input -->
                    <div class="form-group">
                        <label for="editItemQuantityInput{{ $item->id }}">Quantity</label>
                        <input type="text" class="form-control" id="editItemQuantityInput{{ $item->id }}" name="quantity" value="{{ $item->quantity }}" required>
                    </div>

                    <!-- Rate Input -->
                    <div class="form-group">
                        <label for="editItemRateInput{{ $item->id }}">Rate</label>
                        <input type="text" class="form-control" id="editItemRateInput{{ $item->id }}" name="rate" value="{{ $item->rate }}" required>
                    </div>

                    <button type="submit" class="btn btn-primary mt-3">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
</div>
