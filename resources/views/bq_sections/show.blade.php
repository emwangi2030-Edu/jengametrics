@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="row mb-4">
            <div class="col-12 d-flex flex-column flex-md-row justify-content-between align-items-md-center">
                <div>
                    <h2 class="font-weight-bold" style="color:#027333">
                        {{ $section->name }}
                    </h2>
                    <p class="text-muted mb-0">{{ $bqDocument->title }}</p>
                </div>
                <div class="mt-3 mt-md-0">
                    <a href="{{ route('bq_sections.create', $bqDocument) }}" class="btn text-white" style="background-color:#027333">
                        {{ __('Add Item to BoQ') }}
                    </a>
                    <a href="{{ route('bq_documents.show', $bqDocument) }}" class="btn btn-outline-secondary ms-2">
                        {{ __('Back to BoQ') }}
                    </a>
                </div>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <h3 class="text-lg font-weight-bold">{{ __('Items List') }}</h3>
                        </div>

                        <table class="table mt-4">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col">{{ __('Name') }}</th>
                                    <th scope="col">{{ __('Unit') }}</th>
                                    <th scope="col">{{ __('Quantity') }}</th>
                                    <th scope="col">{{ __('Rate') }}</th>
                                    <th scope="col">{{ __('Amount') }}</th>
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
                                        $totalQuantity += $item->quantity;
                                        $totalAmount += $item->amount;
                                    @endphp
                                    <tr>
                                        <td class="p-2">{{ $item->item_name }}</td>
                                        <td>{{ $item->units }}</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>{{ number_format($item->rate, 2) }}</td>
                                        <td>{{ number_format($item->amount, 2) }}</td>
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
                                        <td colspan="6" class="text-center">{{ __('No items found.') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                            <tfoot>
                                <tr class="bg-secondary bg-opacity-10 border-0 rounded">
                                    <th>{{ __('Total') }}</th>
                                    <td></td>
                                    <td class="fw-bold">{{ $totalQuantity }}</td>
                                    <td></td>
                                    <td class="fw-bold">{{ number_format($totalAmount, 2) }}</td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
