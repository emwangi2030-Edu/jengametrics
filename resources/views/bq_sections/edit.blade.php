<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Section: ') . $bqSection->section_name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('bq_sections.update', [$bqDocument, $bqSection]) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label for="section_name" class="block text-gray-700">{{ __('Section Name') }}</label>
                            <input type="text" name="section_name" id="section_name" class="mt-1 block w-full" value="{{ $bqSection->section_name }}" required>
                        </div>

                        <div class="mb-4">
                            <label for="details" class="block text-gray-700">{{ __('Details') }}</label>
                            <textarea name="details" id="details" class="mt-1 block w-full">{{ $bqSection->details }}</textarea>
                        </div>


                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md">{{ __('Update Section') }}</button>
                    </form>

                    <a href="{{ route('bq_documents.show', $bqDocument) }}" class="bg-blue-500 text-white px-4 py-2 rounded-md mt-4 inline-block" aria-label="Back" title="Back"><span data-feather="arrow-left-circle"></span></a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
