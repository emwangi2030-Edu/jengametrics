<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create New BOM') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('boms.store') }}">
                        @csrf

                        <div class="mb-4">
                            <label for="bom_name" class="block text-gray-700">{{ __('BOM Name') }}</label>
                            <input type="text" name="bom_name" id="bom_name" class="mt-1 block w-full" required>
                        </div>

                        <div class="mb-4">
                            <label for="bq_document_id" class="block text-gray-700">{{ __('Select BQ Document') }}</label>
                            <select name="bq_document_id" id="bq_document_id" class="mt-1 block w-full" required>
                                @foreach($bqDocuments as $document)
                                    <option value="{{ $document->id }}">{{ $document->title }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div id="items-container">
                            <div class="item-entry mb-4">
                                <label for="item_description" class="block text-gray-700">{{ __('Item Description') }}</label>
                                <input type="text" name="items[0][description]" class="mt-1 block w-full" required>

                                <label for="quantity" class="block text-gray-700">{{ __('Quantity') }}</label>
                                <input type="number" name="items[0][quantity]" class="mt-1 block w-full" required>

                                <label for="unit" class="block text-gray-700">{{ __('Unit') }}</label>
                                <input type="text" name="items[0][unit]" class="mt-1 block w-full" required>

                                <label for="rate" class="block text-gray-700">{{ __('Rate') }}</label>
                                <input type="number" name="items[0][rate]" class="mt-1 block w-full" step="0.01" required>

                                <label for="amount" class="block text-gray-700">{{ __('Amount') }}</label>
                                <input type="number" name="items[0][amount]" class="mt-1 block w-full" step="0.01" required>
                            </div>
                        </div>

                        <button type="button" id="add-item" class="bg-blue-500 text-white px-4 py-2 rounded-md">{{ __('Add Item') }}</button>
                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md mt-4">{{ __('Create BOM') }}</button>
                    </form>

                    <a href="{{ route('boms.index') }}" class="bg-blue-500 text-white px-4 py-2 rounded-md mt-4 inline-block">{{ __('Back to BOMs') }}</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('add-item').addEventListener('click', function() {
            const itemsContainer = document.getElementById('items-container');
            const itemIndex = itemsContainer.children.length;
            const newItem = `
                <div class="item-entry mb-4">
                    <label for="item_description" class="block text-gray-700">{{ __('Item Description') }}</label>
                    <input type="text" name="items[${itemIndex}][description]" class="mt-1 block w-full" required>

                    <label for="quantity" class="block text-gray-700">{{ __('Quantity') }}</label>
                    <input type="number" name="items[${itemIndex}][quantity]" class="mt-1 block w-full" required>

                    <label for="unit" class="block text-gray-700">{{ __('Unit') }}</label>
                    <input type="text" name="items[${itemIndex}][unit]" class="mt-1 block w-full" required>

                    <label for="rate" class="block text-gray-700">{{ __('Rate') }}</label>
                    <input type="number" name="items[${itemIndex}][rate]" class="mt-1 block w-full" step="0.01" required>

                    <label for="amount" class="block text-gray-700">{{ __('Amount') }}</label>
                    <input type="number" name="items[${itemIndex}][amount]" class="mt-1 block w-full" step="0.01" required>
                </div>
            `;
            itemsContainer.insertAdjacentHTML('beforeend', newItem);
        });
    </script>
</x-app-layout>
