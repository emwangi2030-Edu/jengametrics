@extends('layouts.appbar')

@section('content')
<div class="container">
    <h1>Items for Sub-Element: {{ $subElement->name }}</h1>

    <!-- Form to add a new item -->
    <div class="card">
        <div class="card-header">Add New Item</div>
        <div class="card-body">
            <form action="{{ route('subelements.items.store', $subElement->id) }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="itemNameInput">Item Name</label>
                    <input type="text" class="form-control" id="itemNameInput" name="name" required>
                </div>
                <div class="form-group">
                    <label for="itemDescriptionInput">Description</label>
                    <textarea class="form-control" id="itemDescriptionInput" name="description" rows="3"></textarea>
                </div>
                <input type="hidden" name="sub_element_id" value="{{ $subElement->id }}">
                <button type="submit" class="btn btn-primary mt-3">Save Item</button>
            </form>
        </div>
    </div>

    <!-- List of items -->
    <div class="card my-4">
        <div class="card-header">Items</div>
        <div class="card-body">
            <ul class="list-group">
                @forelse($items as $item)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <strong>{{ $item->name }}</strong><br>
                            <small>{{ $item->description }}</small>
                        </div>
                        <div>
                            <!-- Edit and Delete buttons -->
                            <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editItemModal{{ $item->id }}">
                                Edit
                            </button>

                            <form action="{{ route('items.destroy', $item->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this item?');">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </li>

                    <!-- Edit Item Modal -->
                    <div class="modal fade" id="editItemModal{{ $item->id }}" tabindex="-1" aria-labelledby="editItemModalLabel{{ $item->id }}" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editItemModalLabel{{ $item->id }}">Edit Item</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form action="{{ route('items.update', $item->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="form-group">
                                            <label for="editItemNameInput{{ $item->id }}">Item Name</label>
                                            <input type="text" class="form-control" id="editItemNameInput{{ $item->id }}" name="name" value="{{ $item->name }}" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="editItemDescriptionInput{{ $item->id }}">Description</label>
                                            <textarea class="form-control" id="editItemDescriptionInput{{ $item->id }}" name="description" rows="3">{{ $item->description }}</textarea>
                                        </div>
                                        <button type="submit" class="btn btn-primary mt-3">Save Changes</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <li class="list-group-item">No items available for this sub-element.</li>
                @endforelse
            </ul>
        </div>
    </div>

    <!-- Back to Sub-Elements button -->
    <a href="" class="btn btn-secondary">Back to Sub-Elements</a>
</div>
@endsection
