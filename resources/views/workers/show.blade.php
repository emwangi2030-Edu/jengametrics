@extends('layouts.appbar')

@section('content')
<div class="container py-4">
    <h2 class="text-success mb-4">Worker Profile</h2>

    <div class="row">
        <div class="col-md-6">
            <div class="card shadow-sm mb-3">
                <div class="card-body">
                    <h4 class="text-dark">{{ $worker->full_name }}</h4>
                    <p><strong>ID Number:</strong> {{ $worker->id_number }}</p>
                    <p><strong>Job Category:</strong> {{ $worker->job_category }}</p>
                    <p><strong>Work Type:</strong> {{ $worker->work_type }}</p>
                    <p><strong>Phone:</strong> {{ $worker->phone }}</p>
                    <p><strong>Email:</strong> {{ $worker->email ?? 'N/A' }}</p>
                    <p><strong>Payment Amount:</strong> {{ $worker->payment_amount }}</p>
                    <p><strong>Payment Frequency:</strong> {{ $worker->payment_frequency }}</p>
                    <a href="{{ route('workers.index') }}" class="btn btn-secondary mt-3">Back</a>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow-sm mb-3">
                <div class="card-body">
                    <h4 class="text-dark mb-3">Attendance Chart</h4>

                    <!-- Filter Form -->
                    <form method="GET" action="{{ route('workers.show', $worker->id) }}" class="row mb-4 g-2">
                        <div class="col-md-6">
                            <label for="month">Month</label>
                            <select name="month" class="form-select" onchange="this.form.submit()">
                                @foreach($availableMonths as $num => $name)
                                    <option value="{{ $num }}" {{ $month == $num ? 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="year">Year</label>
                            <select name="year" class="form-select" onchange="this.form.submit()">
                                @foreach($availableYears as $yr)
                                    <option value="{{ $yr }}" {{ $year == $yr ? 'selected' : '' }}>{{ $yr }}</option>
                                @endforeach
                            </select>
                        </div>
                    </form>

                    <!-- Chart Canvas -->
                    <canvas id="attendanceChart" height="300"></canvas>
                    <!-- Download Button -->
                    <div class="mt-3 text-end">
                        <button class="btn btn-outline-primary btn-sm" onclick="downloadChart()">Download Chart</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('attendanceChart').getContext('2d');
    const attendanceChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($labels) !!},
            datasets: [
                {
                    label: 'Present',
                    data: {!! json_encode($presentData) !!},
                    backgroundColor: {!! json_encode(
                        collect($presentData)->map(function($v, $i) use ($inactiveData) {
                            return !$inactiveData[$i] && $v ? 'rgba(40, 167, 69, 0.6)' : 'rgba(0,0,0,0)';
                        })
                    ) !!},
                    borderColor: {!! json_encode(
                        collect($presentData)->map(function($v, $i) use ($inactiveData) {
                            return !$inactiveData[$i] && $v ? 'rgba(40, 167, 69, 1)' : 'rgba(0,0,0,0)';
                        })
                    ) !!},
                    backgroundColorLegend: 'rgba(40, 167, 69, 0.6)', // fallback for legend (custom)
                    borderColorLegend: 'rgba(40, 167, 69, 1)',       // fallback for legend (custom)
                    borderWidth: 1
                },
                {
                    label: 'Absent',
                    data: {!! json_encode($absentData) !!},
                    backgroundColor: {!! json_encode(
                        collect($absentData)->map(function($v, $i) use ($inactiveData) {
                            return !$inactiveData[$i] && $v ? 'rgba(220, 53, 69, 0.6)' : 'rgba(0,0,0,0)';
                        })
                    ) !!},
                    borderColor: {!! json_encode(
                        collect($absentData)->map(function($v, $i) use ($inactiveData) {
                            return !$inactiveData[$i] && $v ? 'rgba(220, 53, 69, 1)' : 'rgba(0,0,0,0)';
                        })
                    ) !!},
                    backgroundColorLegend: 'rgba(220, 53, 69, 0.6)',
                    borderColorLegend: 'rgba(220, 53, 69, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Inactive',
                    data: {!! json_encode($inactiveData) !!},
                    backgroundColor: {!! json_encode(
                        collect($inactiveData)->map(function($v) {
                            return $v ? 'rgba(200,200,200,0.5)' : 'rgba(0,0,0,0)';
                        })
                    ) !!},
                    borderColor: {!! json_encode(
                        collect($inactiveData)->map(function($v) {
                            return $v ? 'rgba(200,200,200,0.8)' : 'rgba(0,0,0,0)';
                        })
                    ) !!},
                    backgroundColorLegend: 'rgba(200,200,200,0.5)',
                    borderColorLegend: 'rgba(200,200,200,0.8)',
                    borderWidth: 1
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'Attendance for {{ \Carbon\Carbon::create()->month((int) $month)->format('F') }} {{ $year }}'
                },
                legend: {
                    display: true,
                    position: 'top'
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.dataset.label + ': ' + (context.raw ? '✓' : '');
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1,
                        callback: function(value) {
                            return value === 1 ? '✓' : '';
                        }
                    }
                }
            }
        }
    });
</script>
<script>
    function downloadChart() {
        const link = document.createElement('a');
        link.download = 'attendance_chart_{{ $worker->full_name }}.png';
        link.href = document.getElementById('attendanceChart').toDataURL('image/png');
        link.click();
    }
</script>
@endsection
