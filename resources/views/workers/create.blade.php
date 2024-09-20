<!-- resources/views/workers/create.blade.php -->
@extends('layouts.appbar')

@section('content')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Add Worker') }}
    </h2>

    <div class="py-12">
        <div class="container mx-auto px-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <!-- Display validation errors -->
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('workers.store') }}">
                        @csrf
                        <input type="hidden" name="project_id" value="{{ $projectId }}">

                        <div class="form-group">
                            <label for="full_name">Full Name</label>
                            <input type="text" name="full_name" class="form-control" value="{{ old('full_name') }}" required>
                        </div>

                        <div class="form-group">
                            <label for="id_number">ID Number</label>
                            <input type="number" name="id_number" class="form-control" value="{{ old('id_number') }}" required>
                        </div>

                        <div class="form-group">
                            <label for="job_category">Job Category</label>
                            <select name="job_category" class="form-control" required>
                                <option value="">Select Job Category</option>
                                <option value="Mason" {{ old('job_category') == 'Mason' ? 'selected' : '' }}>Mason</option>
                                <option value="Site Manager" {{ old('job_category') == 'Site Manager' ? 'selected' : '' }} >Site Manager</option>
                                <option value="Quantity Surveyor" {{ old('job_category') == 'Quantity Surveyor' ? 'selected' : '' }}>Quantity Surveyor</option>
                                <option value="Carpenter" {{ old('job_category') == 'Carpenter' ? 'selected' : '' }}>Carpenter</option>
                                <option value="Plumber" {{ old('job_category') == 'Plumber' ? 'selected' : '' }}>Plumber</option>
                                <option value="Helper/Casual" {{ old('job_category') == 'Helper/Casual' ? 'selected' : '' }}>Helper/Casual</option>
                                <option value="Painter" {{ old('job_category') == 'Painter' ? 'selected' : '' }}>Painter</option>
                                <option value="Sub Contractor" {{ old('job_category') == 'Sub Contractor' ? 'selected' : '' }}>Sub Contractor</option>
                                <option value="Electrician" {{ old('job_category') == 'Electrician' ? 'selected' : '' }}>Electrician</option>
                                <option value="Supervisor" {{ old('job_category') == 'Supervisor' ? 'selected' : '' }}>Supervisor</option>
                                <option value="Assistant Supervisor" {{ old('job_category') == 'Assistant Supervisor' ? 'selected' : '' }}>Assistant Supervisor</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="work_type">Work Type</label>
                            <select name="work_type" class="form-control" required>
                                <option value="">Select Work Type</option>
                                <option value="Under Contract" {{ old('work_type') == 'Under Contract' ? 'selected' : '' }}>Under Contract</option>
                                <option value="Casual" {{ old('work_type') == 'Casual' ? 'selected' : '' }}>Casual</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="phone">Phone</label>
                            <input type="number" name="phone" class="form-control" value="{{ old('phone') }}">
                        </div>

                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email') }}">
                        </div>

                        <br>
                        <button type="submit" class="btn btn-primary">Save</button>

                        <!-- Back Button -->
                        <a href="{{ route('workers.index') }}" class="btn btn-secondary">Back</a>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection