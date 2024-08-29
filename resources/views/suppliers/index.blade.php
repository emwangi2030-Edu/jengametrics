@extends('layouts.appbar')

@section('content')
<div class="container">
    <h1>Suppliers</h1>

    <a href="{{ route('materials.index') }}" class="btn btn-primary mb-3">Materials Purchased</a>

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
                        @foreach($supplier->materials as $material)
                            {{ $material->name }},
                        @endforeach
                    </td>
                    <td>{{ $supplier->created_at->format('Y-m-d') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
