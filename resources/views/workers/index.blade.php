@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h2 class="font-weight-bold" style="color:#027333;">
                Manage Labour: <span class="text-black">{{ $project->name }}</span>
            </h2>
            <div class="d-flex gap-1">
                <a href="{{ route('workers.create') }}" class="btn btn-success">
                    {{ __('Add Worker') }}
                </a>
                <a href="{{ route('attendance.create') }}" class="btn btn-info">
                    {{ __('Daily Attendance') }} 
                </a>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-center">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table mt-3 text-center">
                            <thead class="table-light">
                                <tr>
                                    <th></th>
                                    <th>{{ __('Full Name') }}</th>
                                    <th>{{ __('ID Number') }}</th>
                                    <th>{{ __('Job Category') }}</th>
                                    <th>{{ __('Work Type') }}</th>
                                    <th>{{ __('Phone') }}</th>
                                    <th>{{ __('Email') }}</th>
                                    <th>{{ __('Attended Days') }}</th>
                                    <th>{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($workers as $worker)
                                    <tr>
                                        <td>{{ $loop->iteration }}. </td>
                                        <td>{{ $worker->full_name }}</td>
                                        <td>{{ $worker->id_number }}</td>
                                        <td>{{ $worker->job_category }}</td>
                                        <td>{{ $worker->work_type }}</td>
                                        <td>{{ $worker->phone }}</td>
                                        <td>{{ $worker->email ?? 'N/A' }}</td>
                                        <td>{{ $worker->attendances_count }}</td>
                                        <td class="d-flex gap-1">
                                            <a href="{{ route('workers.show', $worker->id) }}" class="btn btn-info btn-sm">
                                                View
                                            </a>
                                            <a href="{{ route('workers.edit', $worker->id) }}" class="btn btn-warning btn-sm">
                                                Edit
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if($workers->isEmpty())
                        <p class="text-center mt-4 text-muted">{{ __('No workers found.') }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const alertBox = document.getElementById('success-alert');
        if (alertBox) {
            setTimeout(() => {
                alertBox.classList.remove('show');
                alertBox.classList.add('fade');
            }, 4000);
        }
    });
</script>
@endpush
