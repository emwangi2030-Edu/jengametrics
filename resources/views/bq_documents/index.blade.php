@extends('layouts.appbar')

@section('content')

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="mb-4">
                <h2 class="font-weight-bold text-primary">
                    {{ __('Bills of Quantities') }}
                </h2>
            </div>

            @include('flash_msg')

            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <!-- Button to create a new BQ document -->
                    <div class="text-right mb-3">
                        <a href="{{ route('bq_documents.create') }}" class="btn btn-primary btn-lg">
                            {{ __('Create New BQ Document') }}
                        </a>
                    </div>

                    <!-- Display the list of BQ documents -->
                    <div>
                        @if($documents->isEmpty())
                            <p class="text-muted">{{ __('No BQ documents found.') }}</p>
                        @else
                            <ul class="list-group list-group-flush">
                                @foreach($documents as $document)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <a href="{{ route('bq_documents.show', $document) }}" class="text-primary font-weight-bold">
                                            {{ $document->title }}
                                        </a>
                                        <span class="badge badge-primary badge-pill">{{ $document->created_at->format('d-m-Y') }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
