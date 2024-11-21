@extends('layouts.appbar')

@section('content')
<div class="container py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="font-weight-bold text-success">
                {{ __('Total Estimated Cost Report') }}
            </h2>
        </div>
    </div>

    <div class="row">
        <div class="col-md-10">
            <div class="card">
                <div class="card-body">
                    <!-- Display Total Estimated Cost -->
                    <h3 class="font-weight-bold text-primary">
                        {{ __('Total Estimated Cost: ') }} 
                        <span class="text-dark">
                            {{ number_format($totalEstimatedCost, 2) }}
                        </span>
                    </h3>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
