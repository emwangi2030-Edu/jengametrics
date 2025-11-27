@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row mb-5 text-center">
        <div class="col-12">
            <h2 class="display-6 fw-bold" style="color:#027333;">{{ __('Add Item to :level', ['level' => $bqLevel->name]) }}</h2>
            <p class="text-muted">{{ __('Select a section, element, and item to include in this level, or add a manual item.') }}</p>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0 rounded">
                <div class="card-body p-5">
                    <form method="POST" action="{{ route('bq_levels.items.store', [$bqDocument, $bqLevel]) }}">
                        @csrf

                        <div class="form-check form-switch mb-4">
                            <input class="form-check-input" type="checkbox" id="manual_item" name="manual_item" value="1">
                            <label class="form-check-label fw-semibold" for="manual_item">{{ __('Add as manual item (not from library)') }}</label>
                        </div>

                        {{-- Section Dropdown --}}
                        <div class="form-floating mb-4">
                            <select name="section_id" id="section" class="form-select" required>
                                <option value="">{{ __('Choose Section') }}</option>
                                @foreach($sections as $section)
                                    <option value="{{ $section->id }}">{{ $section->name }}</option>
                                @endforeach
                            </select>
                            <label for="section">{{ __('Select Section') }}</label>
                        </div>

                        {{-- Element Dropdown --}}
                        <div class="form-floating mb-4 standard-only">
                            <select name="element_id" id="element" class="form-select" required>
                                <option value="">{{ __('Choose Element') }}</option>
                            </select>
                            <label for="element">{{ __('Select Element') }}</label>
                        </div>

                        {{-- Item Dropdown --}}
                        <div class="form-floating mb-4 standard-only">
                            <select name="item_id" id="item_id" class="form-select" required>
                                <option value="">{{ __('Choose Item') }}</option>
                            </select>
                            <label for="item_id">{{ __('Select Item') }}</label>
                        </div>

                        {{-- Manual item name --}}
                        <div class="form-floating mb-4 manual-only d-none">
                            <input type="text" name="manual_name" id="manual_name" class="form-control" placeholder="{{ __('Item name') }}">
                            <label for="manual_name">{{ __('Manual item name') }}</label>
                        </div>

                        {{-- Manual unit --}}
                        <div class="form-floating mb-4 manual-only d-none">
                            <select name="manual_unit" id="manual_unit" class="form-select">
                                <option disabled value="">{{ __('Choose Unit') }}</option>
                                @foreach($units ?? [] as $unit)
                                    <option value="{{ $unit->abbrev }}">{{ $unit->abbrev }}</option>
                                @endforeach
                            </select>
                            <label for="manual_unit">{{ __('Unit of measure') }}</label>
                        </div>

                        {{-- Rate Input --}}
                        <div class="form-floating mb-4">
                            <input type="number" name="rate" id="rate" class="form-control" step="0.01" placeholder="Enter rate" required>
                            <label for="rate">{{ __('Rate') }}</label>
                        </div>

                        {{-- Quantity Input --}}
                        <div class="form-floating mb-4">
                            <input type="number" name="quantity" id="quantity" class="form-control" placeholder="Enter quantity" required>
                            <label for="quantity">{{ __('Quantity') }}</label>
                        </div>

                        {{-- Amount (Readonly) --}}
                        <div class="form-floating mb-4">
                            <input type="number" name="amount" id="amount" class="form-control" step="0.01" placeholder="Enter amount" readonly>
                            <label for="amount">{{ __('Amount') }}</label>
                        </div>

                        <div class="d-flex justify-content-center">
                            <button type="submit" class="btn w-50 py-2 text-white" style="background-color:#027333;">{{ __('Save to BQ') }}</button>
                        </div>
                    </form>
                    <br>
                    <div class="d-flex justify-content-center">
                        <a href="{{ route('bq_levels.show', [$bqDocument, $bqLevel]) }}" class="btn btn-dark">{{ __('Back') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script>
    $(document).ready(function () {
        console.log('Page loaded, jQuery is ready');

        // CSRF token for AJAX
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function toggleManualMode() {
            const manual = $('#manual_item').is(':checked');
            $('.standard-only').toggleClass('d-none', manual);
            $('.manual-only').toggleClass('d-none', !manual);

            $('#element, #item_id').prop('required', !manual);
            $('#manual_name').prop('required', manual);
            $('#manual_unit').prop('required', manual);

            if (manual) {
                $('#element').val('');
                $('#item_id').val('');
            } else {
                $('#manual_name').val('');
                $('#manual_unit').val('');
            }
        }

        $('#manual_item').on('change', toggleManualMode);
        toggleManualMode();

        // Function to display error message
        function showError(message) {
            let alertDiv = `<div class="alert alert-danger alert-dismissible fade show" role="alert">
                                ${message}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>`;
            $('.card-body').prepend(alertDiv);
        }

        // Load elements based on selected section
        $('#section').on('change', function () {
            let sectionId = $(this).val();
            $('#element, #item_id').html('<option value="">{{ __("Choose") }}</option>');

            if (sectionId) {
                $.ajax({
                    url: '{{ route('get.elements') }}',
                    type: 'GET',
                    data: { section_id: sectionId },
                    beforeSend: function() {
                        $('#element').html('<option>Loading...</option>');
                    },
                    success: function (data) {
                        $('#element').html('<option value="">{{ __("Choose Element") }}</option>');
                        $.each(data, function (key, value) {
                            $('#element').append('<option value="' + key + '">' + value + '</option>');
                        });
                    },
                    error: function() {
                        showError('Failed to load elements. Please try again.');
                    }
                });
            }
        });


        // Load items based on selected element
        $('#element').on('change', function () {
            let elementId = $(this).val();
            $('#item_id').html('<option value="">{{ __("Choose Item") }}</option>');

            if (elementId) {
                $.ajax({
                    url: '{{ route('get.items') }}',
                    type: 'GET',
                    data: { element_id: elementId },
                    beforeSend: function() {
                        $('#item_id').html('<option>Loading...</option>');
                    },
                    success: function (data) {
                        $('#item_id').html('<option value="">{{ __("Choose Item") }}</option>');
                        $.each(data, function (key, value) {
                            $('#item_id').append('<option value="' + key + '">' + value + '</option>');
                        });
                    },
                    error: function() {
                        showError('Failed to load items. Please try again.');
                    }
                });
            }
        });

        // Calculate amount
        $('#quantity, #rate').on('input', function () {
            let quantity = parseFloat($('#quantity').val()) || 0;
            let rate = parseFloat($('#rate').val()) || 0;
            $('#amount').val((quantity * rate).toFixed(2));
        });
    });
</script>
@endpush
@endsection
