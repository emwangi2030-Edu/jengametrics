@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="row mb-4">
        <div class="col-12 d-flex flex-column flex-md-row justify-content-between align-items-md-center">
            <div>
                <h2 class="jm-page-title jm-ui-title">{{ $document->title }}</h2>
                <p class="jm-ui-muted mb-0">{{ $document->description }}</p>
            </div>
            <div class="text-end mt-3 mt-md-0">
                <p class="fs-4 fw-bold mb-1">KES {{ number_format($document->combined_total, 2) }}</p>
                <p class="text-muted mb-0">{{ __('Total (Materials + Labour)') }}</p>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-12">
            <a href="{{ route('boms.index') }}" class="btn btn-outline-secondary me-2" aria-label="Back" title="Back"><span data-feather="arrow-left-circle"></span></a>
            <a href="{{ route('bq_documents.show', $document) }}" class="btn btn-success">{{ __('View BoQ') }}</a>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-10">
            @forelse($document->sections as $section)
                <div class="card jm-ui-card shadow-sm border-0 mb-4">
                    <div class="card-body">
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center">
                            <div>
                                <h5 class="fw-bold mb-1 text-success">{{ $section->section_name ?? __('Unassigned Section') }}</h5>
                                <p class="text-muted mb-0">{{ __('BoQ Items: :count', ['count' => $section->item_count ?? 0]) }}</p>
                            </div>
                            <div class="text-end mt-3 mt-md-0">
                                @php($documentUnits = max(1, (int) ($document->units ?? 1)))
                                @php($sectionQuantity = ($section->quantity ?? 0) * $documentUnits)
                                <p class="mb-1">{{ __('Total Quantity:') }} {{ is_int($sectionQuantity) ? $sectionQuantity : number_format($sectionQuantity, 2) }} {{ $section->units ?? 'N/A' }}</p>
                                <p class="mb-1">{{ __('BoQ Amount Sum:') }} KES {{ number_format((float) ($section->amount ?? 0), 2) }}</p>
                                <p class="fw-bold mb-0">{{ __('Materials Total:') }} KES {{ number_format((float) ($section->material_total ?? 0), 2) }}</p>
                            </div>
                        </div>

                        @if(($section->materials ?? collect())->isEmpty())
                            <div class="border rounded-3 p-4 mt-3 text-center text-muted">
                                {{ __('No materials are mapped to this BoQ item yet.') }}
                            </div>
                        @else
                            <div class="table-responsive mt-3 jm-ui-table-wrap">
                                <table class="table table-sm align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>{{ __('Material') }}</th>
                                            <th>{{ __('Unit') }}</th>
                                            <th class="text-end">{{ __('Quantity') }}</th>
                                            <th class="text-end">{{ __('Rate (KES)') }}</th>
                                            <th class="text-end">{{ __('Amount (KES)') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($section->materials as $material)
                                            <tr>
                                                <td>{{ $material->name ?? __('Unknown Material') }}</td>
                                                <td>{{ $material->unit ?? 'N/A' }}</td>
                                                @php($materialQuantity = $material->quantity ?? 0)
                                                <td class="text-end">{{ is_int($materialQuantity) ? $materialQuantity : number_format($materialQuantity, 2) }}</td>
                                                <td class="text-end">{{ number_format((float) ($material->rate ?? 0), 2) }}</td>
                                                <td class="text-end">{{ number_format((float) ($material->amount ?? 0), 2) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="card jm-ui-card shadow-sm border-0">
                    <div class="card-body text-center py-5">
                        <p class="text-muted mb-0">{{ __('No BoQ items found for this document yet.') }}</p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
