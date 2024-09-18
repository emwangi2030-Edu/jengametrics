@extends('layouts.appbar')
@section('content')

    <h1 class="text-success">Dashboard</h1>
    <div class="container mt-5">
        <h2>Project Stats</h2>

        <div class="row">
            <!-- Card for Total Workers -->
            <div class="col-lg-6">
                <div class="card text-center">
                    <div class="card-body">
                        <h3>Total Workers</h3>
                        <p>{{ $totalWorkers }}</p>
                    </div>
                </div>
            </div>

            <!-- Card for Total Material Expenses -->
            <div class="col-lg-6">
                <div class="card text-center">
                    <div class="card-body">
                        <h3>Total Material Expenses</h3>
                        <p>KES {{ number_format($totalMaterialExpenses, 2) }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
