@extends('layouts.appbar')
@section('content')
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Bills of Quantities') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Button to create a new BQ document -->
                    <a href="{{ route('bq_documents.create') }}" class="btn btn-info">
                        {{ __('Create New BQ Document') }}
                    </a>

                    <div class="mt-6">
                        <!-- Display the list of BQ documents -->
                        @if($documents->isEmpty())
                            <p>{{ __('No BQ documents found.') }}</p>
                        @else
                            <ul class="list-disc pl-5 space-y-2">
                                @foreach($documents as $document)
                                    <li>
                                        <a href="{{ route('bq_documents.show', $document) }}" class="text-blue-500 hover:underline">
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
    @endsection
