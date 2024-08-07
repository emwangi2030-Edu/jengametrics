


<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Section: ') . $bqSection->section_name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Display Section Details -->
                    <p><strong>{{ __('Section Name:') }}</strong> {{ $bqSection->section_name }}</p>
                    <p><strong>{{ __('Details:') }}</strong> {{ $bqSection->details }}</p>
                    <p><strong>{{ __('Unit:') }}</strong> {{ $bqSection->unit }}</p>
                    <p><strong>{{ __('Quantity:') }}</strong> {{ $bqSection->quantity }}</p>

                    <!-- Link to Edit Section -->
                    <a href="{{ route('bq_sections.edit', [$bqDocument, $bqSection]) }}" class="bg-blue-500 text-white px-4 py-2 rounded-md mt-4 inline-block">
                        {{ __('Edit Section') }}
                    </a>

          <!-- Link to Add New Item -->
          <a href="{{ route('create_bq_item', ['bqSection'=> $bqSection]) }}" class="bg-green-500 text-white px-4 py-2 rounded-md mt-4 inline-block">
                        {{ __('Add New Item') }}
                    </a>

                    <!-- Table to display items -->
                    <h3 class="text-lg font-medium mt-6">{{ __('Items List') }}</h3>
                    <table class="min-w-full divide-y divide-gray-200 mt-4">
                        <thead>
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('Description') }}
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('Quantity') }}
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('Unit') }}
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('Rate') }}
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('Amount') }}
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('Actions') }}
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($items as $item)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $item->item_description }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $item->quantity }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $item->unit }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ number_format($item->rate, 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ number_format($item->amount, 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <!-- Add Edit and Delete Links for Items Here if needed -->
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <!-- Link Back to Document -->
                    <a href="{{ route('bq_documents.show', $bqDocument) }}" class="bg-gray-500 text-white px-4 py-2 rounded-md mt-4 inline-block">
                        {{ __('Back to Document') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

