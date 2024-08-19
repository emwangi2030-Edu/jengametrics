<!-- resources/views/workers/show.blade.php -->

@extends('layouts.appbar')
@section('content')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Worker Details') }}
    </h2>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3>{{ $worker->full_name }}</h3>
                    <p><strong>ID Number:</strong> {{ $worker->id_number }}</p>
                    <p><strong>Job Category:</strong> {{ $worker->job_category }}</p>
                    <p><strong>Work Type:</strong> {{ $worker->work_type }}</p>
                    <p><strong>Phone:</strong> {{ $worker->phone }}</p>
                    <p><strong>Email:</strong> {{ $worker->email }}</p>
                    <div class="mt-4">
                    <a href="{{ route('workers.index') }}" class="btn btn-primary mb-4">Back</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
