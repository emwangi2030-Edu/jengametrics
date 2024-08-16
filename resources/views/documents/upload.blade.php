@extends('layouts.appbar')
@section('content') 
    <div class="container mt-5">
        <h1 class="mb-4">Upload Document</h1>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('documents.store') }}" method="POST" enctype="multipart/form-data" class="mb-4">
            @csrf
            <div class="mb-3">
                <label for="file" class="form-label">Choose a document to upload:</label>
                <input type="file" name="file" id="file" class="form-control" required>
            </div>
            <div>
                <button type="submit" class="btn btn-primary">Upload</button>
            </div>
        </form>

        <h2 class="mt-5">Uploaded Documents</h2>

        @if($documents->isEmpty())
            <p>No documents found.</p>
        @else
            <ul class="list-group">
                @foreach($documents as $document)
                    <li class="list-group-item">
                        <a href="{{ route('documents.upload', $document->id) }}" class="text-decoration-none">
                            {{ $document->name }}
                        </a>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>

    <!-- Include Bootstrap JS (Optional) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

@endsection