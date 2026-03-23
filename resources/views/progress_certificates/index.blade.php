@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="jm-page-header">
        <div>
            <h2 class="jm-page-title">{{ __('Progress Certificates:') }} {{ $project->name }}</h2>
            <p class="jm-page-subtitle mb-0">{{ __('Create and track billing certificates by period.') }}</p>
        </div>
        <a href="{{ route('progress_certificates.create') }}" class="btn btn-primary">
            {{ __('New Certificate') }}
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if (session('warning'))
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            {{ session('warning') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if (session('danger'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('danger') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if (session('info'))
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            {{ session('info') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body p-0">
            @if ($certificates->isEmpty())
                <div class="p-5 text-center">
                    <p class="mb-2 fw-semibold">{{ __('No progress certificates yet.') }}</p>
                    <p class="mb-4">{{ __('Create your first certificate to bill completed work for a selected period.') }}</p>
                    <a href="{{ route('progress_certificates.create') }}" class="btn btn-primary">
                        {{ __('Create Certificate') }}
                    </a>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="border-0 ps-4 py-3">Period</th>
                                <th class="border-0 py-3">Reference</th>
                                <th class="border-0 py-3 text-end">Amount (KES)</th>
                                <th class="border-0 py-3">Status</th>
                                <th class="border-0 pe-4 py-3 text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($certificates as $cert)
                            <tr>
                                <td class="ps-4">{{ $cert->period_start->format('d M Y') }} – {{ $cert->period_end->format('d M Y') }}</td>
                                <td>{{ $cert->reference_number ?: '—' }}</td>
                                <td class="text-end fw-semibold">KES {{ number_format($cert->amount, 2) }}</td>
                                <td>
                                    @if ($cert->status === 'draft')
                                        <span class="badge bg-secondary">Draft</span>
                                    @elseif ($cert->status === 'sent')
                                        <span class="badge bg-info">Sent</span>
                                    @else
                                        <span class="badge bg-success">Paid</span>
                                    @endif
                                </td>
                                <td class="pe-4 text-end">
                                    @if ($cert->isDraft())
                                        <a href="{{ route('progress_certificates.edit', $cert) }}" class="btn btn-sm btn-outline-secondary me-1">{{ __('Edit') }}</a>
                                    @endif
                                    @if ($cert->isDraft())
                                        <form action="{{ route('progress_certificates.markSent', $cert) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-primary me-1">{{ __('Mark Sent') }}</button>
                                        </form>
                                    @endif
                                    @if ($cert->isSent() || $cert->isDraft())
                                        <form action="{{ route('progress_certificates.markPaid', $cert) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success">{{ __('Mark Paid') }}</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <div class="mt-3">
        <a href="{{ route('cost-tracking.index') }}" class="btn btn-outline-secondary">{{ __('Back to Cost Tracking') }}</a>
    </div>
</div>
@endsection
