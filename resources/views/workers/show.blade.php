@extends('layouts.app')

@php
    $photoUrl = null;
    $picturePath = $worker->picture;

    if (!empty($picturePath)) {
        if (preg_match('/^https?:\/\//i', $picturePath)) {
            $photoUrl = $picturePath;
        } elseif (\Illuminate\Support\Str::startsWith($picturePath, ['storage/', '/storage/'])) {
            $photoUrl = asset($picturePath);
        } else {
            $photoUrl = \Illuminate\Support\Facades\Storage::disk('public')->url($picturePath);
        }
    }
@endphp

@section('content')
<div class="container py-4">
    <h2 class="mb-4" style="color:#027333;">Worker Profile</h2>

    <div class="row">
        <div class="col-md-6">
            <div class="card shadow-sm mb-3">
                <div class="d-flex flex-column flex-md-row align-items-stretch">
                    <div class="card-body flex-grow-1">
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
                    <div class="p-3 border-top border-md-top-0 border-md-start d-flex align-items-start justify-content-center" style="min-width:220px; flex:0 0 240px;">
                        <img
                            src="{{ $photoUrl ?? asset('assets/media/svg/avatars/blank.svg') }}"
                            alt="{{ $worker->full_name }} photo"
                            class="img-fluid rounded shadow-sm"
                            style="width: 100%; height: auto; object-fit: contain;">
                    </div>
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
                    <div class="d-flex flex-wrap align-items-center mt-2" style="gap: 12px;">
                        <span class="d-flex align-items-center">
                            <span style="width:12px;height:12px;background-color:#28a745;border-radius:2px;display:inline-block;margin-right:6px;"></span>
                            <small class="text-muted">Present</small>
                        </span>
                        <span class="d-flex align-items-center">
                            <span style="width:12px;height:12px;background-color:#dc3545;border-radius:2px;display:inline-block;margin-right:6px;"></span>
                            <small class="text-muted">Absent</small>
                        </span>
                        <span class="d-flex align-items-center">
                            <span style="width:12px;height:12px;background-color:#add8e6;border-radius:2px;display:inline-block;margin-right:6px;"></span>
                            <small class="text-muted">Weekend</small>
                        </span>
                        <span class="d-flex align-items-center">
                            <span style="width:12px;height:12px;background-color:#c8c8c8;border-radius:2px;display:inline-block;margin-right:6px;"></span>
                            <small class="text-muted">Inactive</small>
                        </span>
                    </div>
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
    const attendanceDataUrl = `/workers/{{ $worker->id }}/attendance-data`;
    let attendanceChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: [],
            datasets: [
                {
                    label: 'Attendance',
                    data: [],
                    backgroundColor: [],
                    borderColor: [],
                    borderWidth: 1,
                    customStatuses: []
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'Attendance for ...'
                },
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function (context) {
                            const statuses = context.dataset.customStatuses || [];
                            return statuses[context.dataIndex] || '';
                        }
                    }
                }
            },
            scales: {
                y: {
                    display: false,
                    beginAtZero: true,
                    suggestedMax: 1
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });

    function loadAttendanceData() {
        const monthSelect = document.getElementById('month');
        const yearSelect = document.getElementById('year');

        if (!monthSelect || !yearSelect) {
            return;
        }

        const month = monthSelect.value;
        const year = yearSelect.value;
        const url = `${attendanceDataUrl}?month=${month}&year=${year}&_=${Date.now()}`;

        fetch(url, { cache: 'no-store' })
            .then(res => res.json())
            .then(data => {
                const dataset = attendanceChart.data.datasets[0];

                attendanceChart.data.labels = data.labels;
                dataset.data = data.values || [];
                dataset.backgroundColor = data.backgroundColors || [];
                dataset.borderColor = data.borderColors || [];
                dataset.customStatuses = data.statuses || [];

                attendanceChart.options.plugins.title.text = data.title;
                attendanceChart.update();
            })
            .catch(error => console.error('Unable to load attendance data', error));
    }

    const monthFilter = document.getElementById('month');
    const yearFilter = document.getElementById('year');

    if (monthFilter) {
        monthFilter.addEventListener('change', loadAttendanceData);
    }

    if (yearFilter) {
        yearFilter.addEventListener('change', loadAttendanceData);
    }

    window.addEventListener('load', loadAttendanceData);

    window.addEventListener('storage', (event) => {
        if (event.key === 'attendance:lastSaved') {
            loadAttendanceData();
        }
    });

    document.addEventListener('visibilitychange', () => {
        if (!document.hidden) {
            loadAttendanceData();
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
