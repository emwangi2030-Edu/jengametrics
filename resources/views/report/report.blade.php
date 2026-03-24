@extends('layouts.app')

@section('content')
<div class="container py-4">
    <!-- Header -->
    <div class="jm-page-header">
        <div>
            <h2 class="jm-page-title">{{ __('Project Cost Summary:') }} <span class="text-dark">{{ $project->name }}</span></h2>
            <p class="jm-page-subtitle mb-0">{{ __('Compare estimated and actual material/labour costs.') }}</p>
        </div>
            <div class="jm-actions-bar">
                <a href="{{ route('reports.purchases') }}" class="btn btn-outline-primary btn-sm">
                    {{ __('Purchases Report') }}
                </a>
                <a href="{{ route('reports.wages') }}" class="btn btn-outline-primary btn-sm">
                    {{ __('Wages Report') }}
                </a>
            </div>
        </div>
    </div>

    <!-- Summary and Graphs -->
    <div class="row">
        <!-- Summary Cards -->
        <div class="col-md-6 mb-4">
            <div class="card shadow">
                <div class="card-body text-center">
                    <h4 class="jm-section-title">{{ __('Total Estimated Cost') }}</h4>
                    <h2 class="text-dark">
                        {{ number_format(($totalEstimatedCost ?? 0) + ($totalEstimatedCost_labour ?? 0), 2) }}
                    </h2>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <div class="card shadow">
                <div class="card-body text-center">
                    <h4 class="jm-section-title">{{ __('Total Actual Cost') }}</h4>
                    <h2 class="text-dark">
                        {{ number_format(($total_actual_cost ?? 0) + ($total_actual_payments ?? 0), 2) }}
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
                    <h5 class="jm-section-title">{{ __('Estimated Cost Breakdown') }}</h5>
                    <canvas id="estimatedCostChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-body">
                    <h5 class="jm-section-title">{{ __('Actual Cost Breakdown') }}</h5>
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
                    <h5 class="jm-section-title">{{ __('Estimated Cost Details') }}</h5>
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
                    <h5 class="jm-section-title">{{ __('Actual Cost Details') }}</h5>
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
                                <td>{{ number_format($total_actual_payments, 2) }}</td>
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
                    data: [{{ $total_actual_cost ?? 0 }}, {{ $total_actual_payments ?? 0 }}], // Adjust this if actual labour cost exists
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
