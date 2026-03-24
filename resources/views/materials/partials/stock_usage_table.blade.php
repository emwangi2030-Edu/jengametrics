<div class="table-responsive jm-ui-table-wrap">
    <table class="table table-bordered text-center align-middle mb-0">
        <thead class="table-light">
            <tr>
                <th>{{ __('Date Issued') }}</th>
                <th>{{ __('Material') }}</th>
                <th>{{ __('Section') }}</th>
                <th>{{ __('Quantity Issued') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse($stockUsages as $usage)
                <tr>
                    <td><div class="px-2">{{ $usage->created_at->format('d-m-Y') }}</div></td>
                    <td>{{ $usage->material->name ?? 'N/A' }}</td>
                    <td>{{ $usage->section->name ?? 'N/A' }}</td>
                    <td>{{ $usage->quantity_used }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center text-muted">{{ __('No stock usage history found.') }}</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
