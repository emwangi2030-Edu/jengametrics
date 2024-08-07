<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Bill of Quantities: ') . $bqDocument->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <p>{{ __('Document Details') }}</p>
                    <div class="mt-4">
                        <p><strong>{{ __('Name:') }}</strong> {{ $bqDocument->name }}</p>
                        <p><strong>{{ __('Created At:') }}</strong> {{ $bqDocument->created_at }}</p>
                        <!-- Add more details as needed -->

                        <a href="{{ route('bq_documents.index') }}" class="bg-blue-500 text-white px-4 py-2 rounded-md mt-4 inline-block">{{ __('Back to List') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
