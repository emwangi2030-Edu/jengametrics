@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="jm-page-header">
        <div>
            <h2 class="jm-page-title">
                {{ __('Purchases Report') }}
            </h2>
            <p class="jm-page-subtitle mb-0">{{ __('Review purchase spend by period and export to Excel.') }}</p>
        </div>
        <div class="mt-3 mt-md-0 d-flex gap-2">
            <a href="{{ route('reports.purchases', ['download' => 1]) }}" class="btn btn-outline-primary btn-sm">
                {{ __('Download Excel') }}
            </a>
            <a href="{{ route('reports') }}" class="btn btn-outline-secondary btn-sm" aria-label="Back" title="Back"><span data-feather="arrow-left-circle"></span></a>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <form method="GET" class="row g-3 align-items-end mb-3" id="purchasesFilterForm">
                <div class="col-md-3">
                    <label class="form-label mb-1" for="purchases-year">{{ __('Year') }}</label>
                    <select name="year" id="purchases-year" class="form-select">
                        <option value="">{{ __('All Years') }}</option>
                        @foreach($years as $y)
                            <option value="{{ $y }}" @selected($selectedYear == $y)>{{ $y }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label mb-1" for="purchases-month">{{ __('Month') }}</label>
                    <select name="month" id="purchases-month" class="form-select">
                        <option value="">{{ __('All Months') }}</option>
                        @foreach(range(1,12) as $m)
                            <option value="{{ $m }}" @selected($selectedMonth == $m)>{{ \Carbon\Carbon::create()->month($m)->format('F') }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <a href="{{ route('reports.purchases') }}" class="btn btn-outline-secondary mt-auto">{{ __('Reset') }}</a>
                </div>
            </form>
            <div id="purchases-table-wrapper">
                @include('report.partials.purchases_table', ['materials' => $materials])
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('purchasesFilterForm');
        const year = document.getElementById('purchases-year');
        const month = document.getElementById('purchases-month');
        const tableWrapper = document.querySelector('.card .table-responsive')?.parentElement;

        const submitAjax = () => {
            if (!form || !tableWrapper) return;
            const params = new URLSearchParams(new FormData(form));
            const url = `{{ route('reports.purchases') }}?${params.toString()}`;

            tableWrapper.innerHTML = '<div class="py-4 text-center text-muted">{{ __('Loading...') }}</div>';

            fetch(url, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                cache: 'no-store'
            })
                .then(res => res.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const newTable = doc.querySelector('.card .table-responsive')?.parentElement;
                    if (newTable) {
                        tableWrapper.innerHTML = newTable.innerHTML;
                    } else {
                        window.location.href = url;
                    }
                })
                .catch(() => window.location.href = url);
        };

        if (year) year.addEventListener('change', submitAjax);
        if (month) month.addEventListener('change', submitAjax);
    });
</script>
@endpush
