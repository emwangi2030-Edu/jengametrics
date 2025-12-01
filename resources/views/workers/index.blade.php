@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h2 class="font-weight-bold" style="color:#027333;">
                Manage Labour: <span class="text-black">{{ $project->name }}</span>
            </h2>
            <div class="d-flex gap-1">
                <a href="{{ route('workers.create') }}" class="btn btn-success">
                    {{ __('Add Worker') }}
                </a>
                <a href="{{ route('attendance.create') }}" class="btn btn-info">
                    {{ __('Daily Attendance') }} 
                </a>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-center">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <form method="GET" class="row g-3 align-items-end mb-3" id="statusFilterForm">
                        <div class="col-md-4 col-lg-3">
                            <label for="status-filter" class="form-label mb-1">{{ __('Status') }}</label>
                            <select id="status-filter" name="status" class="form-select">
                                <option value="active" @selected(($status ?? '') === 'active')>{{ __('Active') }}</option>
                                <option value="terminated" @selected(($status ?? '') === 'terminated')>{{ __('Terminated') }}</option>
                                <option value="all" @selected(($status ?? '') === 'all')>{{ __('All') }}</option>
                            </select>
                        </div>
                    </form>
                    <div id="workers-table-wrapper">
                        @include('workers.partials.table', ['workers' => $workers])
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const alertBox = document.getElementById('success-alert');
        if (alertBox) {
            setTimeout(() => {
                alertBox.classList.remove('show');
                alertBox.classList.add('fade');
            }, 4000);
        }

        const statusSelect = document.getElementById('status-filter');
        const filterForm = document.getElementById('statusFilterForm');
        if (statusSelect && filterForm) {
            statusSelect.addEventListener('change', () => {
                const wrapper = document.getElementById('workers-table-wrapper');
                if (!wrapper) {
                    filterForm.submit();
                    return;
                }
                const params = new URLSearchParams(new FormData(filterForm));
                fetch(`{{ route('workers.index') }}?${params.toString()}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    cache: 'no-store'
                })
                    .then(res => res.text())
                    .then(html => {
                        wrapper.innerHTML = html;
                    })
                    .catch(() => filterForm.submit());
            });
        }
    });
</script>
@endpush
