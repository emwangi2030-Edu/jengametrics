@extends('layouts.appbar')

@section('content')
<div class="container py-4">
    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h2 class="font-weight-bold" style="color:#027333">
                Materials Purchased: <span class="text-black">{{ $project->name }}</span>
            </h2>
            <div>
                <a href="{{ route('materials.create') }}" class="btn btn-success me-2">
                    {{ __('Receive Approved Materials') }}
                </a>
                <a href="{{ route('suppliers.index') }}" class="btn btn-outline-secondary">
                    {{ __('Suppliers List') }}
                </a>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <table class="table mt-3">
                        <thead class="table-light">
                            <tr>
                                <th>{{ __('Name') }}</th>
                                <th>{{ __('Unit Price') }}</th>
                                <th>{{ __('Unit of Measure') }}</th>
                                <th>{{ __('Quantity') }}</th>
                                <th>{{ __('Total Amount') }}</th>
                                <th>{{ __('Supplier Name') }}</th>
                                <th>{{ __('Date') }}</th>
                                <th>{{ __('Receipt') }}</th>
                                <!-- <th>{{ __('Actions') }}</th> -->
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($materials as $material)
                                <tr>
                                    <td>{{ $material->product->name ?? 'N/A' }}</td>
                                    <td>{{ number_format($material->unit_price, 2) }}</td>
                                    <td>{{ $material->unit_of_measure }}</td>
                                    <td>{{ (int) $material->quantity_purchased }}</td>
                                    <td>{{ number_format($material->unit_price * $material->quantity_purchased, 2) }}</td>
                                    <td>{{ $material->supplier->name ?? 'N/A' }}</td>
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
                                    <!-- <td class="d-flex gap-1">
                                        <a href="{{ route('materials.edit', $material->id) }}" class="btn btn-warning btn-sm">
                                            {{ __('Edit') }}
                                        </a>
                                        <form action="{{ route('materials.destroy', $material->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this material?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">
                                                {{ __('Delete') }}
                                            </button>
                                        </form>
                                    </td> -->
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
                <table class="table table-bordered mt-3">
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
                            <tr>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->unit_of_measure }}</td>
                                <td>{{ $item->total_stock }}</td>
                                <td>
                                    <form action="{{ route('materials.use', $item->product_id) }}" method="POST" class="d-flex gap-2">
                                        @csrf
                                        <input type="number" name="quantity_used" class="form-control form-control-sm quantity-used" placeholder="Qty" step="0.01" required data-total-stock="{{ $item->total_stock }}">
                                        <select name="section_id" class="form-select form-select-sm" required>
                                            <option value="" disabled selected>Select Section</option>
                                            @foreach($sections as $section)
                                                <option value="{{ $section->id }}">{{ $section->name }}</option>
                                            @endforeach
                                        </select>
                                        <button type="submit" class="btn btn-warning btn-sm">Issue</button>
                                    </form>
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
    <div class="row mt-5">
    <div class="col-12">
        <h3 class="font-weight-bold" style="color:#027333;">Stock Usage History</h3>
        <div class="card shadow-sm">
            <div class="card-body">
                <table class="table table-bordered mt-3">
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
                                <td>{{ $usage->created_at->format('Y-m-d') }}</td>
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
