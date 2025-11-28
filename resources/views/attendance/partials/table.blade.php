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
            @forelse($workers as $worker)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>
                    {{ $worker->full_name }}
                    @if($worker->trashed() || ($worker->terminated ?? false))
                        <span class="badge bg-secondary ms-1">{{ __('Terminated') }}</span>
                    @endif
                </td>
                <td>{{ $worker->job_category }}</td>
                <td>{{ $worker->work_type }}</td>
                <td>{{ $worker->payment_frequency }}</td>
                <td>
                    <input type="hidden" name="worker_ids[]" value="{{ $worker->id }}">
                    <input type="checkbox" name="present[]" value="{{ $worker->id }}"
                        {{ isset($existingAttendances[$worker->id]) ? 'checked' : '' }}>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center text-muted">
                    No workers were active on {{ \Carbon\Carbon::parse($date)->format('d M Y') }}.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
