<!-- Ad-hoc Requisition Modal -->
<div class="modal fade" id="adhocRequisitionModal" tabindex="-1" aria-labelledby="adhocRequisitionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('requisitions.storeAdhoc') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="adhocRequisitionModalLabel">Request Material Not in BOM</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label for="section_adhoc" class="form-label">Section</label>
                        <select name="section" id="section_adhoc" class="form-select" required>
                            <option value="" disabled selected>Select Section</option>
                            @foreach ($sections as $section)
                                <option value="{{ $section->id }}">{{ $section->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="material_name" class="form-label">Material Name</label>
                        <input type="text" name="material_name" id="material_name" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="unit_of_measurement" class="form-label">Unit of Measurement</label>
                        <select name="unit_of_measurement" id="unit_of_measurement" class="form-select" required>
                            <option value="" disabled selected>Select Unit</option>
                            @foreach ($units as $unit)
                                <option value="{{ $unit->abbrev }}">{{ $unit->abbrev }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="quantity_requested_adhoc" class="form-label">Quantity</label>
                        <input type="number" step="0.01" name="quantity_requested" id="quantity_requested_adhoc" class="form-control" required>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

