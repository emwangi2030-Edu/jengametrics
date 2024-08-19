@extends('layouts.appbar')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h1 class="text-center mb-4">Step 3: Confirm and Submit</h1>
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('wizard.complete') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="name">Project Name:</label>
                            <input type="text" id="name" name="name" class="form-control" value="{{ session('name') }}" readonly>
                        </div>

                        <div class="form-group">
                            <label for="description">Project Description:</label>
                            <input type="text" id="description" name="description" class="form-control" value="{{ session('description') }}" readonly>
                        </div>


                        <div class="form-group">
                            <label for="address">Project Address:</label>
                            <input type="text" id="address" name="address" class="form-control" value="{{ session('address') }}" readonly>
                        </div>


                        <div class="form-group">
                            <label for="budget">Budget:</label>
                            <input type="text" id="budget" name="budget" class="form-control" value="{{ session('budget') }}" readonly>
                        </div>
                        <button type="submit" class="btn btn-success">Complete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
