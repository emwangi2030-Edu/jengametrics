@extends('layouts.appbar')

@section('content')
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Bill of Quantities: ') . $bqDocument->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="container">
            <div class="card">
                <div class="card-body">
                    <p class="font-weight-bold">{{ __('Document Details') }}</p>
                    <div class="mt-4">
                        <p><strong>{{ __('Name:') }}</strong> {{ $bqDocument->title }}</p>
                        <p><strong>{{ __('Created At:') }}</strong> {{ $bqDocument->created_at->format('M d, Y') }}</p>
                        <!-- Add more details as needed -->

                        <!-- Link to create a new section -->
                        <a href="{{ route('bq_sections.create', $bqDocument) }}" class="btn btn-primary mt-4">
                            {{ __('Add New Section') }}
                        </a>

                        <!-- Display the list of sections -->
                        <div class="mt-6">
                            <h3 class="h5 font-weight-bold">{{ __('Sections') }}</h3>
                            @if($bqDocument->sections->isEmpty())
                                <p>{{ __('No sections found.') }}</p>
                            @else
                                <ul class="list-unstyled mt-4">
                                    @foreach($bqDocument->sections as $section)
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

                        <a href="{{ route('bq_documents.index') }}" class="btn btn-secondary mt-4">{{ __('Back to List') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
