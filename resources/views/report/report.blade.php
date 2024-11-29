@extends('layouts.appbar')

@section('content')
<div class="container py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="font-weight-bold text-success">
                {{ __('Total Cost Report') }}
            </h2>
        </div>
    </div>

    <!-- Summary and Graphs -->
    <div class="row">
        <!-- Summary Cards -->
        <div class="col-md-6 mb-4">
            <div class="card shadow">
                <div class="card-body text-center">
                    <h4 class="font-weight-bold text-primary">{{ __('Total Estimated Cost') }}</h4>
                    <h2 class="text-dark">
                        {{ number_format(($totalEstimatedCost ?? 0) + ($totalEstimatedCost_labour ?? 0), 2) }}
                    </h2>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <div class="card shadow">
                <div class="card-body text-center">
                    <h4 class="font-weight-bold text-primary">{{ __('Total Actual Cost') }}</h4>
                    <h2 class="text-dark">
                        {{ number_format($total_actual_cost ?? 0, 2) }}
                    </h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Graph Section -->
    <div class="row">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-body">
                    <h5 class="font-weight-bold text-secondary">{{ __('Estimated Cost Breakdown') }}</h5>
                    <canvas id="estimatedCostChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-body">
                    <h5 class="font-weight-bold text-secondary">{{ __('Actual Cost Breakdown') }}</h5>
                    <canvas id="actualCostChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed Breakdown Tables -->
    <div class="row mt-4">
        <!-- Estimated Cost Table -->
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-body">
                    <h5 class="font-weight-bold text-secondary">{{ __('Estimated Cost Details') }}</h5>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>{{ __('Description') }}</th>
                                <th>{{ __('Amount') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ __('Materials') }}</td>
                                <td>{{ number_format($totalEstimatedCost ?? 0, 2) }}</td>
                            </tr>
                            <tr>
                                <td>{{ __('Labour') }}</td>
                                <td>{{ number_format($totalEstimatedCost_labour ?? 0, 2) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Actual Cost Table -->
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-body">
                    <h5 class="font-weight-bold text-secondary">{{ __('Actual Cost Details') }}</h5>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>{{ __('Description') }}</th>
                                <th>{{ __('Amount') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ __('Materials') }}</td>
                                <td>{{ number_format($total_actual_cost ?? 0, 2) }}</td>
                            </tr>
                            <tr>
                                <td>{{ __('Labour') }}</td>
                                <td>{{ number_format(0, 2) }}</td> <!-- Replace with actual labour cost if available -->
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Estimated Cost Chart
        const estimatedCtx = document.getElementById('estimatedCostChart').getContext('2d');
        new Chart(estimatedCtx, {
            type: 'pie',
            data: {
                labels: ['Materials', 'Labour'],
                datasets: [{
                    data: [{{ $totalEstimatedCost ?? 0 }}, {{ $totalEstimatedCost_labour ?? 0 }}],
                    backgroundColor: ['#007bff', '#28a745'],
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'bottom' },
                }
            }
        });

        // Actual Cost Chart
        const actualCtx = document.getElementById('actualCostChart').getContext('2d');
        new Chart(actualCtx, {
            type: 'pie',
            data: {
                labels: ['Materials', 'Labour'],
                datasets: [{
                    data: [{{ $total_actual_cost ?? 0 }}, 0], // Adjust this if actual labour cost exists
                    backgroundColor: ['#6f42c1', '#e83e8c'],
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'bottom' },
                }
            }
        });
    });
</script>
@endsection
