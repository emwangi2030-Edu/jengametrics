@extends('layouts.appbar')

@section('content')
    <div class="container py-4">
        <div class="row mb-4">
            <div class="col-12">
                <h2 class="font-weight-bold text-dark">
                    {{ __('Section: ') . $bqSection->name }}
                </h2>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-body">
                        <!-- Display Section Details -->
                        <!-- <p><strong>{{ __('Section Name:') }}</strong> {{ $bqSection->name }}</p> -->
                        <p><strong>{{ __('Details:') }}</strong> {{ $bqSection->description }}</p>
                    
                        <!-- Link to Add New Item -->
                        <a href="{{ route('bq_sections.create') }}" class="btn btn-success mt-4">
                            {{ __('Add New Item') }}
                        </a>

                        <!-- Table to display items -->
                        <h3 class="text-lg font-weight-bold mt-6">{{ __('Items List') }}</h3>
                        <table class="table table-striped mt-4">
                            <thead>
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
                                        <td>{{ $item->item_name }}</td>
                                        <td>{{ $item->units }}</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>{{ number_format($item->rate, 2) }}</td>
                                        <td>{{ number_format($item->amount, 2) }}</td>
                                        <td>
                                            <!-- Edit and Delete buttons -->
                                                    <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editItemModal{{ $item->id }}">
                                                        Edit
                                                    </button>

                                                    @include('bq_sections.modals.edit_item')
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">{{ __('No items found.') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="1">{{ __('Total') }}</th>
                                    <td></td> <!-- Leave unit column empty -->
                                    <td class="fw-bold">{{ $totalQuantity }}</td>
                                    <td></td> <!-- Leave rate column empty -->
                                    <td class="fw-bold">{{ number_format($totalAmount, 2) }}</td>
                                    <td></td> <!-- Leave actions column empty -->
                                </tr>
                            </tfoot>
                        </table>
                        <!-- Link Back to Document -->
                        <a href="#" class="btn btn-secondary mt-4">
                            {{ __('Back') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
