<input type="hidden" name="date" value="{{ $date }}">

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

