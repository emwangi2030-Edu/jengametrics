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
                                    @php
                                        $nameClasses = ($worker->trashed() && $worker->amount_owed > 0) ? 'text-muted' : '';
                                        $isArchived = $worker->trashed();
                                    @endphp
                                    <tr>
                                        <td>{{ $loop->iteration }}. </td>
                                        <td class="{{ $nameClasses }}">
                                            <a href="{{ route('workers.show', $worker->id) }}" class="text-decoration-none {{ $nameClasses }}">
                                                {{ $worker->full_name }}
                                            </a>
                                            @if($isArchived)
                                                <span class="badge bg-secondary ms-1">{{ __('Archived') }}</span>
                                                @if($worker->amount_owed > 0)
                                                    <span class="badge bg-warning text-dark ms-1">{{ __('Owed') }}: {{ number_format($worker->amount_owed, 2) }}</span>
                                                @endif
                                            @endif
                                        </td>
                                        <td>{{ $worker->id_number }}</td>
                                        <td>{{ $worker->job_category }}</td>
                                        <td>{{ $worker->work_type }}</td>
                                        <td>{{ $worker->phone }}</td>
                                        <td>{{ $worker->email ?? 'N/A' }}</td>
                                        <td>{{ $worker->attendances_count }}</td>
                                        <td class="d-flex gap-1">
                                            <a href="{{ route('workers.edit', $worker->id) }}" class="btn btn-warning btn-sm">
                                                Edit
                                            </a>
                                            <form action="{{ route('workers.destroy', $worker->id) }}" method="POST" onsubmit="return confirm('{{ $isArchived ? __('All outstanding debts have been cleared. Remove this worker? Attendance history will remain marked as terminated.') : __('Archive this worker? Attendance and payments will be kept.') }}')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">
                                                    {{ $isArchived ? __('Remove') : __('Delete') }}
                                                </button>
                                            </form>
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
