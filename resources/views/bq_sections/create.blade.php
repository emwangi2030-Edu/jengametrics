@extends('layouts.appbar')

@section('content')
<div class="container py-5">
    <div class="row mb-5 text-center">
        <div class="col-12">
            <h2 class="display-6 text-primary fw-bold">{{ __('Create New BQ Document') }}</h2>
            <p class="text-muted">Fill in the details to create a comprehensive Bill of Quantities document.</p>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0 rounded">
                <div class="card-body p-5">
                    <form method="POST" action="{{ route('bq_sections.store', $bqDocument) }}">
                        @csrf

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
                        <div class="form-floating mb-4">
                            <select name="element_id" id="element" class="form-select" required>
                                <option value="">{{ __('Choose Element') }}</option>
                            </select>
                            <label for="element">{{ __('Select Element') }}</label>
                        </div>

                        <!-- {{-- Sub-element Dropdown --}}
                        <div class="form-floating mb-4">
                            <select name="sub_element_id" id="sub_element" class="form-select" required>
                                <option value="">{{ __('Choose Sub-element') }}</option>
                            </select>
                            <label for="sub_element">{{ __('Select Sub-element') }}</label>
                        </div> -->

                        {{-- Item Dropdown --}}
                        <div class="form-floating mb-4">
                            <select name="item_id" id="item_id" class="form-select" required>
                                <option value="">{{ __('Choose Item') }}</option>
                            </select>
                            <label for="item_id">{{ __('Select Item') }}</label>
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

                        <button type="submit" class="btn btn-primary w-100 py-2">{{ __('Save BQ') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        console.log('Page loaded, jQuery is ready');

        // CSRF token for AJAX
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

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
@endsection
