@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex align-items-center justify-content-between my-4">
        <h1 class="mb-0" style="color:#027333">User Details</h1>
        <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary" aria-label="Back" title="Back">
            <span data-feather="arrow-left-circle"></span>
        </a>
    </div>

    <div class="row g-4">
        <div class="col-12 col-lg-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-0 pb-0">
                    <h5 class="mb-0">Profile</h5>
                </div>
                <div class="card-body">
                    <p class="mb-2"><strong>Name:</strong> {{ $listedUser->name }}</p>
                    <p class="mb-2"><strong>Email:</strong> {{ $listedUser->email }}</p>
                    <p class="mb-0"><strong>Created:</strong> {{ optional($listedUser->created_at)->format('M d, Y') }}</p>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-0 pb-0">
                    <h5 class="mb-0">Associated Projects</h5>
                </div>
                <div class="card-body">
                    @if($projects->isEmpty())
                        <p class="text-muted mb-0">No associated projects found.</p>
                    @else
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Project Name</th>
                                        <th>Project UID</th>
                                        <th>Duration (Weeks)</th>
                                        <th>Created</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($projects as $project)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $project->name }}</td>
                                            <td>{{ $project->project_uid ?: '-' }}</td>
                                            <td>{{ $project->project_duration ?: '-' }}</td>
                                            <td>{{ optional($project->created_at)->format('M d, Y') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
