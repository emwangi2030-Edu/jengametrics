@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="fw-bold mb-0 jm-ui-title">
                {{ __('Bill of Materials:') }} <span class="jm-ui-muted">{{ $project->name }}</span>
            </h2>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm border-0 jm-ui-card mb-4">
                <div class="card-body">
                    <div class="text-end">
                        <p class="fw-bold text-dark mb-0">{{ __('Total') }}: KES {{ number_format($subDocuments->sum(fn($doc) => $doc->combined_total), 2) }}</p>
                    </div>
                    @if($subDocuments->isEmpty())
                        <p class="text-muted mb-0">{{ __('No BoQs available yet.') }}</p>
                    @else
                        <div class="table-responsive jm-ui-table-wrap">
                            <table class="table align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>{{ __('BoQ') }}</th>
                                        <th class="text-end">{{ __('Material Cost (KES)') }}</th>
                                        <th class="text-end">{{ __('Labour Cost (KES)') }}</th>
                                        <th class="text-end">{{ __('Total (KES)') }}</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($subDocuments as $document)
                                        <tr>
                                            <td class="fw-semibold">
                                                <a href="{{ route('boms.documents.show', $document) }}" class="text-decoration-none">
                                                   {{ $document->title }} ({{ (int) ($document->units) }})
                                                </a>
                                            </td>
                                           <td class="text-end">{{ number_format($document->materials_total, 2) }}</td>
                                           <td class="text-end">{{ number_format($document->labour_total, 2) }}</td>
                                           <td class="text-end">{{ number_format($document->combined_total, 2) }}</td>
                                           <td class="text-end"></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>

            <div class="card shadow-sm border-0 jm-ui-card">
                <div class="card-body">
                    <!-- Document Details -->
                    <div class="mt-4">
                        <!-- Sections List -->
                        <div class="mt-5">
                            @if($sectionsWithTotals->isEmpty())
                                <p class="text-muted">{{ __('No sections found.') }}</p>
                            @else
                                <div class="table-responsive mt-3 jm-ui-table-wrap">
                                    <table class="table table-sm table-hover align-middle">
                                        <thead class="table-light">
                                            <tr>
                                                <th>{{ __('Section') }}</th>
                                                <th class="text-end">{{ __('Material Cost (KES)') }}</th>
                                                <th class="text-end">{{ __('Labour Cost (KES)') }}</th>
                                                <th class="text-end"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($sectionsWithTotals as $entry)
                                            <tr>
                                                <td class="fw-semibold p-2">
                                                    <a href="{{ route('boms.show', $entry->section->id) }}" class="text-decoration-none">
                                                        {{ $entry->section->name }}
                                                    </a>
                                                </td>
                                                <td class="text-end fw-bold">{{ number_format($entry->total_section_material, 2) }}</td>
                                                <td class="text-end fw-bold">{{ number_format($entry->total_section_labour, 2) }}</td>
                                                <td class="text-end"></td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                            <div class="jm-ui-surface p-3">
                                <table class="table table-borderless">
                                    <thead class="p-0 m-0">
                                        <tr class="p-0 m-0">
                                            <th class="p-0 m-0">{{ __('') }}</th>
                                            <th class="p-0 m-0">{{ __('') }}</th>
                                            <th class="p-0 m-0">{{ __('') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="fw-bold">{{ __('TOTAL ESTIMATED MATERIAL COST') }}</td>
                                            <td></td>
                                            <td class="fw-bold text-end">KES {{ number_format($totalAmount, 2) }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">{{ __('TOTAL ESTIMATED LABOUR COST') }}</td>
                                            <td></td>
                                            <td class="fw-bold text-end">KES {{ number_format($totalLabour, 2) }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">{{ __('TOTAL ESTIMATED COMBINED COST') }}</td>
                                            <td></td>
                                            <td class="fw-bold text-end">KES {{ number_format($totalAmount + $totalLabour, 2) }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
