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

        <div class="row">
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
                <td>{{ $item->item_material->name }}</td>
                <td>{{ $item->quantity }}</td>
                <td>{{ $item->item_material->unit_of_measurement }}</td>
                <td>{{ number_format($item->rate, 2) }}</td>
                <td>{{ number_format($item->amount, 2) }}</td>
             
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
            <td>{{ $totalQuantity }}</td>
            <td></td> <!-- Leave unit column empty -->
            <td></td> <!-- Leave rate column empty -->
            <td>{{ number_format($totalAmount, 2) }}</td>

        </tr>
    </tfoot>
</table>


                
                    </div>
                </div>
            </div>
        </div>

        @include('boms.labours')
    </div>
@endsection
