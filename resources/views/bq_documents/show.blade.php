@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <div class="row mb-4">
            <div class="col-12 d-flex flex-column flex-md-row justify-content-between align-items-md-center">
                <div>
                    <h2 class="fw-bold" style="color:#027333">
                        {{ $bqDocument->title }}
                    </h2>
                    <p class="text-muted mb-0">{{ $bqDocument->description }}</p>
                </div>
                <div class="text-end mt-3 mt-md-0">
                    <p class="fs-4 fw-bold mb-1">KES {{ number_format($totalAmount, 2) }}</p>
                    <p class="text-muted mb-0">{{ __('Sub BoQ total') }}</p>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-12">
                <a href="{{ route('bq_sections.create', $bqDocument) }}" class="btn text-white" style="background-color:#027333">
                    {{ __('Add BoQ Item') }}
                </a>
                <a href="{{ route('bq_documents.index') }}" class="btn btn-outline-secondary ms-2">
                    {{ __('Back to Master BoQ') }}
                </a>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-10">
                @if($sectionGroups->isEmpty())
                    <div class="card shadow-sm border-0">
                        <div class="card-body text-center py-5">
                            <p class="text-muted mb-0">{{ __('No sections added to this BoQ yet.') }}</p>
                        </div>
                    </div>
                @else
                    @foreach($sectionGroups as $sectionId => $group)
                        <div class="card shadow-sm border-0 mb-4">
                            <div class="card-body">
                                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center">
                                    <div>
                                        <h5 class="fw-bold mb-1">{{ optional($group['section'])->name ?? __('Unnamed Section') }}</h5>
                                    </div>
                                    <div class="text-end">
                                        <p class="fw-bold fs-5 mb-1">KES {{ number_format($group['total'], 2) }}</p>
                                        @if($sectionId)
                                            <a href="{{ route('bq_sections.show', [$bqDocument, $sectionId]) }}" class="btn btn-outline-primary btn-sm">
                                                {{ __('Manage Items') }}
                                            </a>
                                        @endif
                                    </div>
                                </div>

                                <div class="table-responsive mt-3">
                                    <table class="table table-sm align-middle">
                                        <thead class="table-light">
                                            <tr>
                                                <th>{{ __('Item') }}</th>
                                                <th>{{ __('Unit') }}</th>
                                                <th class="text-end">{{ __('Quantity') }}</th>
                                                <th class="text-end">{{ __('Rate (KES)') }}</th>
                                                <th class="text-end">{{ __('Amount (KES)') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($group['items'] as $item)
                                                @php
                                                    $displayQuantity = $item->quantity ?? 0;
                                                @endphp
                                                <tr>
                                                    <td>{{ $item->item_name }}</td>
                                                    <td>{{ $item->units ?? 'N/A' }}</td>
                                                    <td class="text-end">{{ is_int($displayQuantity) ? $displayQuantity : number_format($displayQuantity, 2) }}</td>
                                                    <td class="text-end">{{ number_format((float) ($item->rate ?? 0), 2) }}</td>
                                                    <td class="text-end">{{ number_format((float) ($item->amount ?? 0), 2) }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
@endsection
