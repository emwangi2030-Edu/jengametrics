@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row mb-4 text-center">
        <div class="col-12">
            <h2 class="display-6 fw-bold" style="color:#027333;">{{ __('Bulk Add BoQ Items') }}</h2>
            <p class="text-muted">Add multiple items to a section. Use the quick-add modal on the section page for single items.</p>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-sm border-0 rounded">
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('bq_sections.store_bulk') }}">
                        @csrf
                        <div class="row g-3 align-items-end mb-3">
                            <div class="col-md-6">
                                <label for="section" class="form-label">{{ __('Select Section') }}</label>
                                <select name="section_id" id="section" class="form-select" required>
                                    <option value="">{{ __('Choose Section') }}</option>
                                    @foreach($sections as $section)
                                        <option value="{{ $section->id }}" {{ (string)($prefillSection ?? '') === (string)$section->id ? 'selected' : '' }}>{{ $section->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 text-end">
                                <button type="button" id="addRowBtn" class="btn btn-success">+ {{ __('Add Row') }}</button>
                            </div>
                        </div>

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="table-responsive">
                            <table class="table table-sm table-hover align-middle" id="bulkTable">
                                <thead class="table-light">
                                    <tr>
                                        <th>{{ __('Element') }}</th>
                                        <th>{{ __('Item') }}</th>
                                        <th class="text-end">{{ __('Rate') }}</th>
                                        <th class="text-end">{{ __('Quantity') }}</th>
                                        <th class="text-end">{{ __('Amount') }}</th>
                                        <th class="text-end">{{ __('Actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-center mt-3">
                            <button type="submit" class="btn w-50 py-2 text-white" style="background-color:#027333;">{{ __('Save All') }}</button>
                        </div>
                    </form>

                    <div class="d-flex justify-content-center mt-3">
                        <a href="{{ route('boq') }}" class="btn btn-dark">Back</a>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0 rounded mt-4">
                <div class="card-body p-4">
                    <h5 class="mb-3">{{ __('Import from CSV') }}</h5>
                    <p class="text-muted small mb-2">{{ __('Accepted columns (header optional): element_id,item_id,rate,quantity') }}</p>
                    <form method="POST" action="{{ route('bq_sections.import_csv') }}" enctype="multipart/form-data" class="row g-3 align-items-end">
                        @csrf
                        <div class="col-md-6">
                            <label for="csv_section" class="form-label">{{ __('Select Section') }}</label>
                            <select name="section_id" id="csv_section" class="form-select" required>
                                <option value="">{{ __('Choose Section') }}</option>
                                @foreach($sections as $section)
                                    <option value="{{ $section->id }}" {{ (string)($prefillSection ?? '') === (string)$section->id ? 'selected' : '' }}>{{ $section->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="csv_file" class="form-label">{{ __('CSV File') }}</label>
                            <input type="file" name="csv" id="csv_file" accept=".csv,text/csv" class="form-control" required>
                        </div>
                        <div class="col-md-2 text-end">
                            <button type="submit" class="btn btn-primary w-100">{{ __('Upload') }}</button>
                        </div>
                    </form>
                    @error('csv')
                        <div class="text-danger small mt-2">{{ $message }}</div>
                    @enderror
                    <div class="mt-3">
                        <button id="downloadTemplate" type="button" class="btn btn-link p-0">{{ __('Download CSV Template') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const sectionSelect = document.getElementById('section');
    const tableBody = document.querySelector('#bulkTable tbody');
    const addRowBtn = document.getElementById('addRowBtn');
    let rowIndex = 0;

    function createSelect(placeholder) {
        const sel = document.createElement('select');
        sel.className = 'form-select';
        const opt0 = document.createElement('option');
        opt0.value = '';
        opt0.textContent = placeholder;
        sel.appendChild(opt0);
        return sel;
    }

    function computeAmount(rateInput, qtyInput, amountInput){
        const r = parseFloat(rateInput.value) || 0;
        const q = parseFloat(qtyInput.value) || 0;
        amountInput.value = (r*q).toFixed(2);
    }

    function loadElementsForSection(sectionId, onLoaded){
        if(!sectionId){ onLoaded({}); return; }
        fetch(`{{ route('get.elements') }}?section_id=${sectionId}`)
            .then(r=>r.json()).then(onLoaded).catch(()=>onLoaded({}));
    }

    function loadItemsForElement(elementId, onLoaded){
        if(!elementId){ onLoaded({}); return; }
        fetch(`{{ route('get.items') }}?element_id=${elementId}`)
            .then(r=>r.json()).then(onLoaded).catch(()=>onLoaded({}));
    }

    function addRow(){
        const tr = document.createElement('tr');

        const tdElement = document.createElement('td');
        const tdItem = document.createElement('td');
        const tdRate = document.createElement('td');
        const tdQty = document.createElement('td');
        const tdAmt = document.createElement('td');
        const tdAct = document.createElement('td');

        const elementSelect = createSelect('{{ __('Choose Element') }}');
        elementSelect.name = `items[${rowIndex}][element_id]`;
        const itemSelect = createSelect('{{ __('Choose Item') }}');
        itemSelect.name = `items[${rowIndex}][item_id]`;

        const rateInput = document.createElement('input');
        rateInput.type = 'number'; rateInput.step = '0.01';
        rateInput.name = `items[${rowIndex}][rate]`;
        rateInput.required = true; rateInput.className = 'form-control text-end';

        const qtyInput = document.createElement('input');
        qtyInput.type = 'number'; qtyInput.step = '0.01';
        qtyInput.name = `items[${rowIndex}][quantity]`;
        qtyInput.required = true; qtyInput.className = 'form-control text-end';

        const amtInput = document.createElement('input');
        amtInput.type = 'number'; amtInput.step = '0.01';
        amtInput.name = `items[${rowIndex}][amount]`;
        amtInput.readOnly = true; amtInput.className = 'form-control text-end';

        const delBtn = document.createElement('button');
        delBtn.type = 'button'; delBtn.className = 'btn btn-sm btn-outline-danger';
        delBtn.textContent = '{{ __('Remove') }}';
        delBtn.addEventListener('click', ()=> tr.remove());

        elementSelect.addEventListener('change', function(){
            loadItemsForElement(this.value, function(items){
                itemSelect.innerHTML='';
                const ph = document.createElement('option'); ph.value=''; ph.textContent='{{ __('Choose Item') }}';
                itemSelect.appendChild(ph);
                Object.entries(items).forEach(([id,name])=>{
                    const opt = document.createElement('option'); opt.value=id; opt.textContent=name; itemSelect.appendChild(opt);
                });
            });
        });

        rateInput.addEventListener('input', ()=>computeAmount(rateInput, qtyInput, amtInput));
        qtyInput.addEventListener('input', ()=>computeAmount(rateInput, qtyInput, amtInput));

        tdElement.appendChild(elementSelect);
        tdItem.appendChild(itemSelect);
        tdRate.className='text-end'; tdRate.appendChild(rateInput);
        tdQty.className='text-end'; tdQty.appendChild(qtyInput);
        tdAmt.className='text-end'; tdAmt.appendChild(amtInput);
        tdAct.className='text-end'; tdAct.appendChild(delBtn);

        tr.appendChild(tdElement); tr.appendChild(tdItem); tr.appendChild(tdRate); tr.appendChild(tdQty); tr.appendChild(tdAmt); tr.appendChild(tdAct);
        tableBody.appendChild(tr);

        loadElementsForSection(sectionSelect.value, function(elements){
            elementSelect.innerHTML='';
            const ph = document.createElement('option'); ph.value=''; ph.textContent='{{ __('Choose Element') }}';
            elementSelect.appendChild(ph);
            Object.entries(elements).forEach(([id,name])=>{
                const opt = document.createElement('option'); opt.value=id; opt.textContent=name; elementSelect.appendChild(opt);
            });
        });

        rowIndex++;
    }

    addRowBtn.addEventListener('click', function(){
        if(!sectionSelect.value){
            alert('{{ __('Please select a section first.') }}');
            return;
        }
        addRow();
    });

    // Pre-add a row if section was preselected
    if (sectionSelect.value) {
        addRow();
    }
});

// Download template CSV
document.getElementById('downloadTemplate')?.addEventListener('click', function(){
    const csv = 'element_id,item_id,rate,quantity\n';
    const blob = new Blob([csv], {type: 'text/csv;charset=utf-8;'});
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url; a.download = 'boq_bulk_template.csv';
    document.body.appendChild(a); a.click(); document.body.removeChild(a);
    URL.revokeObjectURL(url);
});
</script>
@endpush
@endsection
