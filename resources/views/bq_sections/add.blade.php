@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="row mb-4">
            <div class="col-12">
                <h2 class="font-weight-bold text-dark">{{ __('Create New BQ Document') }}</h2>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <form method="POST" action="{{ route('bq_sections.store', $bqDocument) }}">
                            @csrf

                            {{-- Section Dropdown --}}
                            <div class="mb-3">
                                <label for="section" class="form-label">{{ __('Select Section') }}</label>
                                <select name="section_id" id="section" class="form-control" required>
                                    <option value="">{{ __('Choose Section') }}</option>
                                    @foreach($sections as $section)
                                        <option value="{{ $section->id }}">{{ $section->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Element Dropdown --}}
                            <div class="mb-3">
                                <label for="element" class="form-label">{{ __('Select Element') }}</label>
                                <select name="element_id" id="element" class="form-control" required>
                                    <option value="">{{ __('Choose Element') }}</option>
                                    {{-- Options will be loaded dynamically via JS --}}
                                </select>
                            </div>

                            {{-- Sub-element Dropdown --}}
                            <div class="mb-3">
                                <label for="sub_element" class="form-label">{{ __('Select Sub-element') }}</label>
                                <select name="sub_element_id" id="sub_element" class="form-control" required>
                                    <option value="">{{ __('Choose Sub-element') }}</option>
                                    {{-- Options will be loaded dynamically via JS --}}
                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary">{{ __('Save BQ') }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            console.log('Page loaded, jQuery is ready');

            // Load elements based on selected section
            $('#section').on('change', function () {
                var sectionId = $(this).val();
                console.log('Section selected:', sectionId);

                if (sectionId) {
                    $.ajax({
                        url: '{{ route('get.elements') }}',
                        type: 'GET',
                        data: { section_id: sectionId },
                        beforeSend: function() {
                            console.log('Fetching elements for section:', sectionId);
                        },
                        success: function (data) {
                            console.log('Elements received:', data);
                            $('#element').html('<option value="">{{ __("Choose Element") }}</option>');
                            $.each(data, function (key, value) {
                                $('#element').append('<option value="' + key + '">' + value + '</option>');
                            });
                        },
                        error: function(xhr, status, error) {
                            console.error('Error fetching elements:', error);
                            console.log('Response details:', xhr.responseText);
                        }
                    });
                } else {
                    $('#element').html('<option value="">{{ __("Choose Element") }}</option>');
                    $('#sub_element').html('<option value="">{{ __("Choose Sub-element") }}</option>');
                }
            });

            // Load sub-elements based on selected element
            $('#element').on('change', function () {
                var elementId = $(this).val();
                console.log('Element selected:', elementId);

                if (elementId) {
                    $.ajax({
                        url: '{{ route('get.sub_elements') }}',
                        type: 'GET',
                        data: { element_id: elementId },
                        beforeSend: function() {
                            console.log('Fetching sub-elements for element:', elementId);
                        },
                        success: function (data) {
                            console.log('Sub-elements received:', data);
                            $('#sub_element').html('<option value="">{{ __("Choose Sub-element") }}</option>');
                            $.each(data, function (key, value) {
                                $('#sub_element').append('<option value="' + key + '">' + value + '</option>');
                            });
                        },
                        error: function(xhr, status, error) {
                            console.error('Error fetching sub-elements:', error);
                            console.log('Response details:', xhr.responseText);
                        }
                    });
                } else {
                    $('#sub_element').html('<option value="">{{ __("Choose Sub-element") }}</option>');
                }
            });
        });
    </script>
@endsection
