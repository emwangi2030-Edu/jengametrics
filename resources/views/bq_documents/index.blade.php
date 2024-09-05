@extends('layouts.appbar')

@section('content')

    <div class="container py-4">
        <div class="row mb-4">
            <div class="col-12">
                <h2 class="font-weight-bold text-dark">
                    {{ __('Bills of Quantities') }}
                </h2>

                @include('flash_msg')
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <!-- Button to create a new BQ document -->
                        <a href="{{ route('bq_documents.create') }}" class="btn btn-info mb-3">
                            {{ __('Create New BQ Document') }}
                        </a>

                        <!-- Display the list of BQ documents -->
                        <div>
                            @if($documents->isEmpty())
                                <p>{{ __('No BQ documents found.') }}</p>
                            @else
                                <ul class="list-group">
                                    @foreach($documents as $document)
                                        <li class="list-group-item">
                                            <a href="{{ route('bq_documents.show', $document) }}" class="text-info">
                                                {{ $document->title }}
                                            </a>
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
