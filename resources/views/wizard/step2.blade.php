@extends('layouts.app')

@section('content')
<div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="col-md-8 col-lg-6">
        <div class="card shadow-lg">
            <div class="card-body">
                <div class="d-flex justify-content-end">
                    <a href="{{ route('dashboard') }}" class="btn btn-outline-danger btn-sm">X</a>
                </div>
                <h2 class="text-center mb-4">Step 2: Confirm and Submit</h2>

                <form action="{{ route('wizard.complete') }}" method="POST">
                    @csrf

                    <!-- Project Name -->
                    <div class="mb-3">
                        <label for="name" class="form-label">Project Name:</label>
                        <input type="text" id="name" name="name" class="form-control-plaintext" value="{{ session('name') }}" readonly>
                    </div>

                    <!-- Project Description -->
                    <div class="mb-3">
                        <label for="description" class="form-label">Project Description:</label>
                        <input type="text" id="description" name="description" class="form-control-plaintext" value="{{ session('description') }}" readonly>
                    </div>

                    <!-- Project Address -->
                    <div class="mb-3">
                        <label for="address" class="form-label">Project Address:</label>
                        <input type="text" id="address" name="address" class="form-control-plaintext" value="{{ session('address') }}" readonly>
                    </div>

                    <!-- Budget -->
                    <div class="mb-3">
                        <label for="budget" class="form-label">Budget:</label>
                        <input type="text" id="budget" name="budget" class="form-control-plaintext" value="{{ session('budget') }}" readonly>
                    </div>

                    <!-- Submit Button -->
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('wizard.step1') }}" class="btn btn-warning">
                            Edit
                        </a>
                        <button type="submit" class="btn btn-success">Complete</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
