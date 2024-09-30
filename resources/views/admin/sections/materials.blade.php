@extends('layouts.appbar')

@section('content')
<div class="container">
    <h1>Materials for Item: {{ $item->name }}</h1>

    <!-- Form to add a new material -->
    <div class="card">
        <div class="card-header">Add New Material</div>
        <div class="card-body">
            <form action="{{ route('materials.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="materialNameInput">Material Name</label>
                    <input type="text" class="form-control" id="materialNameInput" name="name" required>
                </div>
                <div class="form-group">
                    <label for="unitOfMeasurementInput">Unit of Measurement</label>
                    <select class="form-control" id="unitOfMeasurementInput" name="unit_of_measurement" required>
                        @foreach($units as $unit)
                            <option value="{{ $unit->name }}">{{ $unit->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="conversionFactorInput">Conversion Factor</label>
                    <input type="number" step="0.01" class="form-control" id="conversionFactorInput" name="conversion_factor" required>
                </div>
                <!-- Store the item_id in the form (hidden input) -->
                <input type="hidden" name="item_id" value="{{ $item->id }}">
                <button type="submit" class="btn btn-primary mt-3">Save Material</button>
            </form>
        </div>
    </div>

    <!-- List of materials -->
    <div class="card my-4">
        <div class="card-header">Materials</div>
        <div class="card-body">
            <ul class="list-group">
            @foreach($materials as $itemMaterial)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                        <strong>{{ $itemMaterial->name }}</strong><br>
                        <small>Unit: {{ $itemMaterial->unit_of_measurement }}</small><br>
                        <small>Conversion Factor: {{ $itemMaterial->conversion_factor }}</small>
                    </div>
                    <div>
                        <!-- Edit and Delete buttons -->
                        <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editMaterialModal{{ $itemMaterial->id }}">
                            Edit
                        </button>

                        <form action="{{ route('materials.destroy', $itemMaterial->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this material?');">
                                Delete
                            </button>
                        </form>

                    </div>
                </li>

                <!-- Edit Material Modal (For each material item) -->
                <div class="modal fade" id="editMaterialModal{{ $itemMaterial->id }}" tabindex="-1" aria-labelledby="editMaterialModalLabel{{ $itemMaterial->id }}" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editMaterialModalLabel{{ $itemMaterial->id }}">Edit Material</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="{{ route('materials.item.update', $itemMaterial->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="form-group">
                                        <label for="editMaterialNameInput{{ $itemMaterial->id }}">Material Name</label>
                                        <input type="text" class="form-control" id="editMaterialNameInput{{ $itemMaterial->id }}" name="name" value="{{ $itemMaterial->name }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="editUnitOfMeasurementInput{{ $itemMaterial->id }}">Unit of Measurement</label>
                                        <select class="form-control" id="editUnitOfMeasurementInput{{ $itemMaterial->id }}" name="unit_of_measurement" required>
                                            @foreach($units as $unit)
                                                <option value="{{ $unit->name }}" {{ $unit->name == $itemMaterial->unit_of_measurement ? 'selected' : '' }}>
                                                    {{ $unit->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="editConversionFactorInput{{ $itemMaterial->id }}">Conversion Factor</label>
                                        <input type="number" step="0.01" class="form-control" id="editConversionFactorInput{{ $itemMaterial->id }}" name="conversion_factor" value="{{ $itemMaterial->conversion_factor }}" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary mt-3">Save Changes</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
            </ul>
        </div>
    </div>

    <!-- Back to Items button -->
    <a href="{{ route('subelements.items', $item->sub_element_id) }}" class="btn btn-secondary">Back to Items</a>
</div>
@endsection
