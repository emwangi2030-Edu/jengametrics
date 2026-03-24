@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="jm-page-header">
        <div>
            <h2 class="jm-page-title jm-ui-title">{{ __('Stock Usage History') }}</h2>
            <p class="jm-page-subtitle jm-ui-muted mb-0">{{ __('Review issued material quantities by period and section.') }}</p>
        </div>
    </div>
    <div class="col-12">
        <h3 class="jm-section-title">{{ __('Usage Records') }}</h3>
        <div class="card jm-ui-card shadow-sm border-0">
            <form method="GET" action="{{ route('materials.usage') }}" class="row g-2 mt-2 justify-content-center jm-ui-surface mx-3 p-3" id="stock-usage-filters">
                <div class="col-md-3">
                    <select name="filter" class="form-select">
                        <option value="">All Time</option>
                        <option value="week" {{ request('filter') == 'week' ? 'selected' : '' }}>This Week</option>
                        <option value="month" {{ request('filter') == 'month' ? 'selected' : '' }}>This Month</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="section_id" class="form-select">
                        <option value="">All Sections</option>
                        @foreach($sections as $section)
                            <option value="{{ $section->id }}" {{ request('section_id') == $section->id ? 'selected' : '' }}>
                                {{ $section->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="year" class="form-select">
                        @foreach($availableYears as $y)
                            <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endforeach
                    </select>
                </div>
            </form>
            <div class="card-body" id="stock-usage-results">
                @include('materials.partials.stock_usage_table', ['stockUsages' => $stockUsages])
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const form = document.getElementById('stock-usage-filters');
        const results = document.getElementById('stock-usage-results');

        if (!form || !results) {
            return;
        }

        const fetchUsage = () => {
            const params = new URLSearchParams(new FormData(form));
            const url = `${form.action}?${params.toString()}`;

            results.innerHTML = '<div class="py-5 text-center text-muted">{{ __('Loading usage records...') }}</div>';

            fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then((response) => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then((data) => {
                    results.innerHTML = data.table ?? '';
                })
                .catch(() => {
                    results.innerHTML = '<div class="alert alert-danger" role="alert">{{ __('Could not load usage records. Please try again.') }}</div>';
                });
        };

        form.addEventListener('change', fetchUsage);
        form.addEventListener('submit', (event) => {
            event.preventDefault();
            fetchUsage();
        });
    });
</script>
@endpush
@endsection
