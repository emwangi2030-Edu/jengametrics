@php
    use Carbon\Carbon;
@endphp

@extends('layouts.appbar')

@section('content')
    <div class="card shadow-sm mt-4">
        <div class="card-body">
            <h2 class="mb-4" style="color:#027333">Daily Attendance</h2>

            {{-- Navigation --}}
            <div class="mb-3 d-flex align-items-center">
                <button type="button" id="prevBtn" class="btn btn-outline-secondary me-2">&laquo;</button>

                <input type="date" id="datePicker" class="form-control w-auto me-2"
                    value="{{ $date }}" onchange="loadAttendance(this.value)" required>

                <button type="button" id="nextBtn" class="btn btn-outline-secondary me-2">&raquo;</button>
                <button type="button" id="todayBtn" class="btn btn-success">Today</button>
            </div>

            {{-- Attendance form --}}
            <form method="POST" action="{{ route('attendance.store') }}">
                @csrf

                <div id="attendance-container">
                    {{-- Initial attendance table loads here --}}
                    @include('attendance.partials.table', [
                        'workers' => $workers,
                        'date' => $date,
                        'existingAttendances' => $existingAttendances
                    ])
                </div>

                <br>
                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-primary">Save Attendance</button>
                    <a href="{{ route('workers.index') }}" class="btn btn-secondary">Back</a>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function formatDate(d) {
        return d.toISOString().split('T')[0];
    }

    function loadAttendance(date) {
        fetch("{{ route('attendance.fetch') }}?date=" + date)
            .then(response => response.text())
            .then(html => {
                document.getElementById('attendance-container').innerHTML = html;
                document.getElementById('datePicker').value = date;

                const selectedDate = new Date(date);
                const today = new Date();

                // Update button behavior
                document.getElementById('prevBtn').onclick = () => {
                    let prev = new Date(selectedDate);
                    prev.setDate(prev.getDate() - 1);
                    loadAttendance(formatDate(prev));
                };

                document.getElementById('nextBtn').onclick = () => {
                    let next = new Date(selectedDate);
                    next.setDate(next.getDate() + 1);
                    loadAttendance(formatDate(next));
                };

                document.getElementById('todayBtn').onclick = () => {
                    loadAttendance(formatDate(today));
                };

                // Hide Next/Today if viewing today
                if (formatDate(selectedDate) === formatDate(today)) {
                    document.getElementById('nextBtn').style.display = 'none';
                    document.getElementById('todayBtn').style.display = 'none';
                } else {
                    document.getElementById('nextBtn').style.display = 'inline-block';
                    document.getElementById('todayBtn').style.display = 'inline-block';
                }
            })
            .catch(error => console.error('Error loading attendance:', error));
    }

    // Initialize button logic on first load
    document.addEventListener("DOMContentLoaded", function() {
        loadAttendance(document.getElementById('datePicker').value);
    });
</script>
@endpush
