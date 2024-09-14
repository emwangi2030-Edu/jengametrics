@extends('layouts.appbar')

@section('content')
<div class="container">
    <h1>Suppliers</h1>

    {{-- Error handling for success messages --}}
    @if(session('success'))
        <div class="alert alert-success" id="success-alert" style="display: block;">
            {{ session('success') }}
        </div>
    @endif

    {{-- Link to navigate to materials purchased --}}
    <a href="{{ route('materials.index') }}" class="btn btn-primary mb-3">Materials Purchased</a>

    {{-- Suppliers Table --}}
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Name</th>
                <th>Contact Info</th>
                <th>Material Supplied</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($suppliers as $supplier)
                <tr>
                    <td>{{ $supplier->name }}</td>
                    <td>{{ $supplier->contact_info }}</td>
                    <td>
                        @php
                            $materialNames = $supplier->materials->pluck('name')->toArray();
                            echo implode(', ', $materialNames);
                        @endphp
                    </td>
                    <td>{{ $supplier->created_at->format('Y-m-d') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Pagination Links --}}
    {{ $suppliers->links() }} 
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        setTimeout(function() {
            $('#success-alert').fadeOut('slow');
        }, 4000); // Success alert fades out after 4 seconds
    });
</script>
@endpush
