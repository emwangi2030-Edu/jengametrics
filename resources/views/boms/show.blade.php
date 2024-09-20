@extends('layouts.appbar')

@section('content')

    <x-slot name="header">
        <h2 class="font-weight-bold text-primary">
            {{ __('BOM Details: ') . $bom->bom_name }}
        </h2>
    </x-slot>

    <div class="row justify-content-center">
        <div class="col-lg-12 col-md-12">
            <div class="card shadow-sm border-primary">
                <div class="card-header bg-primary text-white">
                    <h3 class="h5">{{ __('BOM Information') }}</h3>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <p><strong>{{ __('BOM Name:') }}</strong> {{ $bom->bom_name }}</p>
                        <p><strong>{{ __('Created At:') }}</strong> {{ $bom->created_at->format('Y-m-d H:i:s') }}</p>
                    </div>

                    <div class="table-responsive">
                        <h4 class="font-weight-bold mb-3">{{ __('Items') }}</h4>
                        <table class="table table-bordered table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th>{{ __('Item Description') }}</th>
                                    <th>{{ __('Quantity') }}</th>
                                    <th>{{ __('Unit') }}</th>
                                    <th>{{ __('Rate') }}</th>
                                    <th>{{ __('Amount') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($bom->items as $item)
                                    <tr>
                                        <td>{{ $item->item_description }}</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>{{ $item->unit }}</td>
                                        <td>{{ number_format($item->rate, 2) }}</td>
                                        <td>{{ number_format($item->amount, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4 d-flex justify-content-end">
                        <h4 class="text-dark"><strong>{{ __('Total Amount: ') }}</strong> KES {{ number_format($bom->items->sum('amount'), 2) }}</h4>
                    </div>

                    <div class="mt-4">
                        <a href="{{ route('boms.index') }}" class="btn btn-outline-primary btn-lg">{{ __('Back to BOMs') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
