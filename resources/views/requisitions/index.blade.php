@extends('layouts.appbar')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4" style="color:#027333">Material Requisitions</h2>

    <table class="table table-bordered">
        <thead class="table-light">
            <tr>
                <th>Requisition No.</th>
                <th>Material</th>
                <th>Quantity Requested</th>
                <th>Status</th>
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
                    <td>{{ $req->bomItem->item_material->name }}</td>
                    <td>{{ (int) $req->quantity_requested }} {{ $req->bomItem->item_material->unit_of_measurement }}</td>
                    <td><span class="badge bg-{{ $req->status == 'approved' ? 'success' : ($req->status == 'rejected' ? 'danger' : 'secondary') }}">
                        {{ ucfirst($req->status) }}
                    </span></td>
                    <td>{{ $req->requester->name ?? 'N/A' }}</td>
                    <td>{{ $req->requested_at ? \Carbon\Carbon::parse($req->requested_at)->format('Y-m-d') : '-' }}</td>
                    <td>{{ $req->approver->name ?? '-' }}</td>
                    <td>{{ $req->approved_at ? \Carbon\Carbon::parse($req->approved_at)->format('Y-m-d') : '-' }}</td>
                    <td>
                        @if($req->material)
                            <span class="text-muted">Purchased</span>
                        @elseif($req->status === 'pending')
                            <form action="{{ route('requisitions.approve', $req->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button class="btn btn-sm btn-success">Approve</button>
                            </form>
                            <form action="{{ route('requisitions.reject', $req->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button class="btn btn-sm btn-danger">Reject</button>
                            </form>
                        @else
                            <form action="{{ route('requisitions.toggleStatus', $req->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-sm {{ $req->status === 'approved' ? 'btn-danger' : 'btn-success' }}">
                                    {{ $req->status === 'approved' ? 'Mark as Rejected' : 'Mark as Approved' }}
                                </button>
                            </form>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center">No requisitions found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    <h3 class="mt-5" style="color:#027333">Summary of Approved Requisitions</h3>
    <table class="table table-bordered mt-3">
        <thead class="table-light">
            <tr>
                <th>Material</th>
                <th>Total Quantity Requested</th>
                <th>Unit of Measure</th>
            </tr>
        </thead>
        <tbody>
            @forelse($approvedSummary as $summary)
                <tr>
                    <td>{{ $summary->bomItem->item_material->name ?? 'N/A' }}</td>
                    <td>{{ (int) $summary->total_quantity }}</td>
                    <td>{{ $summary->bomItem->item_material->unit_of_measurement ?? 'N/A' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="text-center">No approved requisitions found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    <div>
        <a href="{{ route('boms.index') }}" class="btn btn-secondary mt-3">
            {{ __('Back') }}
        </a>
    </div>
</div>
@endsection
