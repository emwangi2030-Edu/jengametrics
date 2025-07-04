@extends('layouts.appbar')

@section('content')
<div class="container mt-4">
    <h2 class="mb-2">Section: {{ $section_name }}</h2>
    <h3 class="mb-4" style="color:#027333">Requisition Material </h3>

    <form action="{{ route('requisitions.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="bom_item_id" class="form-label">Select Material</label>
            <select name="bom_item_id" id="bom_item_id" class="form-select" required>
                <option value="" disabled>-- Choose Material --</option>
                @foreach ($items as $item)
                    <option value="{{ $item->id }}">
                        {{ $item->item_material->name ?? 'N/A' }} (<span style="color:#027333">Available:</span> {{ (int) $item->total_quantity }} {{ $item->unit}})
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="quantity_requested" class="form-label">Quantity</label>
            <input type="number" name="quantity_requested" id="quantity_requested" step="0.01" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-success">Submit Requisition</button>
        <a href="{{ route('boms.show', $item->section_id) }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
