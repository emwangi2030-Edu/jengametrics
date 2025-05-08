@extends('layouts.appbar')

@section('content')
<div class="container">
    <h1>Document Viewer</h1>
    <div class="document-viewer">
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
    <a href="{{ route('materials.index') }}" class="btn btn-primary mt-3">Back to Materials List</a>
</div>
@endsection
