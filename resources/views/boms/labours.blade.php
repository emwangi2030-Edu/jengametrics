<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card">
            <div class="card-body">
                <!-- Table to display items -->
                <h3 class="text-lg font-weight-bold mt-6" style="color:#027333">{{ __('Labour cost') }}</h3>
                    <table class="table mt-4">
                        <thead class="table-light">
                            <tr>
                                <th scope="col">{{ __('Description') }}</th>
                                <th scope="col">{{ __('No. of Labourers') }}</th>
                                <th scope="col">{{ __('Rate') }}</th>
                                <th scope="col">{{ __('Amount') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $totalQuantity = 0;
                                $totalAmount = 0;
                            @endphp
                            @forelse ($labours as $item)
                                @php
                                    $totalQuantity += $item->quantity;
                                    $totalAmount += $item->amount;
                                @endphp
                                <tr>
                                    <td><div class="px-2">{{ $item->item->name }}</div></td>
                                    <td>{{ $item->quantity }}</td>
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
                                <td><b>{{ $totalQuantity }}</b></td>
                                <td></td> <!-- Leave rate column empty -->
                                <td><b>{{ number_format($totalAmount, 2) }}</b></td>
                        
                            </tr>
                        </tfoot>
                    </table>
                <!-- Link Back to Document -->
                <a href="{{ route('boms.index') }}" class="btn btn-secondary mt-4">
                    {{ __('Back') }}
                </a>
            </div>
        </div>
    </div>
</div>