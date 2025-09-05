@extends('layouts.appbar')

@section('content')
<div class="container py-4">
    <h2 class="mb-4" style="color:#027333;">Worker Profile</h2>

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
                    <p><strong>Start Date:</strong> {{ $worker->created_at->format('d F, Y') }}</p>
                    <p><strong>Payment Rate:</strong> {{ $worker->payment_amount }}</p>
                    <p><strong>Payment Frequency:</strong> {{ $worker->payment_frequency }}</p>
                    <p><strong>Mode of Payment:</strong> {{ $worker->mode_of_payment }}</p>
                    @if ($worker->mode_of_payment == 'Bank')
                        <p><strong>Bank Name:</strong> {{ $worker->bank_name }}</p>
                        <p><strong>Bank Account:</strong> {{ $worker->bank_account }}</p>
                    @endif
                    <p><strong>Amount Owed:</strong> {{ number_format($amountOwed, 2) }}</p>
                    <a href="{{ route('payments.index', $worker->id) }}" class="btn btn-primary mb-3">
                        View Payment History
                    </a>
                    <form action="{{ route('payments.store', $worker->id) }}" method="POST">
                        @csrf
                        <input type="hidden" name="amount" value="{{ $amountOwed }}">
                        <button type="submit" class="btn btn-success"
                            @if($amountOwed <= 0) disabled @endif>
                            Record Payment
                        </button>
                    </form>
                    <a href="{{ route('workers.index') }}" class="btn btn-secondary mt-3">Back</a>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow-sm mb-3">
                <div class="card-body">
                    <h4 class="text-dark mb-3">Attendance Chart</h4>

                    <!-- Filter Form -->
                    <div class="row mb-4 g-2">
                        <div class="col-md-6">
                            <label for="month">Month</label>
                            <select id="month" class="form-select">
                                @foreach($availableMonths as $num => $name)
                                    <option value="{{ $num }}" {{ $month == $num ? 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="year">Year</label>
                            <select id="year" class="form-select">
                                @foreach($availableYears as $yr)
                                    <option value="{{ $yr }}" {{ $year == $yr ? 'selected' : '' }}>{{ $yr }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>


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
    let attendanceChart = new Chart(ctx, {
        type: 'bar',
        data: { labels: [], datasets: [] },
        options: {
        responsive: true,
        plugins: {
            title: {
                display: true,
                text: 'Attendance for ...'
            },
            legend: {
                display: true,
                position: 'top'
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        if (context.dataset.label === "Present") return "Present ✓";
                        if (context.dataset.label === "Absent") return "Absent ✗";
                        if (context.dataset.label === "Inactive") return "Inactive";
                        return "";
                    }
                }
            }
        },
        scales: {
            y: {
                display: false
            }
        }
    }
    
    });

    function loadAttendanceData() {
        const month = document.getElementById('month').value;
        const year = document.getElementById('year').value;

        fetch(`/workers/{{ $worker->id }}/attendance-data?month=${month}&year=${year}`)
            .then(res => res.json())
            .then(data => {
                attendanceChart.data.labels = data.labels;
                attendanceChart.data.datasets = [
                    {
                        label: 'Present',
                        data: data.presentData,
                        backgroundColor: 'rgba(40, 167, 69, 0.6)',
                        borderColor: 'rgba(40, 167, 69, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Absent',
                        data: data.absentData,
                        backgroundColor: 'rgba(220, 53, 69, 0.6)',
                        borderColor: 'rgba(220, 53, 69, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Inactive',
                        data: data.inactiveData,
                        backgroundColor: 'rgba(200,200,200,0.5)',
                        borderColor: 'rgba(200,200,200,0.8)',
                        borderWidth: 1
                    }
                ];
                attendanceChart.options.plugins.title = { display: true, text: data.title };
                attendanceChart.update();
            });
    }

    // trigger on page load & dropdown changes
    document.getElementById('month').addEventListener('change', loadAttendanceData);
    document.getElementById('year').addEventListener('change', loadAttendanceData);
    window.addEventListener('load', loadAttendanceData);
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
