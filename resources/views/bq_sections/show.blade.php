@extends('layouts.appbar')

@section('content')
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Section: ') . $bqSection->section_name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="container">
            <div class="card">
                <div class="card-body">
                    <!-- Display Section Details -->
                    <p><strong>{{ __('Section Name:') }}</strong> {{ $bqSection->section_name }}</p>
                    <p><strong>{{ __('Details:') }}</strong> {{ $bqSection->details }}</p>
                    <p><strong>{{ __('Unit:') }}</strong> {{ $bqSection->unit }}</p>
                    <p><strong>{{ __('Quantity:') }}</strong> {{ $bqSection->quantity }}</p>

                    <!-- Link to Edit Section -->
                    <a href="{{ route('bq_sections.edit', [$bqDocument, $bqSection]) }}" class="btn btn-primary mt-4">
                        {{ __('Edit Section') }}
                    </a>

                    <!-- Link to Add New Item -->
                    <a href="{{ route('create_bq_item', ['bqSection'=> $bqSection]) }}" class="btn btn-success mt-4">
                        {{ __('Add New Item') }}
                    </a>

                    <!-- Table to display items -->
                    <h3 class="text-lg font-medium mt-6">{{ __('Items List') }}</h3>
                    <table class="table table-striped mt-4">
                        <thead>
                            <tr>
                                <th scope="col">{{ __('Description') }}</th>
                                <th scope="col">{{ __('Quantity') }}</th>
                                <th scope="col">{{ __('Unit') }}</th>
                                <th scope="col">{{ __('Rate') }}</th>
                                <th scope="col">{{ __('Amount') }}</th>
                                <th scope="col">{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($items as $item)
                                <tr>
                                    <td>{{ $item->item_description }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>{{ $item->unit }}</td>
                                    <td>{{ number_format($item->rate, 2) }}</td>
                                    <td>{{ number_format($item->amount, 2) }}</td>
                                    <td>
                                        <!-- Add Edit and Delete Links for Items Here if needed -->
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <!-- Link Back to Document -->
                    <a href="{{ route('bq_documents.show', $bqDocument) }}" class="btn btn-secondary mt-4">
                        {{ __('Back to Document') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endsection

