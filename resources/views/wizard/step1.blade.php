@extends('layouts.app')

@section('content')
<div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="col-md-8 col-lg-6">
        <div class="card shadow-lg">
            <div class="card-body">
                <div class="d-flex justify-content-end">
                    <a href="{{ route('dashboard') }}" class="btn btn-outline-danger btn-sm">X</a>
                </div>
                <h2 class="text-center mb-4">Project Details</h2>

                <form action="{{ route('wizard.step1.post') }}" method="POST">
                    @csrf

                    <!-- Project Name -->
                    <div class="mb-3">
                        <label for="name" class="form-label">Project Name:</label>
                        <input type="text" id="name" name="name" class="form-control" required placeholder="Enter project name" value="{{ old('name', session('name')) }}">
                    </div>

                    <!-- Description -->
                    <div class="mb-3">
                        <label for="description" class="form-label">Description:</label>
                        <textarea id="description" name="description" class="form-control" rows="3" required placeholder="Provide a brief project description">{{ old('description', session('description')) }}</textarea>
                    </div>

                    <!-- Project Budget -->
                    <div class="mb-3">
                        <label for="budget" class="form-label">Project Budget:</label>
                        <input type="text" id="budget" name="budget" class="form-control" required placeholder="Enter project budget" value="{{ old('budget', session('budget')) }}">
                    </div>

                    <!-- Project Address -->
                    <div class="mb-4">
                        <label for="address" class="form-label">Project Address:</label>
                        <input type="text" id="address" name="address" class="form-control" required placeholder="Enter project address" value="{{ old('address', session('address')) }}">
                    </div>

                    <!-- Submit Button -->
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-success">Next</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
