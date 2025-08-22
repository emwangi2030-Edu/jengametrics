@extends('layouts.appbar')

@section('content')
<div class="row mt-5">
    <div class="col-12">
        <h3 class="font-weight-bold" style="color:#027333;">Inventory Management</h3>
        <div class="row mt-5">
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
                                <tr>
                                    <td><div class="px-2">{{ $item->name }}</div></td>
                                    <td>{{ $item->unit_of_measure }}</td>
                                    <td>{{ $item->total_stock }}</td>
                                    <td>
                                        <div class="px-2">
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
</div>
@endsection
@push('scripts')
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

