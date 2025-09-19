<!-- Requisition Modal -->
<div class="modal fade" id="requisitionModal" tabindex="-1" aria-labelledby="requisitionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('requisitions.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="requisitionModalLabel">Requisition Material</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label for="section" class="form-label">Section</label>
                        <select name="section" id="section" class="form-select" required>
                            <option value="" disabled selected>Select Section</option>
                            @foreach ($sections as $section)
                                <option value="{{ $section->id }}">{{ $section->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="bom_item_id" class="form-label">Select Material</label> 
                        <select name="bom_item_id" id="bom_item_id" class="form-select" required {{ $requisitionableItems->isEmpty() ? 'disabled' : '' }}>
                            @if ($requisitionableItems->isEmpty())
                                <option disabled selected>No materials available for requisition</option>
                            @else
                                <option value="" disabled selected>Choose Material</option>
                                @foreach ($requisitionableItems as $item)
                                    <option value="{{ $item->id }}"
                                            data-max="{{ $item->remaining_quantity }}"
                                            data-unit="{{ $item->unit }}">
                                        {{ $item->item_material->name ?? 'N/A' }} (Available: {{ (int) $item->remaining_quantity }} {{ $item->unit }})
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="quantity_requested" class="form-label">Quantity</label>
                        <input type="number" name="quantity_requested" id="quantity_requested" step="0.01" class="form-control" required>
                    </div>

                    <div class="mb-3 text-center">
                        <button type="button" class="btn btn-outline-secondary" id="request-adhoc-material">
                            Request Material Not in BOM
                        </button>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success" {{ $requisitionableItems->isEmpty() ? 'disabled' : '' }}>Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const trigger = document.getElementById('request-adhoc-material');
        const requisitionModalEl = document.getElementById('requisitionModal');
        const adhocModalEl = document.getElementById('adhocRequisitionModal');

        if (!trigger || !requisitionModalEl || !adhocModalEl || typeof bootstrap === 'undefined') {
            return;
        }

        const requisitionModal = bootstrap.Modal.getOrCreateInstance(requisitionModalEl);
        const adhocModal = bootstrap.Modal.getOrCreateInstance(adhocModalEl);

        trigger.addEventListener('click', () => {
            const showAdhoc = () => {
                adhocModal.show();
                requisitionModalEl.removeEventListener('hidden.bs.modal', showAdhoc);
            };

            requisitionModalEl.addEventListener('hidden.bs.modal', showAdhoc);
            requisitionModal.hide();
        });
    });
</script>
@endpush
