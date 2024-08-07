<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create New Bill of Quantities') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('bq_documents.store') }}">
                        @csrf
                        <div class="mb-4">
                            <label for="name" class="block text-gray-700">{{ __('Name') }}</label>
                            <input type="text" name="name" id="name" class="mt-1 block w-full" required>
                        </div>

                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md">{{ __('Create') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
