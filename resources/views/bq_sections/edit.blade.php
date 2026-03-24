@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="jm-page-header">
        <div>
            <h2 class="jm-page-title jm-ui-title">{{ __('Edit Section') }}</h2>
            <p class="jm-page-subtitle jm-ui-muted mb-0">{{ $bqSection->section_name ?? $bqSection->name }}</p>
        </div>
        <a href="{{ route('bq_documents.show', $bqDocument) }}" class="btn btn-outline-secondary" aria-label="Back" title="Back"><span data-feather="arrow-left-circle"></span></a>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card jm-ui-card shadow-sm border-0">
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('bq_sections.update', [$bqDocument, $bqSection]) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="section_name" class="form-label">{{ __('Section Name') }}</label>
                            <input type="text" name="section_name" id="section_name" class="form-control" value="{{ $bqSection->section_name ?? $bqSection->name }}" required>
                        </div>

                        <div class="mb-4">
                            <label for="details" class="form-label">{{ __('Details') }}</label>
                            <textarea name="details" id="details" class="form-control" rows="4">{{ $bqSection->details }}</textarea>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('bq_documents.show', $bqDocument) }}" class="btn btn-outline-secondary" aria-label="Back" title="Back"><span data-feather="arrow-left-circle"></span></a>
                            <button type="submit" class="btn btn-primary">{{ __('Update Section') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
