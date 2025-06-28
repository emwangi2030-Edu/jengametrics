@extends('layouts.appbar')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4" style="color:#027333">Daily Attendance</h2>

    <form method="POST" action="{{ route('attendance.store') }}">
        @csrf

        <div class="mb-3">
            <label for="date" class="form-label">Date:</label>
            <input type="date" id="date" name="date" class="form-control w-auto"
                   value="{{ now()->toDateString() }}" required>
        </div>

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
                                    @foreach($workers as $index => $worker)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $worker->full_name }}</td>
                                        <td>{{ $worker->job_category }}</td>
                                        <td>{{ $worker->work_type }}</td>
                                        <td>{{ $worker->payment_frequency }}</td>
                                        <td>
                                            <input type="hidden" name="worker_ids[]" value="{{ $worker->id }}">
                                            <input type="checkbox" name="present[]" value="{{ $worker->id }}">
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
