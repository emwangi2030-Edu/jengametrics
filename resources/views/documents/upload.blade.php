@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="jm-page-header">
        <div>
            <h2 class="jm-page-title jm-ui-title">{{ __('Upload Document') }}</h2>
            <p class="jm-page-subtitle jm-ui-muted mb-0">{{ __('Store and review uploaded project documents.') }}</p>
        </div>
        <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary" aria-label="Back" title="Back"><span data-feather="arrow-left-circle"></span></a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card jm-ui-card shadow-sm border-0 mb-4">
        <div class="card-body">
            <form action="{{ route('documents.store') }}" method="POST" enctype="multipart/form-data" class="row g-3 align-items-end jm-ui-surface p-3">
                @csrf
                <div class="col-md-9">
                    <label for="file" class="form-label">{{ __('Choose a document to upload') }}</label>
                    <input type="file" name="file" id="file" required class="form-control">
                </div>
                <div class="col-md-3 d-grid">
                    <button type="submit" class="btn btn-primary">{{ __('Upload') }}</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card jm-ui-card shadow-sm border-0">
        <div class="card-body">
            <h5 class="jm-section-title mb-3">{{ __('Uploaded Documents') }}</h5>

            @if($documents->isEmpty())
                <p class="text-muted mb-0">{{ __('No documents uploaded yet.') }}</p>
            @else
                <div class="table-responsive jm-ui-table-wrap">
                    <table class="table align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>{{ __('Document Name') }}</th>
                                <th class="text-end">{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($documents as $document)
                                <tr>
                                    <td>{{ $document->name }}</td>
                                    <td class="text-end">
                                        <a href="{{ asset('storage/' . ltrim($document->path, '/')) }}" target="_blank" rel="noopener" class="btn btn-sm btn-outline-primary">
                                            {{ __('View') }}
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
