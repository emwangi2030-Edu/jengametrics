@extends('layouts.appbar')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4 text-success">Add Worker</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

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

                <div class="d-flex justify-content-between">
                    <a href="{{ route('workers.index') }}" class="btn btn-secondary">Back</a>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
