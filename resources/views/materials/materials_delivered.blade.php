@extends('layouts.app')

@section('content')
<div class="row py-4">
    <h2 class="font-weight-bold" style="color:#027333">
        Material Management
    </h2>
    <div class="d-flex justify-content-between w-100 flex-wrap gap-2">
        <div>
            <button type="button" class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#requisitionModal">
                Requisition Material
            </button>
        </div>
        <div>
            <a href="{{ route('materials.create') }}" class="btn btn-success me-2">
                {{ __('Receive Approved Materials') }}
            </a>
            <!-- <a href="{{ route('suppliers.index') }}" class="btn btn-warning">
                {{ __('Suppliers List') }}
            </a> -->
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-md-12">
        <h3 class="font-weight-bold" style="color:#027333">Materials Delivered</h3>
        <div class="card shadow-sm">
            <form method="GET" action="{{ route('materials.delivered') }}" class="row g-2 mt-2 justify-content-center" id="materials-delivered-filters">
                <div class="col-md-3">
                    <select name="filter" class="form-select">
                        <option value="">All Time</option>
                        <option value="week" {{ request('filter') == 'week' ? 'selected' : '' }}>This Week</option>
                        <option value="month" {{ request('filter') == 'month' ? 'selected' : '' }}>This Month</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="year" class="form-select">
                        @foreach($availableYears as $y)
                            <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="supplier_id" class="form-select">
                        <option value="">All Suppliers</option>
                        @foreach($suppliers as $s)
                            <option value="{{ $s->id }}" {{ request('supplier_id') == $s->id ? 'selected' : '' }}>
                                {{ $s->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </form>
            <div class="card-body" id="materials-delivered-results">
                @include('materials.partials.delivered_table', ['materials' => $materials])
            </div>
        </div>
    </div>
</div>

@include('requisitions.requisition_modal')
@include('requisitions.adhoc_modal')

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const form = document.getElementById('materials-delivered-filters');
        const results = document.getElementById('materials-delivered-results');

        if (!form || !results) {
            return;
        }

        const fetchMaterials = () => {
            const params = new URLSearchParams(new FormData(form));
            const url = `${form.action}?${params.toString()}`;

            results.innerHTML = '<div class="py-5 text-center text-muted">{{ __('Loading...') }}</div>';

            fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then((response) => {
                    if (!response.ok) {
                        throw new Error('Network problem');
                    }
                    return response.json();
                })
                .then((data) => {
                    results.innerHTML = data.table ?? '';
                })
                .catch(() => {
                    results.innerHTML = '<div class="alert alert-danger" role="alert">{{ __('Failed to load data. Please try again.') }}</div>';
                });
        };

        form.addEventListener('change', fetchMaterials);
        form.addEventListener('submit', (event) => {
            event.preventDefault();
            fetchMaterials();
        });
    });
</script>
@endpush
@endsection
