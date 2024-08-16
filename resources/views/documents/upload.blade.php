@extends('layouts.appbar')
@section('content') 
    <div class="container mt-5">
        <h1 class="mb-4">Upload Document</h1>
<<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Upload Document') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endifgitt

                    <form action="{{ route('documents.store') }}" method="POST" enctype="multipart/form-data" class="mt-4">
                        @csrf
                        <div class="mb-4">
                            <label for="file" class="block text-sm font-medium text-gray-700">Choose a document to upload:</label>
                            <input type="file" name="file" id="file" required class="mt-1 block w-full">
                        </div>
                        <div>
                            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 focus:outline-none focus:ring focus:ring-blue-300">Upload</button>
                        </div>
                    </form>

                    <div class="mt-6">
                        <h3 class="text-lg font-medium text-gray-900">Uploaded Documents</h3>
                        @if($documents->isEmpty())
                            <p class="mt-2 text-gray-500">No documents uploaded yet.</p>
                        @else
                            <table class="mt-4 w-full">
                                <thead>
                                    <tr>
                                        <th class="px-4 py-2">Document Name</th>
                                        <th class="px-4 py-2">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($documents as $document)
                                        <tr>
                                            <td class="px-4 py-2">{{ $document->name }}</td>
                                            <td class="px-4 py-2">
                                                <a href="{{ route('documents.upload', $document->id) }}" class="bg-green-500 text-white px-2 py-1 rounded-md">View</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Include Bootstrap JS (Optional) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

@endsection
</x-app-layout>
