@extends('layouts.appbar')

@section('content')
    <div class="container py-4">
        <div class="row mb-4">
            <div class="col-12">
                <h2 class="font-weight-bold text-success">
                    {{ __('Section: ') . $bqSection->name }}
                </h2>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-body">
                        <!-- Display Section Details -->
                        <p><strong>{{ __('Details:') }}</strong> {{ $bqSection->description }}</p>
                    

                 

                        <!-- Link to Add New Item -->
                        <!-- <a href="{{ route('boms.create') }}" class="btn btn-success mt-4">
                            {{ __('Add New Item') }}
                        </a> -->

                        <!-- Table to display items -->
                        <h3 class="text-lg font-weight-bold mt-6">{{ __('Items List') }}</h3>
                        <table class="table table-striped mt-4">
                            <thead>
                                <tr>
                                    <th scope="col">{{ __('Description') }}</th>
                                    <th scope="col">{{ __('Quantity') }}</th>
                                    <th scope="col">{{ __('Unit') }}</th>
                                    <th scope="col">{{ __('Rate') }}</th>
                                    <th scope="col">{{ __('Amount') }}</th>
                                    <th scope="col">{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($items as $item)
                                    <tr>
                                        <td>{{ $item->item_material->name }}</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>{{ $item->item_material->unit_of_measurement }}</td>
                                        <td>{{ number_format($item->rate, 2) }}</td>
                                        <td>{{ number_format($item->amount, 2) }}</td>
                                        <td>
                                            <!-- Add Edit and Delete Links for Items Here if needed -->
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">{{ __('No items found.') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                        <!-- Link Back to Document -->
                        <a href="#" class="btn btn-secondary mt-4">
                            {{ __('Back to Document') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
