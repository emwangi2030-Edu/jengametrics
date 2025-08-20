@extends('layouts.appbar')

@section('content')
<div class="row mt-5">
    <div class="col-12">
        <h3 class="font-weight-bold" style="color:#027333;">Stock Usage History</h3>
        <div class="card shadow-sm">
            <form method="GET" action="{{ route('materials.index') }}" class="row g-2 mt-2 justify-content-center">
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
                    <select name="year" class="form-select" onchange="this.form.submit()">
                        @foreach($availableYears as $y)
                            <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                </div>
            </form>
            <div class="card-body">
                <table class="table table-bordered mt-3 text-center">
                    <thead class="table-light">
                        <tr>
                            <th>{{ __('Date Issued') }}</th>
                            <th>{{ __('Material') }}</th>
                            <th>{{ __('Section') }}</th>
                            <th>{{ __('Quantity Used') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($stockUsages as $usage)
                            <tr>
                                <td><div class="px-2">{{ $usage->created_at->format('Y-m-d') }}</div></td>
                                <td>{{ $usage->material->name ?? 'N/A' }}</td>
                                <td>{{ $usage->section->name ?? 'N/A' }}</td>
                                <td>{{ $usage->quantity_used }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">{{ __('No stock usage history found.') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

