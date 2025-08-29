@extends('layouts.appbar')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4" style="color:#027333;">Add Worker</h2>

    <div class="card shadow-sm w-75 m-auto">
        <div class="card-body">
            <form method="POST" action="{{ route('workers.store') }}">
                @csrf
                <input type="hidden" name="project_id" value="{{ $projectId }}">

                <div class="mb-3">
                    <label for="full_name" class="form-label">Full Name</label>
                    <input type="text" name="full_name" class="form-control" value="{{ old('full_name') }}" required>
                </div>

                <div class="mb-3">
                    <label for="id_number" class="form-label">ID Number</label>
                    <input type="number" name="id_number" class="form-control" value="{{ old('id_number') }}" required>
                </div>

                <div class="mb-3">
                    <label for="job_category" class="form-label">Job Category</label>
                    <select name="job_category" class="form-select" required>
                        <option value="">Select Job Category</option>
                        @foreach ([
                            'Mason', 'Site Manager', 'Quantity Surveyor', 'Carpenter', 'Plumber',
                            'Helper/Casual', 'Painter', 'Sub Contractor', 'Electrician',
                            'Supervisor', 'Assistant Supervisor'
                        ] as $role)
                            <option value="{{ $role }}" {{ old('job_category') == $role ? 'selected' : '' }}>{{ $role }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="work_type" class="form-label">Work Type</label>
                    <select name="work_type" class="form-select" required>
                        <option value="">Select Work Type</option>
                        <option value="Under Contract" {{ old('work_type') == 'Under Contract' ? 'selected' : '' }}>Under Contract</option>
                        <option value="Casual" {{ old('work_type') == 'Casual' ? 'selected' : '' }}>Casual</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="phone" class="form-label">Phone</label>
                    <input type="number" name="phone" class="form-control" value="{{ old('phone') }}">
                </div>

                <div class="mb-4">
                    <label for="email" class="form-label">Email <span class="text-muted">(optional)</span></label>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}">
                </div>

                <div class="mb-3">
                    <label for="payment_amount" class="form-label">Payment Rate (KES)</label>
                    <input type="number" name="payment_amount" class="form-control" step="0.01" value="{{ old('payment_amount') }}">
                </div>

                <div class="mb-3">
                    <label for="payment_frequency" class="form-label">Payment Frequency</label>
                    <select name="payment_frequency" id="payment_frequency" class="form-select">
                        <option value="">Select Frequency</option>
                        <option value="per_day">Per Day</option>
                        <option value="per_week">Per Week</option>
                        <option value="per_month">Per Month</option>
                        <option value="one_time_payment">One-Time Payment</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="mode_of_payment" class="form-label">Mode of Payment</label>
                    <select name="mode_of_payment" id="mode_of_payment" class="form-select" required>
                        <option value="">Select Mode</option>
                        <option value="MPESA" {{ old('mode_of_payment') == 'MPESA' ? 'selected' : '' }}>MPESA</option>
                        <option value="Airtel Money" {{ old('mode_of_payment') == 'Airtel Money' ? 'selected' : '' }}>Airtel Money</option>
                        <option value="Bank" {{ old('mode_of_payment') == 'Bank' ? 'selected' : '' }}>Bank</option>
                        <option value="Cash" {{ old('mode_of_payment') == 'Cash' ? 'selected' : '' }}>Cash</option>
                    </select>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('workers.index') }}" class="btn btn-secondary">Back</a>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const workType = document.querySelector('select[name="work_type"]');
        const frequency = document.querySelector('select[name="payment_frequency"]');

        const updateFrequencyOptions = () => {
            const selected = workType.value;
            frequency.innerHTML = ''; // Clear all options

            if (selected === 'Casual') {
                frequency.innerHTML += `<option value="per day">Per Day</option>`;
                frequency.innerHTML += `<option value="per week">Per Week</option>`;
            } else if (selected === 'Under Contract') {
                frequency.innerHTML += `<option value="per month">Per Month</option>`;
            } else {
                frequency.innerHTML += `<option value="">Select Frequency</option>`;
            }
        };

        workType.addEventListener('change', updateFrequencyOptions);

        // Initialize on load
        updateFrequencyOptions();
    });
</script>
@endpush
