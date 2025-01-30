@extends('layouts.appbar')

@section('content')
<div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="col-md-6">
        <div class="card shadow-lg">
            <div class="card-body">
                <h2 class="text-center mb-4">Step 2: Project Information</h2>

                <form action="{{ route('wizard.step2.post') }}" method="POST">
                    @csrf

                    <!-- Project Budget -->
                    <div class="mb-3">
                        <label for="budget" class="form-label">Project Budget:</label>
                        <input type="text" id="budget" name="budget" class="form-control" required placeholder="Enter project budget">
                    </div>

                    <!-- Project Address -->
                    <div class="mb-3">
                        <label for="address" class="form-label">Project Address:</label>
                        <input type="text" id="address" name="address" class="form-control" required placeholder="Enter project address">
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
