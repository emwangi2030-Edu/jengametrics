@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row mb-5 text-center">
        <div class="col-12">
            <h2 class="jm-page-title display-6 fw-bold">{{ __('Add Item to :level', ['level' => $bqLevel->name]) }}</h2>
            <p class="text-muted">{{ __('Select a section, element, and item to include in this level, or add a manual item.') }}</p>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0 rounded">
                <div class="card-body p-5">
                    <form id="bq-section-form" method="POST" action="{{ route('bq_levels.items.store', [$bqDocument, $bqLevel]) }}">
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

                        {{-- Manual item materials --}}
                        <div class="manual-only d-none mb-4">
                            <label class="form-label fw-semibold">{{ __('Manual item materials') }}</label>
                            <div id="manual-materials" class="d-flex flex-column gap-3">
                                <div class="row g-3 align-items-end manual-material-row" data-index="0">
                                    <div class="col-12 col-md-4">
                                        <label class="form-label">{{ __('Material name') }}</label>
                                        <input type="text" name="manual_materials[0][name]" class="form-control manual-material-name" placeholder="{{ __('e.g. Cement') }}">
                                    </div>
                                    <div class="col-12 col-md-3">
                                        <label class="form-label">{{ __('Unit') }}</label>
                                        <select name="manual_materials[0][unit]" class="form-select manual-material-unit">
                                            <option value="">{{ __('Choose Unit') }}</option>
                                            @foreach($units ?? [] as $unit)
                                                <option value="{{ $unit->abbrev }}">{{ $unit->abbrev }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-6 col-md-2">
                                        <label class="form-label">{{ __('Quantity') }}</label>
                                        <input type="number" step="0.01" min="0" name="manual_materials[0][quantity]" class="form-control manual-material-quantity" placeholder="0">
                                    </div>
                                    <div class="col-6 col-md-2">
                                        <label class="form-label">{{ __('Unit rate') }}</label>
                                        <input type="number" step="0.01" min="0" name="manual_materials[0][rate]" class="form-control manual-material-rate" placeholder="0.00">
                                    </div>
                                    <div class="col-12 col-md-1 d-grid">
                                        <button type="button" class="btn btn-outline-danger btn-sm manual-material-remove" disabled>
                                            <i data-feather="x"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-3">
                                <button type="button" id="manual-material-add" class="btn btn-outline-success btn-sm">
                                    {{ __('Add material') }}
                                </button>
                            </div>
                        </div>

                        <template id="manual-material-template">
                            <div class="row g-3 align-items-end manual-material-row" data-index="__INDEX__">
                                <div class="col-12 col-md-4">
                                    <label class="form-label">{{ __('Material name') }}</label>
                                    <input type="text" name="manual_materials[__INDEX__][name]" class="form-control manual-material-name" placeholder="{{ __('e.g. Cement') }}">
                                </div>
                                <div class="col-12 col-md-3">
                                    <label class="form-label">{{ __('Unit') }}</label>
                                    <select name="manual_materials[__INDEX__][unit]" class="form-select manual-material-unit">
                                        <option value="">{{ __('Choose Unit') }}</option>
                                        @foreach($units ?? [] as $unit)
                                            <option value="{{ $unit->abbrev }}">{{ $unit->abbrev }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-6 col-md-2">
                                    <label class="form-label">{{ __('Quantity') }}</label>
                                    <input type="number" step="0.01" min="0" name="manual_materials[__INDEX__][quantity]" class="form-control manual-material-quantity" placeholder="0">
                                </div>
                                <div class="col-6 col-md-2">
                                    <label class="form-label">{{ __('Unit rate') }}</label>
                                    <input type="number" step="0.01" min="0" name="manual_materials[__INDEX__][rate]" class="form-control manual-material-rate" placeholder="0.00">
                                </div>
                                <div class="col-12 col-md-1 d-grid">
                                    <button type="button" class="btn btn-outline-danger btn-sm manual-material-remove">
                                        <i data-feather="x"></i>
                                    </button>
                                </div>
                            </div>
                        </template>

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
                            <button type="button" class="btn btn-success w-50 py-2" data-bs-toggle="modal" data-bs-target="#confirmSaveToBoqModal">
                                {{ __('Save to BoQ') }}
                            </button>
                        </div>
                    </form>
                    <br>
                    <div class="d-flex justify-content-center">
                        <a href="{{ route('bq_levels.show', [$bqDocument, $bqLevel]) }}" class="btn btn-dark" aria-label="Back" title="Back"><span data-feather="arrow-left-circle"></span></a>
                    </div>

                    <!-- Confirm Save Modal -->
                    <div class="modal fade" id="confirmSaveToBoqModal" tabindex="-1" aria-labelledby="confirmSaveToBoqModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="confirmSaveToBoqModalLabel">{{ __('Confirm Save') }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('Close') }}"></button>
                                </div>
                                <div class="modal-body">
                                    {{ __('Save this item to the BoQ?') }}
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                                    <button type="button" class="btn btn-success" id="confirm-save-to-boq">{{ __('Yes, Save') }}</button>
                                </div>
                            </div>
                        </div>
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

        $('#confirm-save-to-boq').on('click', function () {
            $('#bq-section-form').trigger('submit');
        });

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
            setManualMaterialRequired(manual);

            if (manual) {
                $('#element').val('');
                $('#item_id').val('');
            } else {
                $('#manual_name').val('');
                $('#manual_unit').val('');
                resetManualMaterials();
            }
        }

        $('#manual_item').on('change', toggleManualMode);
        toggleManualMode();

        function setManualMaterialRequired(required) {
            $('#manual-materials').find('input, select').prop('required', required);
        }

        function createManualMaterialRow(index) {
            const template = $('#manual-material-template').html();
            return template.replace(/__INDEX__/g, index);
        }

        function updateManualMaterialButtons() {
            const rows = $('#manual-materials .manual-material-row');
            rows.find('.manual-material-remove').prop('disabled', rows.length <= 1);
        }

        function refreshFeatherIcons() {
            if (window.feather && typeof window.feather.replace === 'function') {
                window.feather.replace();
            }
        }

        function renumberManualMaterials() {
            $('#manual-materials .manual-material-row').each(function (index) {
                $(this).attr('data-index', index);
                $(this).find('[name]').each(function () {
                    const name = $(this).attr('name');
                    const updated = name.replace(/manual_materials\\[\\d+\\]/, 'manual_materials[' + index + ']');
                    $(this).attr('name', updated);
                });
            });
        }

        function resetManualMaterials() {
            $('#manual-materials').html(createManualMaterialRow(0));
            updateManualMaterialButtons();
            refreshFeatherIcons();
        }

        $('#manual-material-add').on('click', function () {
            const nextIndex = $('#manual-materials .manual-material-row').length;
            $('#manual-materials').append(createManualMaterialRow(nextIndex));
            setManualMaterialRequired($('#manual_item').is(':checked'));
            updateManualMaterialButtons();
            refreshFeatherIcons();
        });

        $('#manual-materials').on('click', '.manual-material-remove', function () {
            $(this).closest('.manual-material-row').remove();
            renumberManualMaterials();
            updateManualMaterialButtons();
            refreshFeatherIcons();
        });

        // Function to display error message
        function showError(message) {
            let alertDiv = `<div class="alert alert-danger alert-dismissible fade show" role="alert">
                                ${message}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>`;
            $('.card-body').prepend(alertDiv);
        }

        function normalizeOptions(data) {
            if (Array.isArray(data)) {
                return data.map(function (item) {
                    return {
                        id: item.id ?? item.value ?? '',
                        name: item.name ?? item.label ?? '',
                        unit: item.unit ?? ''
                    };
                });
            }

            return Object.entries(data || {}).map(function ([id, name]) {
                return { id: id, name: name, unit: '' };
            });
        }

        function sortOptionsByName(options) {
            return options.slice().sort(function (a, b) {
                return String(a.name).localeCompare(String(b.name));
            });
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
                        const options = sortOptionsByName(normalizeOptions(data));
                        $.each(options, function (_, option) {
                            $('#element').append('<option value="' + option.id + '">' + option.name + '</option>');
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
                        const options = sortOptionsByName(normalizeOptions(data));
                        $.each(options, function (_, option) {
                            $('#item_id').append('<option value="' + option.id + '">' + option.name + '</option>');
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
