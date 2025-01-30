@extends('layouts.appbar')

@section('content')
    <div class="container">
        <h1>List of Materials</h1>
        <a href="#" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addMaterialModal">Add Material</a>
        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Unit</th>
                    <th>Rate</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
                    <tr>
                        <td><span class="fw-bold">{{ $loop->iteration }}</span>. {{ $product->name }}</td>
                        <td>{{ $product->unit }}</td>
                        <td>{{ $product->rate }}</td>
                        <td>
                            <a href="#" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editModal{{ $product->id }}">Edit</a>
                            <form action="{{ route('products.destroy', $product->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this material?');">Delete</button>
                            </form>
                        </td>
                    </tr>

                    <!-- Edit Modal -->
                    <div class="modal fade" id="editModal{{ $product->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $product->id }}" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editModalLabel{{ $product->id }}">Edit Material</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form action="{{ route('products.update', $product->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="form-group">
                                            <label for="name{{ $product->id }}">Material Name</label>
                                            <input type="text" class="form-control" id="name{{ $product->id }}" name="name" value="{{ $product->name }}" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="unit{{ $product->id }}">Unit</label>
                                            <select class="form-control" name="unit" id="unit{{ $product->id }}">
                                                @foreach($units as $unit)
                                                    <option value="{{ $unit->abbrev }}" {{ $product->unit == $unit->abbrev ? 'selected' : '' }}>
                                                        {{ $unit->abbrev }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="rate{{ $product->id }}">Rate</label>
                                            <input type="number" step="0.01" class="form-control" id="rate{{ $product->id }}" name="rate" value="{{ $product->rate }}" required>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-primary">Update</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </tbody>
        </table> 
    </div>

    <!-- Add Material Modal -->
    <div class="modal fade" id="addMaterialModal" tabindex="-1" aria-labelledby="addMaterialModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addMaterialModalLabel">Add Material</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('products.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="name">Material Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="unit">Select Unit</label>
                            <select class="form-control" name="unit" id="unit">
                                @foreach($units as $unit)
                                    <option value="{{ $unit->abbrev }}">{{ $unit->abbrev }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="rate">Rate</label>
                            <input type="number" step="0.01" class="form-control" id="rate" name="rate" required>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Add</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
