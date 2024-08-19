<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add New BoQ Item for: ') . $bqSection->section_name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('save_bq_item', $bqSection) }}" id="bqForm">
                        @csrf
                        <input type="hidden" name="bq_section_id" value="{{ $bqSection->id }}">

                        <div class="mb-4">
                            <label for="item_description" class="block text-gray-700">{{ __('Item Description') }}</label>
                            <input type="text" name="item_description" id="item_description" class="mt-1 block w-full" placeholder="Enter item description" required>
                        </div>

                        <div class="mb-4">
                            <label for="quantity" class="block text-gray-700">{{ __('Quantity') }}</label>
                            <input type="number" name="quantity" id="quantity" class="mt-1 block w-full" placeholder="Enter quantity" required>
                        </div>

                        <div class="mb-4">
                            <label for="unit" class="block text-gray-700">{{ __('Unit') }}</label>
                            <select name="unit" id="unit" class="mt-1 block w-full" required>
                                <option value="" disabled selected>{{ __('Select unit') }}</option>
                                @foreach ($units as $unit)
                                    <option value="{{ $unit }}">{{ ucfirst($unit) }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="rate" class="block text-gray-700">{{ __('Rate') }}</label>
                            <input type="number" name="rate" id="rate" class="mt-1 block w-full" step="0.01" placeholder="Enter rate" required>
                        </div>

                        <div class="mb-4">
                            <label for="amount" class="block text-gray-700">{{ __('Amount') }}</label>
                            <input type="number" name="amount" id="amount" class="mt-1 block w-full" step="0.01" placeholder="Enter amount" readonly>
                        </div>

                        <button type="submit" class="">{{ __('Add Item') }}</button>
                    </form>

                    <a href="#" class="">{{ __('Back to Section') }}</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const quantityInput = document.getElementById('quantity');
            const rateInput = document.getElementById('rate');
            const amountInput = document.getElementById('amount');

            function calculateAmount() {
                const quantity = parseFloat(quantityInput.value) || 0;
                const rate = parseFloat(rateInput.value) || 0;
                const amount = quantity * rate;
                amountInput.value = amount.toFixed(2); // Display amount with 2 decimal places
            }

            quantityInput.addEventListener('input', calculateAmount);
            rateInput.addEventListener('input', calculateAmount);
        });
    </script>
</x-app-layout>
