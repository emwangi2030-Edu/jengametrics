@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="row mb-4">
            <div class="col-12 d-flex flex-column flex-md-row justify-content-between align-items-md-center">
                <div>
                    <h2 class="font-weight-bold" style="color:#027333">
                        {{ $bqLevel->name }}
                    </h2>
                    <p class="text-muted mb-0">{{ $bqDocument->title }}</p>
                </div>
                <div class="mt-3 mt-md-0">
                    <a href="{{ route('bq_levels.items.create', [$bqDocument, $bqLevel]) }}" class="btn text-white" style="background-color:#027333">
                        {{ __('Add Item to Level') }}
                    </a>
                    <a href="{{ route('bq_documents.show', $bqDocument) }}" class="btn btn-outline-secondary ms-2">
                        {{ __('Back to Sub BoQ') }}
                    </a>
                </div>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-11">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h3 class="text-lg font-weight-bold mb-3">{{ __('Items in this Level') }}</h3>
                        <div class="table-responsive">
                            <table class="table mt-2">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col">{{ __('Name') }}</th>
                                        <th scope="col">{{ __('Unit') }}</th>
                                        <th scope="col" class="text-end">{{ __('Quantity') }}</th>
                                        <th scope="col" class="text-end">{{ __('Rate') }}</th>
                                        <th scope="col" class="text-end">{{ __('Amount') }}</th>
                                        <th scope="col">{{ __('Actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $totalQuantity = 0;
                                        $totalAmount = 0;
                                    @endphp
                                    @forelse ($items as $item)
                                        @php
                                            $totalQuantity += (float) ($item->quantity ?? 0);
                                            $totalAmount += (float) ($item->amount ?? 0);
                                        @endphp
                                        <tr>
                                            <td class="p-2">{{ $item->item_name }}</td>
                                            <td>{{ $item->units }}</td>
                                            <td class="text-end">{{ number_format((float) $item->quantity, 2) }}</td>
                                            <td class="text-end">{{ number_format((float) $item->rate, 2) }}</td>
                                            <td class="text-end">{{ number_format((float) $item->amount, 2) }}</td>
                                            <td>
                                                <div class="d-flex gap-2">
                                                    <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editItemModal{{ $item->id }}">
                                                        {{ __('Edit') }}
                                                    </button>
                                                    @include('bq_sections.modals.edit_item')

                                                    <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteItemModal{{ $item->id }}">
                                                        {{ __('Delete') }}
                                                    </button>
                                                    @include('bq_sections.modals.delete_item')
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center">{{ __('No items found in this level.') }}</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                                <tfoot>
                                    <tr class="bg-secondary bg-opacity-10 border-0 rounded">
                                        <th>{{ __('Total') }}</th>
                                        <td></td>
                                        <td class="fw-bold text-end">{{ number_format($totalQuantity, 2) }}</td>
                                        <td></td>
                                        <td class="fw-bold text-end">{{ number_format($totalAmount, 2) }}</td>
                                        <td></td>
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
