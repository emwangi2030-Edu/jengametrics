@extends('layouts.appbar')

@section('content')
<div class="container py-4">
    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h2 class="font-weight-bold text-dark">{{ __('Materials Purchased') }}</h2>
            <div>
                <a href="{{ route('materials.create') }}" class="btn btn-success me-2">
                    {{ __('Add New Material') }}
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
                    @if(session('success'))
                        <div id="success-alert" class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <table class="table table-striped mt-3">
                        <thead class="table-light">
                            <tr>
                                <th>{{ __('Name') }}</th>
                                <th>{{ __('Unit Price') }}</th>
                                <th>{{ __('Unit of Measure') }}</th>
                                <th>{{ __('Quantity') }}</th>
                                <th>{{ __('Total Amount') }}</th>
                                <th>{{ __('Supplier Name') }}</th>
                                <th>{{ __('Date Added') }}</th>
                                <th>{{ __('Documents') }}</th>
                                <th>{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($materials as $material)
                                <tr>
                                    <td>{{ $material->product->name ?? 'N/A' }}</td>
                                    <td>{{ number_format($material->unit_price, 2) }}</td>
                                    <td>{{ $material->unit_of_measure }}</td>
                                    <td>{{ $material->quantity_in_stock }}</td>
                                    <td>{{ number_format($material->unit_price * $material->quantity_in_stock, 2) }}</td>
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
                                    <td class="d-flex gap-1">
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
@endpush
