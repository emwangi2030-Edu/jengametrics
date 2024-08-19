@extends('layouts.appbar')
@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h1 class="text-center mb-4">Step 1: Basic Information</h1>
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('wizard.step1.post') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="school_name">Project Name:</label>
                            <input type="text" id="name" name="name" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="description">Description:</label>
                            <input type="text" id="description" name="description" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Next</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
