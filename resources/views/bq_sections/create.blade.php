@extends('layouts.appbar')

@section('content')
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create New Section for: ') . $bqDocument->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="container">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('bq_sections.store', $bqDocument) }}">
                        @csrf
                        <div class="mb-3">
                            <label for="section_name" class="form-label">{{ __('Section Name') }}</label>
                            <input type="text" name="section_name" id="section_name" class="form-control @error('section_name') is-invalid @enderror" required>
                            @error('section_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="details" class="form-label">{{ __('Details') }}</label>
                            <textarea name="details" id="details" class="form-control @error('details') is-invalid @enderror" rows="4"></textarea>
                            @error('details')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">{{ __('Save Section') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endsection
