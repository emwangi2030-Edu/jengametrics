@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="jm-page-header">
        <div>
            <h2 class="jm-page-title jm-ui-title">{{ __('Supplier Details') }}</h2>
            <p class="jm-page-subtitle jm-ui-muted mb-0">{{ $supplier->name }}</p>
        </div>
        <a href="{{ route('suppliers.index') }}" class="btn btn-outline-secondary" aria-label="Back" title="Back"><span data-feather="arrow-left-circle"></span></a>
    </div>

    <div class="card jm-ui-card shadow-sm border-0 mb-4">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <strong>{{ __('Name') }}:</strong>
                    <div>{{ $supplier->name }}</div>
                </div>
                <div class="col-md-6">
                    <strong>{{ __('Contact Info') }}:</strong>
                    <div>{{ $supplier->contact_info ?: 'N/A' }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card jm-ui-card shadow-sm border-0">
        <div class="card-body">
            <h5 class="jm-section-title mb-3">{{ __('Materials Supplied') }}</h5>
            <div class="table-responsive jm-ui-table-wrap">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>{{ __('Material') }}</th>
                            <th>{{ __('Unit') }}</th>
                            <th class="text-end">{{ __('Unit Price') }}</th>
                            <th class="text-end">{{ __('Quantity Purchased') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($supplier->materials as $material)
                            <tr>
                                <td>{{ $material->name }}</td>
                                <td>{{ $material->unit_of_measure ?? 'N/A' }}</td>
                                <td class="text-end">{{ number_format((float) $material->unit_price, 2) }}</td>
                                <td class="text-end">{{ number_format((float) $material->quantity_purchased, 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">{{ __('No materials found for this supplier.') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
