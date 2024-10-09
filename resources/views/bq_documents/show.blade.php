@extends('layouts.appbar')

@section('content')
<div class="container mt-4">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="font-weight-bold text-primary">
                {{ __('Bill of Quantities: ') . $bqDocument->title }}
            </h2>

            @include('flash_msg')
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <!-- Document Details -->
                    <p class="font-weight-bold text-dark">{{ __('Document Details') }}</p>
                    <div class="mt-4">
                        <p><strong>{{ __('Name:') }}</strong> {{ $bqDocument->name }}</p>
                        <p><strong>{{ __('Created At:') }}</strong> {{ $bqDocument->created_at->format('M d, Y') }}</p>

                        <!-- Link to create a new section -->
                        <a href="{{ route('bq_sections.create', $bqDocument) }}" class="btn btn-primary mt-4">
                            {{ __('Add New Section') }}
                        </a>

                        <!-- Sections List -->
                        <div class="mt-5">
                            <h3 class="h5 font-weight-bold text-dark">{{ __('Sections') }}</h3>
                            @if($sections->isEmpty())
                                <p class="text-muted">{{ __('No sections found.') }}</p>
                            @else
                                <ul class="list-group mt-3">
                                    @foreach($sections as $section)
                                        <li class="list-group-item">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <p class="font-weight-bold mb-1">{{ __('Section Name:') }} {{ $section->section_name }}</p>
                                                    <p class="mb-0 text-muted">{{ __('Details:') }} {{ $section->details }}</p>
                                                </div>
                                                <a href="{{ route('bq_sections.show', [$bqDocument, $section]) }}" class="btn btn-outline-primary btn-sm">
                                                    {{ __('View Section') }}
                                                </a>
                                            </div>
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
</div>
@endsection
