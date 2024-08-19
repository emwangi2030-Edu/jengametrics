@extends('layouts.appbar')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h2 class="text-center mb-4">Step 2: Project Information</h2>
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('wizard.step2.post') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="budget">Project Budget :</label>
                            <input type="text" id="budget" name="budget" class="form-control" required>
                        </div>


                        <div class="form-group">
                            <label for="school_address">Project Address:</label>
                            <input type="text" id="address" name="address" class="form-control" required>
                        </div>


                        <button type="submit" class="btn btn-primary">Next</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
