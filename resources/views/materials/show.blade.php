@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="jm-page-header">
        <div>
            <h2 class="jm-page-title jm-ui-title">{{ __('Material Details') }}</h2>
            <p class="jm-page-subtitle jm-ui-muted mb-0">{{ $material->name }}</p>
        </div>
        <a href="{{ route('materials.index') }}" class="btn btn-outline-secondary" aria-label="Back" title="Back"><span data-feather="arrow-left-circle"></span></a>
    </div>

    <div class="card jm-ui-card shadow-sm border-0">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <strong>{{ __('Name') }}:</strong>
                    <div>{{ $material->name }}</div>
                </div>
                <div class="col-md-6">
                    <strong>{{ __('Unit of Measure') }}:</strong>
                    <div>{{ $material->unit_of_measure ?? 'N/A' }}</div>
                </div>
                <div class="col-md-6">
                    <strong>{{ __('Unit Price') }}:</strong>
                    <div>{{ number_format((float) $material->unit_price, 2) }}</div>
                </div>
                <div class="col-md-6">
                    <strong>{{ __('Quantity Purchased') }}:</strong>
                    <div>{{ number_format((float) $material->quantity_purchased, 2) }}</div>
                </div>
                <div class="col-md-6">
                    <strong>{{ __('Quantity in Stock') }}:</strong>
                    <div>{{ number_format((float) $material->quantity_in_stock, 2) }}</div>
                </div>
                <div class="col-md-6">
                    <strong>{{ __('Supplier') }}:</strong>
                    <div>{{ optional($material->supplier)->name ?? 'N/A' }}</div>
                </div>
            </div>

            <div class="d-flex gap-2 mt-4">
                <a href="{{ route('materials.edit', $material->id) }}" class="btn btn-primary">{{ __('Edit') }}</a>
                <a href="{{ route('materials.index') }}" class="btn btn-outline-secondary">{{ __('Back to Materials') }}</a>
            </div>
        </div>
    </div>
</div>
@endsection
