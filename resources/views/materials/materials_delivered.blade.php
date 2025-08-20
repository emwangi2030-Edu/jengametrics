@extends('layouts.appbar')

@section('content')
<div class="row py-4">
    <h2 class="font-weight-bold" style="color:#027333">
        Material Management: <span class="text-black">{{ $project->name }}</span>
    </h2>
    <div class="d-flex justify-content-between w-100 flex-wrap gap-2">
        <div>
            <button type="button" class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#requisitionModal">
                Requisition Material
            </button>
            <a href="{{ route('requisitions.index') }}" class="btn btn-secondary">
                Requisition List
            </a>
        </div>
        <div>
            <a href="{{ route('materials.create') }}" class="btn btn-success me-2">
                {{ __('Receive Approved Materials') }}
            </a>
            <a href="{{ route('suppliers.index') }}" class="btn btn-warning">
                {{ __('Suppliers List') }}
            </a>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-md-12">
        <h3 class="font-weight-bold" style="color:#027333">Materials Delivered</h3>
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
                            <th>{{ __('Name') }}</th>
                            <th>{{ __('Requisitioned Quantity') }}</th>
                            <th>{{ __('Quantity Received') }}</th>
                            <th>{{ __('Variance') }}</th>
                            <th>{{ __('UoM') }}</th>
                            <th>{{ __('Unit Price') }}</th>
                            <th>{{ __('Total Amount') }}</th>
                            <th>{{ __('Supplier Name') }}</th>
                            <th>{{ __('Date') }}</th>
                            <th>{{ __('Receipt') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($materials as $material)
                            <tr>
                                <td><div class="px-2">{{ $material->product->name }}</div></td>
                                <td>{{ (int) $material->requisitioned_quantity }}</td>
                                <td>{{ (int) $material->quantity_purchased }}</td>
                                @php $variance = (int) $material->variance; @endphp
                                <td class="{{ $variance > 0 ? 'text-success' : ($variance < 0 ? 'text-danger' : 'text-secondary') }}">
                                    {{ $variance > 0 ? '+' . $variance : $variance }}
                                </td>
                                <td>{{ $material->unit_of_measure }}</td>
                                <td>{{ number_format($material->unit_price, 2) }}</td>
                                <td>{{ number_format($material->unit_price * $material->quantity_purchased, 2) }}</td>
                                <td>{{ $material->supplier->name }}</td>
                                <td>{{ $material->created_at->format('Y-m-d') }}</td>
                                <td>
                                    @if($material->document)
                                        <a href="{{ route('materials.viewDocument', $material->id) }}" class="text-decoration-underline">
                                            {{ __('View') }}
                                        </a>
                                    @else
                                        <span class="text-muted">None</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                @if($materials->isEmpty())
                    <p class="text-center mt-4">{{ __('No materials found.') }}</p>
                @endif
            </div>
        </div>
    </div>
</div>

@include('requisitions.requisition_modal')
@endsection
