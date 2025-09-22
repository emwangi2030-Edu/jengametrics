@extends('layouts.app')

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
                    @php
                        $formatNumeric = function ($value) {
                            if ($value === null) {
                                return null;
                            }

                            $numeric = round((float) $value, 2);

                            return abs($numeric - round($numeric)) < 0.005
                                ? (int) round($numeric)
                                : number_format($numeric, 2);
                        };
                    @endphp
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
                                @php
                                    $requisitionedQty = $material->requisitioned_quantity;
                                    if ($requisitionedQty === null && $material->variance !== null && $material->quantity_purchased !== null) {
                                        $requisitionedQty = (float) $material->quantity_purchased - (float) $material->variance;
                                    }
                                    $variance = (float) ($material->variance ?? 0);
                                    $displayRequisitioned = $requisitionedQty !== null ? $formatNumeric($requisitionedQty) : null;
                                    $displayQuantity = $formatNumeric($material->quantity_purchased);
                                    $displayVariance = $formatNumeric($variance);
                                @endphp
                                <tr>
                                    <td><div class="px-2">{{ $material->product->name ?? $material->name }}</div></td>
                                    <td>{{ $displayRequisitioned !== null ? $displayRequisitioned : 'N/A' }}</td>
                                    <td>{{ $displayQuantity }}</td>
                                    <td class="{{ $variance > 0 ? 'text-success' : ($variance < 0 ? 'text-danger' : 'text-secondary') }}">
                                        {{ $variance > 0 ? '+' : '' }}{{ $displayVariance }}
                                    </td>
                                    <td>{{ $material->unit_of_measure }}</td>
                                    <td>{{ number_format($material->unit_price, 2) }}</td>
                                    <td>{{ number_format($material->unit_price * $material->quantity_purchased, 2) }}</td>
                                    <td>{{ $material->supplier->name }}</td>
                                    <td>{{ $material->created_at->format('d-m-Y') }}</td>
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

    <div class="row mt-5">
        <div class="col-12">
            <h3 class="font-weight-bold" style="color:#027333;">Inventory Management</h3>
            <div class="card shadow-sm">
                <div class="card-body">
                    <table class="table table-bordered mt-3 text-center">
                        <thead class="table-light">
                            <tr>
                                <th>{{ __('Name') }}</th>
                                <th>{{ __('Unit of Measure') }}</th>
                                <th>{{ __('Total Quantity in Stock') }}</th>
                                <th>{{ __('Issue Stock') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($inventory as $item)
                                @php
                                    $inventoryIsAdhoc = empty($item->product_id);
                                    $inventoryRouteKey = $inventoryIsAdhoc
                                        ? 'adhoc-' . md5($item->name . '|' . $item->unit_of_measure)
                                        : $item->product_id;
                                @endphp
                                <tr>
                                    <td><div class="px-2">{{ $item->name }}</div></td>
                                    <td>{{ $item->unit_of_measure }}</td>
                                    <td>{{ $item->total_stock }}</td>
                                    <td>
                                        <div class="px-2">
                                            <form action="{{ route('materials.use', $inventoryRouteKey) }}" method="POST" class="d-flex gap-2">
                                                @csrf
                                                @if($inventoryIsAdhoc)
                                                    <input type="hidden" name="adhoc_name" value="{{ $item->name }}">
                                                    <input type="hidden" name="adhoc_unit" value="{{ $item->unit_of_measure }}">
                                                @endif
                                                <input type="number" name="quantity_used" class="form-control form-control-sm quantity-used" placeholder="Qty" step="0.01" required data-total-stock="{{ $item->total_stock }}">
                                                <select name="section_id" class="form-select form-select-sm" required>
                                                    <option value="" disabled selected>Select Section</option>
                                                    @foreach($sections as $section)
                                                        <option value="{{ $section->id }}">{{ $section->name }}</option>
                                                    @endforeach
                                                </select>
                                                <button type="submit" class="btn btn-warning btn-sm">Issue</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">{{ __('No inventory found.') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
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
                                    <td><div class="px-2">{{ $usage->created_at->format('d-m-Y') }}</div></td>
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
     @include('requisitions.requisition_modal')
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
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.quantity-used').forEach(function(input) {
            input.addEventListener('input', function () {
                const totalStock = parseFloat(this.getAttribute('data-total-stock')) || 0;
                const entered = parseFloat(this.value) || 0;
                if (entered > totalStock) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Insufficient Stock',
                        text: `You cannot issue more than ${totalStock} units.`,
                        confirmButtonColor: '#027333'
                    }).then(() => {
                        this.value = totalStock;
                    });
                }
            });
        });
    });
</script>
@endpush
