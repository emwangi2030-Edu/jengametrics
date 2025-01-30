@extends('layouts.appbar')

@section('content')
<div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="col-md-6">
        <div class="card shadow-lg">
            <div class="card-body">
                <h2 class="text-center mb-4">Step 1: Basic Information</h2>

                <form action="{{ route('wizard.step1.post') }}" method="POST">
                    @csrf

                    <!-- Project Name -->
                    <div class="mb-3">
                        <label for="name" class="form-label">Project Name:</label>
                        <input type="text" id="name" name="name" class="form-control" required placeholder="Enter project name">
                    </div>

                    <!-- Description -->
                    <div class="mb-3">
                        <label for="description" class="form-label">Description:</label>
                        <textarea id="description" name="description" class="form-control" rows="3" required placeholder="Provide a brief project description"></textarea>
                    </div>

                    <!-- Submit Button -->
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">Next →</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
