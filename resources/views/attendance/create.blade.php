@php
    use Carbon\Carbon;
@endphp
@extends('layouts.appbar')

@section('content')
    <div class="container mt-4">
        <h2 class="mb-4" style="color:#027333">Daily Attendance</h2>

        <form method="GET" action="{{ route('attendance.create') }}" class="mb-3 d-flex align-items-center">

            {{-- Previous day --}}
            <button type="submit" name="date" value="{{ Carbon::parse($date)->subDay()->toDateString() }}"
                class="btn btn-outline-secondary me-2">&laquo;</button>

            {{-- Date picker --}}
            <input type="date" id="date" name="selected_date" class="form-control w-auto me-2"
                value="{{ $date }}" onchange="this.form.submit()" required>

            {{-- Next day (only show if not today) --}}
            @if($date !== now()->toDateString())
                <button type="submit" name="date" value="{{ Carbon::parse($date)->addDay()->toDateString() }}"
                    class="btn btn-outline-secondary me-2">&raquo;</button>
            @endif

            {{-- Show "Today" button only if not on today --}}
            @if($date !== now()->toDateString())
                <button type="submit" name="date" value="{{ now()->toDateString() }}"
                    class="btn btn-success">Today</button>
            @endif
        </form>

        <form method="POST" action="{{ route('attendance.store') }}">
            @csrf

            <input type="hidden" name="date" value="{{ $date }}">

            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table mt-3">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Full Name</th>
                                            <th>Job Category</th>
                                            <th>Work Type</th>
                                            <th>Payment Frequency</th>
                                            <th>Present?</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($workers as $worker)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $worker->full_name }}</td>
                                            <td>{{ $worker->job_category }}</td>
                                            <td>{{ $worker->work_type }}</td>
                                            <td>{{ $worker->payment_frequency }}</td>
                                            <td>
                                                <input type="hidden" name="worker_ids[]" value="{{ $worker->id }}">
                                                <input type="checkbox" name="present[]" value="{{ $worker->id }}"
                                                    {{ isset($existingAttendances[$worker->id]) ? 'checked' : '' }}>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <br>
            <div class="d-flex justify-content-between">
                <button type="submit" class="btn btn-primary">Save Attendance</button>
                <a href="{{ route('workers.index') }}" class="btn btn-secondary">Back</a>
            </div>
        </form>
    </div>
@endsection
