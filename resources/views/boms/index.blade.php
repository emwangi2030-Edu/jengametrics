<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('BOMs') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <a href="{{ route('boms.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded-md">Create New BOM</a>

                    <table class="mt-4 w-full">
                        <thead>
                            <tr>
                                <th class="px-4 py-2">BOM Name</th>
                                <th class="px-4 py-2">BQ Document</th>
                                <th class="px-4 py-2">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($boms as $bom)
                                <tr>
                                    <td class="px-4 py-2">{{ $bom->bom_name }}</td>
                                    <td class="px-4 py-2">{{ $bom->bqDocument->title }}</td>
                                    <td class="px-4 py-2">
                                        <a href="{{ route('boms.show', $bom->id) }}" class="bg-green-500 text-white px-2 py-1 rounded-md">View</a>
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
