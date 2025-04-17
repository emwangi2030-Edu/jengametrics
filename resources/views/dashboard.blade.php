@extends('layouts.appbar')

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
                            <h3 class="text-dark">KES {{ $totalMaterialExpenses }}</h3>
                        </div>
                    </div>
                </div>
            </div>

      

            <!-- Chart Section (Placeholder for Chart.js) -->
            <div class="row mt-4">
                <div class="col-md-12">
                    <h3 style="color:#027333">Expense Trends</h3>
                    <canvas id="expenseChart"></canvas>
                </div>
            </div>

      
</div>


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
