<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Bill of Quantities: ') . $bqDocument->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <p><strong>{{ __('Document Details') }}</strong></p>
                    <div class="mt-4">
                        <p><strong>{{ __('Name:') }}</strong> {{ $bqDocument->title }}</p>
                        <p><strong>{{ __('Created At:') }}</strong> {{ $bqDocument->created_at->format('M d, Y') }}</p>
                        <!-- Add more details as needed -->

                        <!-- Link to create a new section -->
                        <a href="{{ route('bq_sections.create', $bqDocument) }}" class="bg-blue-500 text-white px-4 py-2 rounded-md mt-4 inline-block">
                            {{ __('Add New Section') }}
                        </a>

                        <!-- Display the list of sections -->
                        <div class="mt-6">
                            <h3 class="text-lg font-medium text-gray-800">{{ __('Sections') }}</h3>
                            @if($bqDocument->sections->isEmpty())
                                <p>{{ __('No sections found.') }}</p>
                            @else
                                <ul class="mt-4">
                                    @foreach($bqDocument->sections as $section)
                                        <li class="mb-2">
                                            <p><strong>{{ __('Section Name:') }}</strong> {{ $section->section_name }}</p>
                                            <p><strong>{{ __('Details:') }}</strong> {{ $section->details }}</p>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>

                        <a href="{{ route('bq_documents.index') }}" class="bg-blue-500 text-white px-4 py-2 rounded-md mt-4 inline-block">{{ __('Back to List') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
