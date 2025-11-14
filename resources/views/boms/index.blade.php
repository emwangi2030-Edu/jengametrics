@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="font-weight-bold" style="color:#027333">
                Bill of Materials: <span class="text-black">{{ $project->name }}</span>
            </h2>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <div class="text-end">
                        <p class="fw-bold text-dark mb-0">{{ __('Total') }}: KES {{ number_format($subDocuments->sum(fn($doc) => $doc->combined_total), 2) }}</p>
                    </div>
                    @if($subDocuments->isEmpty())
                        <p class="text-muted mb-0">{{ __('No sub BoQs available yet.') }}</p>
                    @else
                        <div class="table-responsive">
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
                                            <td class="fw-semibold">{{ $document->title }}</td>
                                           <td class="text-end">{{ number_format($document->materials_total, 2) }}</td>
                                           <td class="text-end">{{ number_format($document->labour_total, 2) }}</td>
                                           <td class="text-end">{{ number_format($document->combined_total, 2) }}</td>
                                           <td class="text-end">
                                                <a href="{{ route('boms.documents.show', $document) }}" class="btn btn-outline-primary btn-sm">
                                                    {{ __('Open BoM') }}
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <!-- Document Details -->
                    <div class="mt-4">
                        <!-- Sections List -->
                        <div class="mt-5">
                            @if($sections->isEmpty())
                                <p class="text-muted">{{ __('No sections found.') }}</p>
                            @else
                                <div class="table-responsive mt-3">
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
                                            @foreach($sections as $section)
                                                @php
                                                    $total_section_material = \App\Models\BomItem::whereProjectId(project_id())
                                                        ->where('section_id', $section->id)
                                                        ->selectRaw('SUM(quantity * rate) as total')
                                                        ->value('total');
                                                    $total_section_labour = \App\Models\BomLabour::whereProjectId(project_id())
                                                        ->where('section_id', $section->id)
                                                        ->sum('amount');
                                                @endphp
                                                <tr>
                                                    <td class="fw-semibold p-2">{{ $section->name }}</td>
                                                    <td class="text-end fw-bold">{{ number_format($total_section_material, 2) }}</td>
                                                    <td class="text-end fw-bold">{{ number_format($total_section_labour, 2) }}</td>
                                                    <td class="text-end">
                                                        <a href="{{ route('boms.show', $section->id) }}" class="btn btn-outline-primary btn-sm">
                                                            {{ __('View Section') }}
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                            <div class="bg-secondary bg-opacity-10 text-black border-0 rounded p-3">
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

