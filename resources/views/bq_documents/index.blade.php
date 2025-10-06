@extends('layouts.app')

@section('content')

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
                <div>
                    <h2 class="fw-bold text-primary mb-1">
                        {{ __('Bill of Quantities for') }} {{ $project->name }}
                    </h2>
                    <p class="text-muted mb-0">{{ __('Master document aggregates all BoQs for requisitions and reporting.') }}</p>
                </div>
                <a href="{{ route('bq_documents.create') }}" class="btn btn-success mt-3 mt-md-0">
                    {{ __('Create BoQ') }}
                </a>
            </div>

            @include('flash_msg')

            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body d-flex flex-column flex-md-row justify-content-between align-items-md-center">
                    <div>
                        <h5 class="fw-bold text-secondary mb-1">{{ $masterDocument->title }}</h5>
                        <p class="text-muted mb-0">{{ __('Total across all BoQs') }}</p>
                    </div>
                    <div class="text-end">
                        <p class="fs-4 fw-bold text-dark mb-0">KES {{ number_format($overallTotal, 2) }}</p>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">{{ __('BoQs') }}</h5>

                    @if($subDocuments->isEmpty())
                        <p class="text-muted">{{ __('No BoQs created yet.') }}</p>
                    @else
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>{{ __('Title') }}</th>
                                        <th class="text-center">{{ __('Items') }}</th>
                                        <th class="text-end">{{ __('Total Amount (KES)') }}</th>
                                        <th class="text-end">{{ __('Created') }}</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($subDocuments as $document)
                                        <tr>
                                            <td class="fw-semibold">{{ $document->title }}</td>
                                            <td class="text-center">{{ $document->unique_items_count ?? 0 }}</td>
                                            <td class="text-end">{{ number_format($document->aggregated_amount ?? 0, 2) }}</td>
                                            <td class="text-end">{{ $document->created_at->format('d M Y') }}</td>
                                            <td class="text-end">
                                                <a href="{{ route('bq_documents.show', $document) }}" class="btn btn-primary btn-sm me-2">
                                                    {{ __('View') }}
                                                </a>
                                                <form action="{{ route('bq_documents.destroy', $document) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('Are you sure you want to delete this BoQ?') }}');">
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
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
