@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="row mb-3 align-items-center">
            <div class="col">
                <h2 class="fw-bold m-0" style="color:#027333">BoQ • <span class="text-dark">{{ $bqSection->name }}</span></h2>
            </div>
            <div class="col-auto">
                <a href="{{ route('boq') }}" class="btn btn-outline-secondary btn-sm">Back to BoQ</a>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-11">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <div class="d-flex gap-2 mb-3">
                            <a href="{{ route('bq_sections.create') }}" class="btn btn-success btn-sm">
                                {{ __('Add New Item') }}
                            </a>
                            <a href="{{ route('boms.show', $bqSection->id) }}" class="btn btn-outline-primary btn-sm">
                                {{ __('View BoM for this Section') }}
                            </a>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-sm table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col">{{ __('Name') }}</th>
                                        <th scope="col">{{ __('Unit') }}</th>
                                        <th scope="col" class="text-end">{{ __('Quantity') }}</th>
                                        <th scope="col" class="text-end">{{ __('Rate (KES)') }}</th>
                                        <th scope="col" class="text-end">{{ __('Amount (KES)') }}</th>
                                        <th scope="col" class="text-end">{{ __('Actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $totalQuantity = 0;
                                        $totalAmount = 0;
                                    @endphp
                                    @forelse ($items as $item)
                                        @php
                                            $totalQuantity += $item->quantity;
                                            $totalAmount += $item->amount;
                                        @endphp
                                        <tr>
                                            <td class="p-2">{{ $item->item_name }}</td>
                                            <td class="text-nowrap">{{ $item->units }}</td>
                                            <td class="text-end">{{ number_format($item->quantity, 2) }}</td>
                                            <td class="text-end">{{ number_format($item->rate, 2) }}</td>
                                            <td class="text-end">{{ number_format($item->amount, 2) }}</td>
                                            <td class="text-end">
                                                <div class="d-inline-flex gap-2">
                                                    <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editItemModal{{ $item->id }}">
                                                        Edit
                                                    </button>
                                                    @include('bq_sections.modals.edit_item')

                                                    <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteItemModal{{ $item->id }}">
                                                        Delete
                                                    </button>
                                                    @include('bq_sections.modals.delete_item')
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center text-muted py-4">{{ __('No items found.') }}</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                                <tfoot>
                                    <tr class="table-secondary">
                                        <th colspan="2" class="text-end text-uppercase small">{{ __('Total') }}</th>
                                        <th class="text-end fw-bold">{{ number_format($totalQuantity, 2) }}</th>
                                        <th></th>
                                        <th class="text-end fw-bold">KES {{ number_format($totalAmount, 2) }}</th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
