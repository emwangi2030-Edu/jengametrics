@extends('layouts.appbar')

@section('content')
    <div class="container py-4">
        <div class="row mb-4">
            <div class="col-12">
                <h2 class="font-weight-bold text-dark">
                    {{ __('Bill of Quantities: ') . $bqDocument->name }}
                </h2>

                
                @include('flash_msg')
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-body">
                        <p class="font-weight-bold">{{ __('Document Details') }}</p>
                        <div class="mt-4">
                            <p><strong>{{ __('Name:') }}</strong> {{ $bqDocument->name }}</p>
                            <p><strong>{{ __('Created At:') }}</strong> {{ $bqDocument->created_at->format('M d, Y') }}</p>
                            <!-- Add more details as needed -->

                            <!-- Link to create a new section -->
                            <a href="{{ route('bq_sections.create', $bqDocument) }}" class="btn btn-primary mt-4">
                                {{ __('Add New Section') }}
                            </a>

                            <!-- Display the list of sections -->
                            <div class="mt-6">
                                <h3 class="h5 font-weight-bold">{{ __('Sections') }}</h3>
                                @if($sections->isEmpty())
                                    <p>{{ __('No sections found.') }}</p>
                                @else
                                    <ul class="list-unstyled mt-4">
                                        @foreach($sections as $section)
                                            <li class="mb-3">
                                                <p><strong>{{ __('Section Name:') }}</strong> {{ $section->section_name }}</p>
                                                <p><strong>{{ __('Details:') }}</strong> {{ $section->details }}</p>
                                                <!-- Optionally, add a link to view more details or edit the section -->
                                                <a href="{{ route('bq_sections.show', [$bqDocument, $section]) }}" class="text-primary">
                                                    {{ __('View Section') }}
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
    </div>
@endsection
