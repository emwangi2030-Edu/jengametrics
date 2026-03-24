@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="jm-page-header">
        <div>
            <h2 class="jm-page-title jm-ui-title">{{ __('Document Viewer') }}</h2>
            <p class="jm-page-subtitle jm-ui-muted mb-0">{{ __('Preview uploaded material receipts and supporting files.') }}</p>
        </div>
        <a href="{{ route('materials.index') }}" class="btn btn-outline-secondary" aria-label="Back" title="Back"><span data-feather="arrow-left-circle"></span></a>
    </div>
    <div class="card jm-ui-card shadow-sm border-0">
        <div class="card-body">
        @if($documentUrl)
            @php
                // Get the file extension to determine the type of document
                $extension = pathinfo($documentUrl, PATHINFO_EXTENSION);
            @endphp

            @if(in_array($extension, ['pdf', 'jpg', 'jpeg', 'png', 'gif']))
                <!-- Use standard iframe for PDFs and images -->
                <iframe src="{{ asset($documentUrl) }}" width="100%" height="600px" frameborder="0">
                    Your browser does not support viewing embedded documents.
                    Please download the file instead: <a href="{{ asset($documentUrl) }}">Download Document</a>
                </iframe>
            @else
                <!-- Provide a download link for unsupported types -->
                <p>Viewing this document type is not supported. Please download it to view: <a href="{{ asset($documentUrl) }}">Download Document</a></p>
            @endif
        @else
            <p>No document available to display.</p>
        @endif
        </div>
    </div>
</div>
@endsection
