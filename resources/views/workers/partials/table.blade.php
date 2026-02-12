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
                        @if(auth()->check() && (!auth()->user()->isSubAccount() || auth()->user()->can_manage_labour))
                            <a href="{{ route('workers.show', $worker->id) }}" class="text-decoration-none {{ $nameClasses }}">
                                {{ $worker->full_name }}
                            </a>
                        @else
                            <span class="{{ $nameClasses }}">{{ $worker->full_name }}</span>
                        @endif
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
                        @if($isArchived && auth()->check() && (!auth()->user()->isSubAccount() || auth()->user()->can_manage_labour))
                            <form action="{{ route('workers.restore', $worker->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm">
                                    {{ __('Restore') }}
                                </button>
                            </form>
                        @endif
                        <form action="{{ route('workers.destroy', $worker->id) }}" method="POST" data-confirm-message="{{ $isArchived ? __('All outstanding debts have been cleared. Remove this worker? Attendance history will remain marked as terminated.') : __('Archive this worker? Attendance and payments will be kept.') }}">
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
