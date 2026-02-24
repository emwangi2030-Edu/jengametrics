@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
                    <div>
                        <h2 class="fw-bold text-primary mb-1">
                            {{ __('Copy BoQ') }}
                        </h2>
                        <p class="text-muted mb-0">
                            {{ __('Copy items from ":title" into a new BoQ.', ['title' => $sourceDocument->title]) }}
                        </p>
                    </div>
                    <a href="{{ route('bq_documents.index') }}" class="btn btn-outline-secondary mt-3 mt-md-0" aria-label="Back" title="Back"><span data-feather="arrow-left-circle"></span></a>
                </div>

                @include('flash_msg')

                <form method="POST" action="{{ route('bq_documents.copy.store', $sourceDocument) }}">
                    @csrf
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-body">
                            <h5 class="fw-bold mb-3">{{ __('New BoQ Details') }}</h5>

                            <div class="mb-3">
                                <label for="title" class="form-label">{{ __('Title') }}</label>
                                <input
                                    type="text"
                                    name="title"
                                    id="title"
                                    class="form-control @error('title') is-invalid @enderror"
                                    value="{{ old('title', $suggestedTitle) }}"
                                    required
                                >
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">{{ __('Description') }}</label>
                                <textarea
                                    name="description"
                                    id="description"
                                    rows="3"
                                    class="form-control @error('description') is-invalid @enderror"
                                >{{ old('description', $sourceDocument->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-body">
                            <div class="d-flex justify-content-between flex-column flex-md-row align-items-md-center mb-3">
                            <h5 class="fw-bold mb-2 mb-md-0">{{ __('Select Items to Copy') }}</h5>
                                <div class="text-muted">{{ __('Uncheck any items you do not want to include.') }}</div>
                            </div>

                            @php
                                $selectedItems = collect(old('items', $groupedSections->flatMap(fn ($group) => $group['items']->pluck('id'))->all()))
                                    ->map(fn ($id) => (int) $id);
                            @endphp

                            @error('items')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror

                            @forelse($groupedSections as $group)
                                <div class="mb-4">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h6 class="fw-bold mb-0">
                                            {{ optional($group['section'])->name ?? __('Unassigned Section') }}
                                        </h6>
                                        <span class="badge bg-light text-muted">
                                            {{ __('Total Items: :count', ['count' => $group['items']->count()]) }}
                                        </span>
                                    </div>

                                    <div class="table-responsive">
                                        <table class="table table-sm align-middle">
                                            <thead class="table-light">
                                                <tr>
                                                    <th style="width: 40px;">
                                                        <input type="checkbox" class="form-check-input"
                                                            data-group-toggle
                                                            aria-label="{{ __('Select entire section') }}">
                                                    </th>
                                                    <th>{{ __('Item') }}</th>
                                                    <th class="text-end">{{ __('Quantity') }}</th>
                                                    <th class="text-end">{{ __('Rate (KES)') }}</th>
                                                    <th class="text-end">{{ __('Amount (KES)') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($group['items'] as $item)
                                                    <tr>
                                                        <td>
                                                            <input
                                                                type="checkbox"
                                                                name="items[]"
                                                                value="{{ $item->id }}"
                                                                class="form-check-input"
                                                                {{ $selectedItems->contains($item->id) ? 'checked' : '' }}
                                                            >
                                                        </td>
                                                        <td>{{ $item->item_name ?? optional($item->item)->name ?? __('Unnamed Item') }}</td>
                                                        <td class="text-end">{{ number_format((float) ($item->quantity ?? 0), 2) }}</td>
                                                        <td class="text-end">{{ number_format((float) ($item->rate ?? 0), 2) }}</td>
                                                        <td class="text-end">{{ number_format((float) ($item->amount ?? 0), 2) }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @empty
                                <p class="text-muted mb-0">{{ __('No items available to copy from this BoQ.') }}</p>
                            @endforelse
                        </div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-success">
                            {{ __('Create BoQ') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const updateToggleState = function (toggle) {
                    const table = toggle.closest('table');
                    if (!table) {
                        return;
                    }

                    const checkboxes = Array.from(table.querySelectorAll('tbody input[type="checkbox"]'));
                    if (checkboxes.length === 0) {
                        toggle.checked = false;
                        toggle.indeterminate = false;
                        toggle.disabled = true;
                        return;
                    }

                    const checkedCount = checkboxes.filter((checkbox) => checkbox.checked).length;
                    toggle.checked = checkedCount === checkboxes.length;
                    toggle.indeterminate = checkedCount > 0 && checkedCount < checkboxes.length;
                };

                document.querySelectorAll('[data-group-toggle]').forEach(function (toggle) {
                    updateToggleState(toggle);

                    toggle.addEventListener('change', function () {
                        const table = this.closest('table');
                        if (!table) {
                            return;
                        }

                        const checkboxes = table.querySelectorAll('tbody input[type="checkbox"]');
                        checkboxes.forEach(function (checkbox) {
                            checkbox.checked = toggle.checked;
                        });

                        toggle.indeterminate = false;
                    });
                });

                document.querySelectorAll('tbody input[type="checkbox"]').forEach(function (checkbox) {
                    checkbox.addEventListener('change', function () {
                        const table = this.closest('table');
                        if (!table) {
                            return;
                        }

                        const toggle = table.querySelector('[data-group-toggle]');
                        if (toggle) {
                            updateToggleState(toggle);
                        }
                    });
                });
            });
        </script>
    @endpush
@endsection
