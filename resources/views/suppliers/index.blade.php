@extends('layouts.appbar')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold">Suppliers</h3>
        <a href="{{ route('materials.index') }}" class="btn btn-outline-primary">View Purchased Materials</a>
    </div>

    {{-- Success Alert --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert" id="success-alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Suppliers Table --}}
    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Name</th>
                            <th>Contact Info</th>
                            <th>Materials Supplied</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($suppliers as $supplier)
                            <tr>
                                <td class="fw-semibold">{{ $supplier->name }}</td>
                                <td>{{ $supplier->contact_info }}</td>
                                <td>
                                    @php
                                        $materialNames = $supplier->materials->pluck('name')->filter()->toArray();
                                        echo $materialNames ? implode(', ', $materialNames) : 'N/A';
                                    @endphp
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">No suppliers found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="d-flex justify-content-center mt-3">
                {{ $suppliers->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function () {
        setTimeout(function () {
            $('#success-alert').fadeOut('slow');
        }, 4000);
    });
</script>
@endpush
