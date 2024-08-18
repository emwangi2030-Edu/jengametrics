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
                    <form method="POST" action="{{ route('workers.store') }}">
                        @csrf

                        <div class="form-group">
                            <label for="full_name">Full Name</label>
                            <input type="text" name="full_name" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="id_number">ID Number</label>
                            <input type="number" name="id_number" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="job_category">Job Category</label>
                            <select name="job_category" class="form-control" required>
                                <option value="Mason">Mason</option>
                                <option value="Site Manager">Site Manager</option>
                                <option value="Quantity Surveyor">Quantity Surveyor</option>
                                <option value="Carpenter">Carpenter</option>
                                <option value="Plumber">Plumber</option>
                                <option value="Helper/Casual">Helper/Casual</option>
                                <option value="Painter">Painter</option>
                                <option value="Sub Contractor">Sub Contractor</option>
                                <option value="Electrician">Electrician</option>
                                <option value="Supervisor">Supervisor</option>
                                <option value="Assistant Supervisor">Assistant Supervisor</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="work_type">Work Type</label>
                            <select name="work_type" class="form-control" required>
                                <option value="Under Contract">Under Contract</option>
                                <option value="Casual">Casual</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="phone">Phone</label>
                            <input type="number" name="phone" class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" name="email" class="form-control">
                        </div>

                        <button type="submit" class="btn btn-primary">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
@endsection
