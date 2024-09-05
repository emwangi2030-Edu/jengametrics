<!-- resources/views/workers/index.blade.php -->
@extends('layouts.appbar')
@section('content')

<div class="content">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Manage Labour') }}
    </h2>

    <div class="py-12">
        <div class="container mx-auto px-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <a href="{{ route('workers.create') }}" class="btn btn-primary mb-4">Add Worker</a>

                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead class="thead-dark">
                                <tr>
                                    <th scope="col">Full Name</th>
                                    <th scope="col">ID Number</th>
                                    <th scope="col">Job Category</th>
                                    <th scope="col">Work Type</th>
                                    <th scope="col">Phone</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Details</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($workers as $worker)
                                    <tr>
                                        <td>{{ $worker->full_name }}</td>
                                        <td>{{ $worker->id_number }}</td>
                                        <td>{{ $worker->job_category }}</td>
                                        <td>{{ $worker->work_type }}</td>
                                        <td>{{ $worker->phone }}</td>
                                        <td>{{ $worker->email }}</td>
                                        <td>
                                            <a href="{{ route('workers.show', $worker->id) }}" class="btn btn-link text-primary p-0">></a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    </div>


@endsection
