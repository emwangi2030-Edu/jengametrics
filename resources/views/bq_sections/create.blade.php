@extends('layouts.appbar')

@section('content')
    <div class="container py-4">
        <div class="row mb-4">
            <div class="col-12">
                <h2 class="font-weight-bold text-dark">
                    {{ __('Create New Section for: ') . $bqDocument->name }}
                </h2>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-8">
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
    </div>
@endsection
