@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <h2 class="mb-4" style="color:#027333;">Add Worker</h2>

        <div class="card shadow-sm w-75 m-auto">
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('workers.store') }}" enctype="multipart/form-data">
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
                        <input type="number" name="phone" class="form-control" value="{{ old('phone') }}" required>
                    </div>

                    <div class="mb-4">
                        <label for="email" class="form-label">Email <span class="text-muted">(optional)</span></label>
                        <input type="email" name="email" class="form-control" value="{{ old('email') }}">
                    </div>

                    <div class="mb-3">
                        <label for="picture" class="form-label">Profile Photo <span class="text-muted">(optional)</span></label>
                        <input type="file" name="picture" id="picture" class="form-control" accept="image/*">
                    </div>

                    <div class="mb-3">
                        <label for="payment_amount" class="form-label">Payment Rate (KES)</label>
                        <input type="number" name="payment_amount" class="form-control" step="0.01" value="{{ old('payment_amount') }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="payment_frequency" class="form-label">Payment Frequency</label>
                        <select name="payment_frequency" id="payment_frequency" class="form-select" required>
                            <option value="" {{ old('payment_frequency') ? '' : 'selected' }}>Select Frequency</option>
                            <option value="per day" {{ old('payment_frequency') == 'per day' ? 'selected' : '' }}>Per Day</option>
                            <option value="per month" {{ old('payment_frequency') == 'per month' ? 'selected' : '' }}>Per Month</option>
                            <option value="one-time payment" {{ old('payment_frequency') == 'one-time payment' ? 'selected' : '' }}>One-time Payment</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="mode_of_payment" class="form-label">Mode of Payment</label>
                        <select name="mode_of_payment" id="mode_of_payment" class="form-select" required>
                            <option value="" {{ old('mode_of_payment') ? '' : 'selected' }}>Select Mode</option>
                            <option value="MPESA" {{ old('mode_of_payment') == 'MPESA' ? 'selected' : '' }}>MPESA</option>
                            <option value="Airtel Money" {{ old('mode_of_payment') == 'Airtel Money' ? 'selected' : '' }}>Airtel Money</option>
                            <option value="Bank" {{ old('mode_of_payment') == 'Bank' ? 'selected' : '' }}>Bank</option>
                            <option value="Cash" {{ old('mode_of_payment') == 'Cash' ? 'selected' : '' }}>Cash</option>
                        </select>
                    </div>

                    <div id="bankFields" style="display: none;">
                        <div class="mb-3">
                            <label for="bank_name" class="form-label">Bank Name</label>
                            <input type="text" name="bank_name" id="bank_name" value="{{ old('bank_name') }}" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="bank_account" class="form-label">Bank Account</label>
                            <input type="text" name="bank_account" id="bank_account" value="{{ old('bank_account') }}" class="form-control">
                        </div>
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
            const modeSelect = document.getElementById('mode_of_payment');
            const bankFields = document.getElementById('bankFields');
            const bankName = document.getElementById('bank_name');
            const bankAccount = document.getElementById('bank_account');

            function toggleBankFields() {
                if (modeSelect.value === 'Bank') {
                    bankFields.style.display = 'block';
                    bankName.required = true;
                    bankAccount.required = true;
                    bankName.disabled = false;
                    bankAccount.disabled = false;
                } else {
                    bankFields.style.display = 'none';
                    bankName.required = false;
                    bankAccount.required = false;
                    bankName.disabled = true;
                    bankAccount.disabled = true;
                    bankName.value = '';
                    bankAccount.value = '';
                }
            }

            // Run on page load (handles old values after validation errors)
            toggleBankFields();

            // Run on change
            modeSelect.addEventListener('change', toggleBankFields);
        });
    </script>
@endpush
