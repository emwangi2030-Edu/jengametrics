@extends('layouts.app')

@section('content')

<div class="container-fluid">
    <h1 class="my-4" style="color:#027333">Dashboard</h1>

    <div class="row justify-content-center g-4">
        <!-- Total Workers Card -->
        <div class="col-md-4">
            <div class="card shadow border-0">
                <div class="card-body text-center">
                    <h5 class="card-title" style="color:#027333">Total Workers</h5>
                    <h3 class="text-dark">{{ $totalWorkers}}</h3>
                </div>
            </div>
        </div>

        <!-- Total Material Expenses Card -->
        <div class="col-md-4">
            <div class="card shadow border-0">
                <div class="card-body text-center">
                    <h5 class="card-title" style="color:#027333">Total Material Expenses</h5>
                    <h3 class="text-dark">KES {{ number_format($totalMaterialExpenses, 2) }}</h3>
                </div>
            </div>
        </div>
    </div>

    <br>

    <form method="GET" action="{{ route('dashboard') }}" class="mb-3">
        <label for="year" style="color:#027333">Filter by Year:</label>
        <select name="year" id="year" onchange="this.form.submit()" class="form-select w-auto d-inline-block">
            @foreach ($availableYears as $year)
                <option value="{{ $year }}" {{ $year == $selectedYear ? 'selected' : '' }}>
                    {{ $year }}
                </option>
            @endforeach
        </select>
    </form>

    <div class="row mt-4">
        <div class="col-md-12">
            <h3 style="color:#027333">Material Expense Trends</h3>
            <canvas id="expenseChart"></canvas>
        </div>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('expenseChart').getContext('2d');

    const expenseChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($labels),
            datasets: [{
                label: 'Material Expenses',
                data: @json($data),
                borderColor: 'rgba(75, 192, 192, 1)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderWidth: 2,
                fill: true,
                tension: 0.3
            }]
        },
        options: {
        responsive: true,
        animation: {
            duration: 1000,
            easing: 'easeOutQuart'
        },
        animations: {
            tension: {
                duration: 1000,
                easing: 'easeOutQuart',
                from: 0.5,
                to: 0.3,
                loop: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 20000,
                    callback: function(value) {
                        return value.toLocaleString();
                    }
                },
                title: {
                    display: true,
                    text: 'Expense (KES)',
                    color: '#333',
                    font: {
                        size: 14,
                        weight: 'bold'
                    }
                }
            }
        },
        plugins: {
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return 'KES ' + context.parsed.y.toLocaleString();
                    }
                }
            }
        }
    }
    });
</script>
@endsection
