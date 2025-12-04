@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
        <div>
            <h2 class="font-weight-bold mb-1" style="color:#027333">
                {{ __('Purchases Report') }}
            </h2>
            <p class="text-muted mb-0">{{ __('Materials purchased for this project.') }}</p>
        </div>
        <div class="mt-3 mt-md-0 d-flex gap-2">
            <a href="{{ route('reports.purchases', ['download' => 1]) }}" class="btn btn-outline-primary btn-sm">
                {{ __('Download Excel') }}
            </a>
            <a href="{{ route('reports') }}" class="btn btn-outline-secondary btn-sm">
                {{ __('Back') }}
            </a>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-sm align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>{{ __('Material') }}</th>
                            <th>{{ __('Supplier') }}</th>
                            <th>{{ __('Unit') }}</th>
                            <th class="text-end">{{ __('Unit Price (KES)') }}</th>
                            <th class="text-end">{{ __('Quantity Purchased') }}</th>
                            <th class="text-end">{{ __('Total Cost (KES)') }}</th>
                            <th>{{ __('Date Purchased') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($materials as $material)
                            <tr>
                                <td>{{ $material->name }}</td>
                                <td>{{ $material->supplier->name ?? __('N/A') }}</td>
                                <td>{{ $material->unit_of_measure ?? 'N/A' }}</td>
                                <td class="text-end">{{ number_format((float) $material->unit_price, 2) }}</td>
                                <td class="text-end">{{ number_format((float) $material->quantity_purchased, 2) }}</td>
                                <td class="text-end">
                                    {{ number_format((float) $material->unit_price * (float) $material->quantity_purchased, 2) }}
                                </td>
                                <td>{{ optional($material->created_at)->format('d M Y') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    {{ __('No purchases found.') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
