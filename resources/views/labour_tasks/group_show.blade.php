@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="jm-page-title jm-ui-title">{{ __('Team Details') }}</h2>
            <p class="jm-ui-muted mb-0">{{ $group->name }}</p>
        </div>
        <a href="{{ route('labour_tasks.index') }}" class="btn btn-outline-secondary" aria-label="Back" title="Back"><span data-feather="arrow-left-circle"></span></a>
    </div>

    <div class="card jm-ui-card shadow-sm border-0">
        <div class="card-header bg-white border-0 pb-0">
            <h5 class="jm-section-title mb-0">{{ __('Team Members') }}</h5>
        </div>
        <div class="card-body">
            @if($group->workers->isEmpty())
                <p class="text-muted mb-0">{{ __('No workers have been assigned to this team yet.') }}</p>
            @else
                <div class="table-responsive jm-ui-table-wrap">
                    <table class="table align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Job Category</th>
                                <th>Work Type</th>
                                <th>Phone</th>
                                <th>Email</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($group->workers as $worker)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $worker->full_name }}</td>
                                    <td>{{ $worker->job_category }}</td>
                                    <td>{{ $worker->work_type }}</td>
                                    <td>{{ $worker->phone }}</td>
                                    <td>{{ $worker->email ?: 'N/A' }}</td>
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
