@extends('layouts.appbar')

@section('content')

<div class="container-fluid">
  
  
            <h1 class="text-success my-4">Dashboard</h1>

            <div class="row g-4">
                <!-- Total Workers Card -->
                <div class="col-md-4">
                    <div class="card shadow border-0">
                        <div class="card-body text-center">
                            <h5 class="card-title text-success">Total Workers</h5>
                            <h3 class="text-dark">{{ $totalWorkers}}</h3>
                        </div>
                    </div>
                </div>

                <!-- Total Material Expenses Card -->
                <div class="col-md-4">
                    <div class="card shadow border-0">
                        <div class="card-body text-center">
                            <h5 class="card-title text-success">Total Material Expenses</h5>
                            <h3 class="text-dark">KES {{ $totalMaterialExpenses }}</h3>
                        </div>
                    </div>
                </div>

                <!-- Upcoming Projects Card -->
                <div class="col-md-4">
                    <div class="card shadow border-0">
                        <div class="card-body text-center">
                            <h5 class="card-title text-success">Upcoming Projects</h5>
                            <h3 class="text-dark">0</h3>
                        </div>
                    </div>
                </div>
            </div>

      

            <!-- Chart Section (Placeholder for Chart.js) -->
            <div class="row mt-4">
                <div class="col-md-12">
                    <h3 class="text-success">Expense Trends</h3>
                    <canvas id="expenseChart"></canvas>
                </div>
            </div>

      
</div>

<!-- Bootstrap 5 JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Chart.js for Graphs -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    var ctx = document.getElementById('expenseChart').getContext('2d');
    var expenseChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            datasets: [{
                label: 'Material Expenses (KES)',
                data: [50000, 70000, 65000, 85000, 90000, 120000],
                borderColor: 'rgba(75, 192, 192, 1)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
</script>

@endsection
