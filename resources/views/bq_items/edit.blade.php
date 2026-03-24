@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="jm-page-header">
        <div>
            <h2 class="jm-page-title jm-ui-title">{{ __('Edit BoQ Item') }}</h2>
            <p class="jm-page-subtitle jm-ui-muted mb-0">{{ $bqItem->item_description }}</p>
        </div>
        <a href="{{ route('bq_documents.show', $bqDocument) }}" class="btn btn-outline-secondary" aria-label="Back" title="Back"><span data-feather="arrow-left-circle"></span></a>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card jm-ui-card shadow-sm border-0">
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('bq_items.update', ['id' => $bqItem->id]) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="bq_section_id" class="form-label">{{ __('Section') }}</label>
                            <select name="bq_section_id" id="bq_section_id" class="form-select" required>
                                @foreach($sections as $section)
                                    <option value="{{ $section->id }}" {{ $bqItem->bq_section_id == $section->id ? 'selected' : '' }}>
                                        {{ $section->section_name ?? $section->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="item_description" class="form-label">{{ __('Item Description') }}</label>
                            <input type="text" name="item_description" id="item_description" class="form-control" value="{{ $bqItem->item_description }}" required>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-4">
                                <label for="quantity" class="form-label">{{ __('Quantity') }}</label>
                                <input type="number" name="quantity" id="quantity" class="form-control" value="{{ $bqItem->quantity }}" required>
                            </div>
                            <div class="col-md-4">
                                <label for="unit" class="form-label">{{ __('Unit') }}</label>
                                <input type="text" name="unit" id="unit" class="form-control" value="{{ $bqItem->unit }}" required>
                            </div>
                            <div class="col-md-4">
                                <label for="rate" class="form-label">{{ __('Rate') }}</label>
                                <input type="number" name="rate" id="rate" class="form-control" step="0.01" value="{{ $bqItem->rate }}" required>
                            </div>
                        </div>

                        <div class="mt-3">
                            <label for="amount" class="form-label">{{ __('Amount') }}</label>
                            <input type="number" name="amount" id="amount" class="form-control" step="0.01" value="{{ $bqItem->amount }}" required>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('bq_documents.show', $bqDocument) }}" class="btn btn-outline-secondary" aria-label="Back" title="Back"><span data-feather="arrow-left-circle"></span></a>
                            <button type="submit" class="btn btn-primary">{{ __('Update Item') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const quantityInput = document.getElementById('quantity');
        const rateInput = document.getElementById('rate');
        const amountInput = document.getElementById('amount');

        function calculateAmount() {
            const quantity = parseFloat(quantityInput.value) || 0;
            const rate = parseFloat(rateInput.value) || 0;
            amountInput.value = (quantity * rate).toFixed(2);
        }

        quantityInput.addEventListener('input', calculateAmount);
        rateInput.addEventListener('input', calculateAmount);
    });
</script>
@endpush
