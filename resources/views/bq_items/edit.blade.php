<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit BoQ Item: ') . $bqItem->item_description }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('bq_items.update', [$bqDocument, $bqItem]) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label for="bq_section_id" class="block text-gray-700">{{ __('Section') }}</label>
                            <select name="bq_section_id" id="bq_section_id" class="mt-1 block w-full" required>
                                @foreach($sections as $section)
                                    <option value="{{ $section->id }}" {{ $bqItem->bq_section_id == $section->id ? 'selected' : '' }}>
                                        {{ $section->section_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="item_description" class="block text-gray-700">{{ __('Item Description') }}</label>
                            <input type="text" name="item_description" id="item_description" class="mt-1 block w-full" value="{{ $bqItem->item_description }}" required>
                        </div>

                        <div class="mb-4">
                            <label for="quantity" class="block text-gray-700">{{ __('Quantity') }}</label>
                            <input type="number" name="quantity" id="quantity" class="mt-1 block w-full" value="{{ $bqItem->quantity }}" required>
                        </div>

                        <div class="mb-4">
                            <label for="unit" class="block text-gray-700">{{ __('Unit') }}</label>
                            <input type="text" name="unit" id="unit" class="mt-1 block w-full" value="{{ $bqItem->unit }}" required>
                        </div>

                        <div class="mb-4">
                            <label for="rate" class="block text-gray-700">{{ __('Rate') }}</label>
                            <input type="number" name="rate" id="rate" class="mt-1 block w-full" step="0.01" value="{{ $bqItem->rate }}" required>
                        </div>

                        <div class="mb-4">
                            <label for="amount" class="block text-gray-700">{{ __('Amount') }}</label>
                            <input type="number" name="amount" id="amount" class="mt-1 block w-full" step="0.01" value="{{ $bqItem->amount }}" required>
                        </div>

                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md">{{ __('Update Item') }}</button>
                    </form>

                    <a href="{{ route('bq_documents.show', $bqDocument) }}" class="bg-blue-500 text-white px-4 py-2 rounded-md mt-4 inline-block">{{ __('Back to Document') }}</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
