<!-- resources/views/workers/index.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Workers') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <a href="{{ route('workers.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded-md">Add Worker</a>

                    <table class="mt-4 w-full">
                        <thead>
                            <tr>
                                <th class="px-4 py-2">Full Name</th>
                                <th class="px-4 py-2">ID Number</th>
                                <th class="px-4 py-2">Job Category</th>
                                <th class="px-4 py-2">Work Type</th>
                                <th class="px-4 py-2">Phone</th>
                                <th class="px-4 py-2">Email</th>
                                <th class="px-4 py-2">Details</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($workers as $worker)
                                <tr>
                                    <td class="px-4 py-2">{{ $worker->full_name }}</td>
                                    <td class="px-4 py-2">{{ $worker->id_number }}</td>
                                    <td class="px-4 py-2">{{ $worker->job_category }}</td>
                                    <td class="px-4 py-2">{{ $worker->work_type }}</td>
                                    <td class="px-4 py-2">{{ $worker->phone }}</td>
                                    <td class="px-4 py-2">{{ $worker->email }}</td>
                                    <td class="px-4 py-2">
                                        <a href="{{ route('workers.show', $worker->id) }}" class="text-blue-500">></a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
