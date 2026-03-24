@extends('layouts.app')

@section('content')
<div class="container py-4">
    @php
        $backUrl = isset($bqSection->bq_level_id) && $bqSection->bq_level_id
            ? route('bq_levels.show', [$bqSection->bq_document_id, $bqSection->bq_level_id])
            : route('bq_documents.show', $bqSection->bq_document_id);
    @endphp

    <div class="jm-page-header">
        <div>
            <h2 class="jm-page-title jm-ui-title">{{ __('Add New BoQ Item') }}</h2>
            <p class="jm-page-subtitle jm-ui-muted mb-0">{{ $bqSection->section_name ?? $bqSection->name ?? __('Section') }}</p>
        </div>
        <a href="{{ $backUrl }}" class="btn btn-outline-secondary" aria-label="Back" title="Back"><span data-feather="arrow-left-circle"></span></a>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card jm-ui-card shadow-sm border-0">
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('bq_documents.items.store', $bqSection->bq_document_id) }}" id="bqForm">
                        @csrf
                        <input type="hidden" name="bq_section_id" value="{{ $bqSection->id }}">

                        <div class="mb-3">
                            <label for="item_description" class="form-label">{{ __('Item Description') }}</label>
                            <input type="text" name="item_description" id="item_description" class="form-control" placeholder="Enter item description" required>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="quantity" class="form-label">{{ __('Quantity') }}</label>
                                <input type="number" name="quantity" id="quantity" class="form-control" placeholder="Enter quantity" required>
                            </div>
                            <div class="col-md-6">
                                <label for="unit" class="form-label">{{ __('Unit') }}</label>
                                <select name="unit" id="unit" class="form-select" required>
                                    <option value="" disabled selected>{{ __('Select unit') }}</option>
                                    @foreach ($units as $unit)
                                        <option value="{{ $unit }}">{{ ucfirst($unit) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="rate" class="form-label">{{ __('Rate') }}</label>
                                <input type="number" name="rate" id="rate" class="form-control" step="0.01" placeholder="Enter rate" required>
                            </div>
                            <div class="col-md-6">
                                <label for="amount" class="form-label">{{ __('Amount') }}</label>
                                <input type="number" name="amount" id="amount" class="form-control" step="0.01" placeholder="Enter amount" readonly>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ $backUrl }}" class="btn btn-outline-secondary" aria-label="Back" title="Back"><span data-feather="arrow-left-circle"></span></a>
                            <button type="submit" class="btn btn-primary">{{ __('Add Item') }}</button>
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
