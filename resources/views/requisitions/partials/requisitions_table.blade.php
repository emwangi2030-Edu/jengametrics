<div class="table-responsive jm-ui-table-wrap">
    <table class="table table-bordered align-middle mb-0">
        <thead class="table-light">
            <tr>
                <th>Requisition No.</th>
                <th>Material</th>
                <th>BoQ</th>
                <th>Quantity Requested</th>
                <th>Status</th>
                <th>Section</th>
                <th>Requested By</th>
                <th>Requested At</th>
                <th>Approved By</th>
                <th>Approved At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($requisitions as $req)
                <tr>
                    <td>{{ $req->requisition_no }}</td>
                    <td>{{ $req->bomItem->item_material->name ?? $req->extra_material_name }}</td>
                    <td>{{ $req->bomItem->bqDocument->title ?? ($req->extra_material_name ? __('Ad-hoc Request') : __('Unknown')) }}</td>
                    <td>
                        {{ (int) $req->quantity_requested }} {{ $req->bomItem->item_material->unit_of_measurement ?? $req->extra_unit }}
                    </td>
                    <td>
                        <span class="badge bg-{{ $req->status == 'approved' ? 'success' : ($req->status == 'rejected' ? 'danger' : 'secondary') }}">
                        {{ ucfirst($req->status) }}
                        </span>
                    </td>
                    <td>{{ $req->section->name }}</td>
                    <td>{{ $req->requester->name ?? 'N/A' }}</td>
                    <td>{{ $req->requested_at ? \Carbon\Carbon::parse($req->requested_at)->format('d-m-Y') : '-' }}</td>
                    <td>{{ $req->approver->name ?? '-' }}</td>
                    <td>{{ $req->approved_at ? \Carbon\Carbon::parse($req->approved_at)->format('d-m-Y') : '-' }}</td>
                    <td>
                        @if($req->status === 'pending')
                            <form action="{{ route('requisitions.approve', $req->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button class="btn btn-sm btn-success">Approve</button>
                            </form>
                            <form action="{{ route('requisitions.reject', $req->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button class="btn btn-sm btn-danger">Reject</button>
                            </form>
                        @else
                            <span class="text-muted">N/A</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="11" class="text-center">No requisitions found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
