@extends('layouts.app')

@section('content')

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
                <div>
                    <h2 class="fw-bold text-primary mb-1">
                        {{ __('Bill of Quantities for') }} {{ $project->name }}
                    </h2>
                    <p class="text-muted mb-0">{{ __('Master document aggregates all BoQs for requisitions and reporting.') }}</p>
                </div>
                <div class="d-flex flex-column flex-sm-row gap-2 mt-3 mt-md-0">
                    <button type="button"
                        class="btn btn-success"
                        data-bs-toggle="modal"
                        data-bs-target="#createSubBoqModal">
                        {{ __('Create BoQ') }}
                    </button>
                    <button type="button"
                        class="btn btn-outline-primary"
                        data-bs-toggle="modal"
                        data-bs-target="#createLibraryModal">
                        {{ __('Create Library') }}
                    </button>
                </div>
            </div>

            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body d-flex flex-column flex-md-row justify-content-between align-items-md-center">
                    <div>
                        <h5 class="fw-bold text-secondary mb-1">{{ $masterDocument->title }}</h5>
                        <p class="text-muted mb-0">{{ __('Total across all BoQs') }}</p>
                    </div>
                    <div class="text-end">
                        <p class="fs-4 fw-bold text-dark mb-0">KES {{ number_format($overallTotal, 2) }}</p>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-body">
                    @if($subDocuments->isEmpty())
                        <p class="text-muted">{{ __('No BoQs created yet.') }}</p>
                    @else
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>{{ __('Title') }}</th>
                                        <th class="text-center">{{ __('Items') }}</th>
                                        <th class="text-end">{{ __('Total Amount (KES)') }}</th>
                                        <th class="text-end">{{ __('Created') }}</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($subDocuments as $document)
                                        <tr>
                                            <td class="fw-semibold">{{ $document->title }}</td>
                                            <td class="text-center">{{ $document->unique_items_count ?? 0 }}</td>
                                            <td class="text-end">{{ number_format($document->aggregated_amount ?? 0, 2) }}</td>
                                            <td class="text-end">{{ $document->created_at->format('d M Y') }}</td>
                                            <td class="text-end">
                                                <a href="{{ route('bq_documents.copy', $document) }}" class="btn btn-success btn-sm me-2" title="{{ __('Copy BoQ') }}">
                                                    +
                                                </a>
                                                <button type="button" class="btn btn-outline-secondary btn-sm me-2" data-bs-toggle="modal" data-bs-target="#editSubBoqModal{{ $document->id }}">
                                                    {{ __('Edit') }}
                                                </button>
                                                <a href="{{ route('bq_documents.show', $document) }}" class="btn btn-primary btn-sm me-2">
                                                    {{ __('View') }}
                                                </a>
                                                <form action="{{ route('bq_documents.destroy', $document) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('Are you sure you want to delete this BoQ?') }}');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm">
                                                        {{ __('Delete') }}
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>

    <!-- Create/Edit Sub BoQ Modals -->
    <div class="modal fade" id="createSubBoqModal" tabindex="-1" aria-labelledby="createSubBoqModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="{{ route('bq_documents.store') }}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="createSubBoqModalLabel">{{ __('Create Sub BoQ') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('Close') }}"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label" for="create-sub-boq-title">{{ __('Title') }}</label>
                            <input type="text" class="form-control" id="create-sub-boq-title" name="title" required maxlength="255">
                        </div>
                        <div class="mb-0">
                            <label class="form-label" for="create-sub-boq-description">{{ __('Description (optional)') }}</label>
                            <textarea class="form-control" id="create-sub-boq-description" name="description" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                        <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @foreach($subDocuments as $document)
        <div class="modal fade" id="editSubBoqModal{{ $document->id }}" tabindex="-1" aria-labelledby="editSubBoqModalLabel{{ $document->id }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="POST" action="{{ route('bq_documents.update', $document) }}">
                        @csrf
                        @method('PUT')
                        <div class="modal-header">
                            <h5 class="modal-title" id="editSubBoqModalLabel{{ $document->id }}">{{ __('Edit Sub BoQ') }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('Close') }}"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label" for="edit-sub-boq-title-{{ $document->id }}">{{ __('Title') }}</label>
                                <input type="text" class="form-control" id="edit-sub-boq-title-{{ $document->id }}" name="title" value="{{ $document->title }}" required maxlength="255">
                            </div>
                            <div class="mb-0">
                                <label class="form-label" for="edit-sub-boq-description-{{ $document->id }}">{{ __('Description (optional)') }}</label>
                                <textarea class="form-control" id="edit-sub-boq-description-{{ $document->id }}" name="description" rows="3">{{ $document->description }}</textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                            <button type="submit" class="btn btn-primary">{{ __('Save Changes') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach

            <div class="card shadow-sm border-0 mt-4">
                <div class="card-body">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-3">
                        <h5 class="fw-bold mb-0">{{ __('Libraries') }}</h5>
                    </div>

                    @if($libraries->isEmpty())
                        <p class="text-muted mb-0">{{ __('No libraries created yet. Use the button above to create one.') }}</p>
                    @else
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>{{ __('Name') }}</th>
                                        <th class="text-center">{{ __('Items') }}</th>
                                        <th class="text-end">{{ __('Created') }}</th>
                                        <th class="text-end">{{ __('Actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($libraries as $library)
                                        <tr>
                                            <td class="fw-semibold">{{ $library->name }}</td>
                                            <td class="text-center">{{ $library->items_count }}</td>
                                            <td class="text-end">{{ $library->created_at->format('d M Y') }}</td>
                                            <td class="text-end">                                                                                                                             
                                                <div class="btn-group btn-group-sm" role="group">                                                                                             
                                                    <button type="button"                                                                                                                     
                                                        class="btn btn-primary edit-library"                                                                                                  
                                                        data-library-name="{{ $library->name }}"                                                                                              
                                                        data-library-url="{{ route('libraries.items', $library) }}"                                                                           
                                                        data-library-update="{{ route('libraries.update', $library) }}">                                                                      
                                                        {{ __('Edit') }}                                                                                                                      
                                                    </button>                                                                                                                                 
                                                    <button type="button"                                                                                                                     
                                                        class="btn btn-outline-primary view-library-items"                                                                                    
                                                        data-library-name="{{ $library->name }}"                                                                                              
                                                        data-library-url="{{ route('libraries.items', $library) }}">                                                                          
                                                        {{ __('View') }}                                                                                                                      
                                                    </button>
                                                </div>                                                                                                                                        
                                                <form action="{{ route('libraries.destroy', $library) }}" method="POST" class="d-inline ms-2"                                                 
                                                    onsubmit="return confirm('{{ __('Delete this library? This action cannot be undone.') }}');">                                             
                                                    @csrf                                                                                                                                     
                                                    @method('DELETE')                                                                                                                         
                                                    <button type="submit" class="btn btn-danger btn-sm">                                                                                      
                                                        {{ __('Delete') }}                                                                                                                    
                                                    </button>                                                                                                                                 
                                                </form>                                                                                                                                       
                                            </td>     
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Library Modal -->                                                                                                                       
<div class="modal fade" id="editLibraryModal" tabindex="-1" aria-labelledby="editLibraryModalLabel" aria-hidden="true">                           
    <div class="modal-dialog modal-lg modal-dialog-centered">                                                                                     
        <div class="modal-content">                                                                                                               
            <form id="edit-library-form" method="POST">                                                                                           
                @csrf                                                                                                                             
                @method('PUT')                                                                                                                    
                <div class="modal-header">                                                                                                        
                    <h5 class="modal-title" id="editLibraryModalLabel">{{ __('Edit Library') }}</h5>                                              
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('Close') }}"></button>                      
                </div>                                                                                                                            
                <div class="modal-body">                                                                                                          
                    <div class="mb-3">                                                                                                            
                        <label for="edit-library-name" class="form-label">{{ __('Library Name') }}</label>                                        
                        <input type="text" id="edit-library-name" name="name" class="form-control" required>                                      
                    </div>                                                                                                                        
                    <div class="mb-3">                                                                                                            
                        <label for="edit-library-description" class="form-label">{{ __('Description (optional)') }}</label>                       
                        <textarea id="edit-library-description" name="description" class="form-control" rows="2"></textarea>                      
                    </div>                                                                                                                        
                    <div class="row g-3">                                                                                                         
                        <div class="col-md-6">                                                                                                    
                            <label class="form-label" for="edit-library-section">{{ __('Section') }}</label>                                      
                            <select id="edit-library-section" class="form-select">                                                                
                                <option value="">{{ __('Select a section') }}</option>                                                            
                                @foreach($sections as $section)                                                                                   
                                    <option value="{{ $section->id }}">{{ $section->name }}</option>                                              
                                @endforeach                                                                                                       
                            </select>                                                                                                             
                        </div>                                                                                                                    
                        <div class="col-md-6">                                                                                                    
                            <label class="form-label" for="edit-library-element">{{ __('Element') }}</label>                                      
                            <select id="edit-library-element" class="form-select" disabled>                                                       
                                <option value="">{{ __('Select an element') }}</option>                                                           
                            </select>                                                                                                             
                        </div>                                                                                                                    
                    </div>                                                                                                                        
                                                                                                                                                    
                    <div class="mt-4">                                                                                                            
                        <label class="form-label">{{ __('Available Items') }}</label>                                                             
                        <div id="edit-library-items-container" class="border rounded p-3" style="max-height: 250px; overflow-y: auto;">           
                            <p class="text-muted mb-0">{{ __('Select a section to load items.') }}</p>                                            
                        </div>                                                                                                                    
                    </div>                                                                                                                        
                                                                                                                                                    
                    <div class="mt-3">                                                                                                            
                        <label class="form-label">{{ __('Selected Items') }}</label>                                                              
                        <div id="edit-library-selected-items" class="selected-items border rounded p-3 bg-light" style="max-height: 200px; overflow-y: auto;">   
                            <p class="text-muted mb-0">{{ __('No items selected yet.') }}</p>                                                     
                        </div>                                                                                                                    
                        <div id="edit-library-selected-inputs" class="hidden-selected-inputs d-none"></div>                                                              
                    </div>                                                                                                                        
                </div>                                                                                                                            
                                                                                                                                                    
                <div class="modal-footer">                                                                                                        
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>                   
                    <button type="submit" class="btn btn-primary">{{ __('Save Changes') }}</button>                                               
                </div>                                                                                                                            
            </form>                                                                                                                               
        </div>                                                                                                                                    
    </div>                                                                                                                                        
</div>                                                                                                                                            
                                                                                                                                                    
<!-- Create Library Modal -->                                                                                                                     
<div class="modal fade" id="createLibraryModal" tabindex="-1" aria-labelledby="createLibraryModalLabel" aria-hidden="true">                       
    <div class="modal-dialog modal-lg modal-dialog-centered">                                                                                     
        <div class="modal-content">                                                                                                               
            <form action="{{ route('libraries.store') }}" method="POST">                                                                          
                @csrf                                                                                                                             
                <div class="modal-header">                                                                                                        
                    <h5 class="modal-title" id="createLibraryModalLabel">{{ __('Create Library') }}</h5>                                          
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('Close') }}"></button>                      
                </div>                                                                                                                            
                <div class="modal-body">                                                                                                          
                    <div class="mb-3">                                                                                                            
                        <label for="library-name" class="form-label">{{ __('Library Name') }}</label>                                             
                        <input type="text" id="library-name" name="name" class="form-control" required>                                           
                    </div>                                                                                                                        
                    <div class="mb-3">                                                                                                            
                        <label for="library-description" class="form-label">{{ __('Description (optional)') }}</label>                            
                        <textarea id="library-description" name="description" class="form-control" rows="2"></textarea>                           
                    </div>                                                                                                                        
                    <div class="row g-3">                                                                                                         
                        <div class="col-md-6">                                                                                                    
                            <label class="form-label" for="library-section">{{ __('Section') }}</label>                                           
                            <select id="library-section" class="form-select">                                                                     
                                <option value="">{{ __('Select a section') }}</option>                                                            
                                @foreach($sections as $section)                                                                                   
                                    <option value="{{ $section->id }}">{{ $section->name }}</option>                                              
                                @endforeach                                                                                                       
                            </select>                                                                                                             
                        </div>                                                                                                                    
                        <div class="col-md-6">                                                                                                    
                            <label class="form-label" for="library-element">{{ __('Element') }}</label>                                           
                            <select id="library-element" class="form-select" disabled>                                                            
                                <option value="">{{ __('Select an element') }}</option>                                                           
                            </select>                                                                                                             
                        </div>                                                                                                                    
                    </div>                                                                                                                        
                                                                                                                                                
                    <div class="mt-4">
                        <label class="form-label">{{ __('Available Items') }}</label>
                        <div id="library-items-container" class="border rounded p-3" style="max-height: 250px; overflow-y: auto;">
                            <p class="text-muted mb-0">{{ __('Select a section and element to load items.') }}</p>
                        </div>
                        <p class="small text-muted mt-2 mb-0">{{ __('Tick items to include them in this library. You can switch sections and elements to keep adding more; selections appear below.') }}</p>
                    </div>

                    <div class="mt-3">
                        <label class="form-label">{{ __('Selected Items') }}</label>
                        <div id="library-selected-items" class="selected-items border rounded p-3 bg-light" style="max-height: 200px; overflow-y: auto;">
                            <p class="text-muted mb-0">{{ __('No items selected yet.') }}</p>
                        </div>
                        <div id="library-selected-inputs" class="hidden-selected-inputs d-none"></div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                    <button type="submit" class="btn btn-primary">{{ __('Create') }}</button>                                                     
                </div>                                                                                                                            
            </form>                                                                                                                               
        </div>                                                                                                                                    
    </div>                                                                                                                                        
</div>                   

<!-- Library Items Modal -->
<div class="modal fade" id="libraryItemsModal" tabindex="-1" aria-labelledby="libraryItemsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="libraryItemsModalLabel">{{ __('Library Items') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('Close') }}"></button>
            </div>
            <div class="modal-body">
                <div id="library-items-table-wrapper">
                    <p class="text-muted mb-0">{{ __('Select a library to view its items.') }}</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">{{ __('Close') }}</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')                                                                                                                                  
<script>                                                                                                                                          
    (function () {                                                                                                                                
        /**                                                                                                                                       
         * Utilities shared by create/edit flows                                                                                                 
         */                                                                                                                                       
        const ITEM_TEMPLATE = (item) => `                                                                                                         
            <div class="d-flex justify-content-between align-items-center py-1 border-bottom">
                <div>                                                                                                                             
                    <strong>${item.name}</strong>                                                                                                 
                    <div class="small text-muted">${item.section_name ?? '-'} › ${item.element_name ?? '-'}</div>                                 
                </div>                                                                                                                            
                <button type="button" class="btn btn-sm btn-outline-danger remove-selected-item" data-item-id="${item.id}">                       
                    &times;                                                                                                                       
                </button>                                                                                                                         
            </div>
        `;                                                                                                                                        
                                                                                                                                                
        const CHECKBOX_TEMPLATE = (item) => `                                                                                                     
            <div class="form-check mb-2">                                                                                                         
                <input class="form-check-input library-item-checkbox" type="checkbox" value="${item.id}" id="item-${item.id}">                    
                <label class="form-check-label" for="item-${item.id}">                                                                            
                    <div class="fw-semibold">${item.name}</div>                                                                                   
                    <div class="small text-muted">${item.section_name ?? '-'} › ${item.element_name ?? '-'}</div>                                 
                </label>                                                                                                                          
            </div>                                                                                                                                
        `;                                                                                                                                        

        function renderAvailableItems(container, items, selectedMap = new Map()) {
            container.empty();

            if (!items || !items.length) {
                container.append('<p class="text-muted mb-0">{{ __('No items found for the current selection.') }}</p>');
                return;
            }

            items.forEach((item) => {
                const payload = {
                    id: parseInt(item.id ?? item.item_id, 10),
                    name: item.name ?? item.item ?? '-',
                    section_id: item.section_id ?? null,
                    section_name: item.section_name ?? item.section ?? '-',
                    element_id: item.element_id ?? null,
                    element_name: item.element_name ?? item.element ?? '-',
                };

                const existing = selectedMap.get(payload.id);
                if (existing) {
                    existing.section_id = payload.section_id;
                    existing.section_name = payload.section_name;
                    existing.element_id = payload.element_id;
                    existing.element_name = payload.element_name;
                }

                const templateData = {
                    id: payload.id,
                    name: payload.name,
                    section_name: (existing ?? payload).section_name,
                    element_name: (existing ?? payload).element_name,
                };

                const node = $(CHECKBOX_TEMPLATE(templateData));
                const checkbox = node.find('.library-item-checkbox');
                checkbox.data('payload', existing ?? payload);
                checkbox.prop('checked', Boolean(existing));
                container.append(node);
            });
        }
                                                                                                                                                
        function renderSelectedItems(wrapper, list) {                                                                                             
            wrapper.empty();                                                                                                                      
                                                                                                                                                
            if (!list || !list.length) {                                                                                                          
                wrapper.append('<p class="text-muted mb-0">{{ __('No items selected yet.') }}</p>');                                              
                return;                                                                                                                           
            }                                                                                                                                     
                                                                                                                                                
            list.forEach(item => wrapper.append($(ITEM_TEMPLATE(item))));                                                                         
        }                                                                                                                                         
                                                                                                                                                
        function syncHiddenInputs(container, list) {                                                                                              
            container.empty();                                                                                                                    
            list.forEach(item => {                                                                                                                
                container.append(`<input type="hidden" name="items[]" value="${item.id}">`);
            });                                                                                                                                   
        }                                                                                                                                         

        async function loadItemsForCreate(elementSelect, itemsContainer, selectedMap, selectedList) {
            const elementId = elementSelect.val();

            if (!elementId) {
                itemsContainer.html('<p class="text-muted mb-0">{{ __('Select a section and element to load items.') }}</p>');
                return;
            }

            itemsContainer.html('<p class="text-muted mb-0">{{ __('Loading items...') }}</p>');

            try {
                const items = await $.get('{{ route('items.details') }}', { element_id: elementId });

                if (!items || !items.length) {
                    itemsContainer.html('<p class="text-muted mb-0">{{ __('No items found for the selected element.') }}</p>');
                    return;
                }

                itemsContainer.empty();

                items.forEach((item) => {
                    const existing = selectedMap.get(item.id);
                    const payload = existing ?? {
                        id: item.id,
                        name: item.name,
                        section_id: item.section_id,
                        section_name: item.section_name,
                        element_id: item.element_id,
                        element_name: item.element_name,
                    };

                    if (existing) {
                        existing.section_id = item.section_id;
                        existing.section_name = item.section_name;
                        existing.element_id = item.element_id;
                        existing.element_name = item.element_name;
                    }

                    const checkboxId = `create-library-item-${item.id}`;
                    const wrapper = $('<div>', { class: 'form-check mb-2' });
                    const input = $('<input>', {
                        class: 'form-check-input library-item-checkbox',
                        type: 'checkbox',
                        value: item.id,
                        id: checkboxId,
                        checked: Boolean(existing),
                    });
                    input.data('payload', payload);

                    const label = $('<label>', { class: 'form-check-label', for: checkboxId });
                    label.append($('<div>', { class: 'fw-semibold' }).text(item.name || '-'));

                    const sectionName = payload.section_name || '-';
                    const elementName = payload.element_name || '-';
                    label.append($('<div>', { class: 'small text-muted' }).text(`${sectionName} / ${elementName}`));

                    wrapper.append(input);
                    wrapper.append(label);
                    itemsContainer.append(wrapper);
                });
            } catch (_) {
                itemsContainer.html('<p class="text-muted mb-0 text-danger">{{ __('Unable to load items. Please try again.') }}</p>');
            }
        }
                                                                                                                                                
        async function loadElements(sectionSelect, elementSelect) {                                                                               
            const sectionId = sectionSelect.val();                                                                                                
            elementSelect.prop('disabled', true).empty().append(                                                                                  
                $('<option>', { value: '', text: '{{ __('Select an element') }}' })                                                               
            );                                                                                                                                    
                                                                                                                                                
            if (!sectionId) return;                                                                                                               
                                                                                                                                                
            try {                                                                                                                                 
                const response = await $.get('{{ route('get.elements.by.section') }}', { section_id: sectionId });                                
                elementSelect.prop('disabled', false);                                                                                            
                                                                                                                                                
                $.each(response, (id, name) => {                                                                                                  
                    elementSelect.append($('<option>', { value: id, text: name }));                                                               
                });                                                                                                                               
            } catch (_) {                                                                                                                         
                elementSelect.prop('disabled', true);                                                                                             
            }                                                                                                                                     
        }                                                                                                                                         
                                                                                                                                                
        async function loadItems(elementSelect, itemsContainer, selectedMap) {
            const elementId = elementSelect.val();
            renderAvailableItems(itemsContainer, null);

            if (!elementId) return;

            try {
                const items = await $.get('{{ route('items.details') }}', { element_id: elementId });
                renderAvailableItems(itemsContainer, items, selectedMap);
            } catch (_) {
                itemsContainer.empty().append(
                    '<p class="text-muted mb-0">{{ __('Unable to load items. Please try again.') }}</p>'
                );
            }
        }

        /**                                                                                                                                       
         * Create flow                                                                                                                            
         */                                                                                                                                       
        (function initCreate() {
            const modal = $('#createLibraryModal');
            if (!modal.length) return;

            const sectionSelect = $('#library-section');
            const elementSelect = $('#library-element');
            const available = $('#library-items-container');
            const selectedWrapper = $('#library-selected-items');
            const hiddenInputs = $('#library-selected-inputs');

            const selectedMap = new Map();
            const selectedList = [];

            function renderSelected() {
                renderSelectedItems(selectedWrapper, selectedList);
                syncHiddenInputs(hiddenInputs, selectedList);
            }

            function reset() {
                sectionSelect.val('');
                elementSelect.prop('disabled', true).val('');
                available.html('<p class="text-muted mb-0">{{ __('Select a section and element to load items.') }}</p>');
                selectedMap.clear();
                selectedList.splice(0);
                renderSelected();
            }

            modal.on('show.bs.modal', reset);
            modal.on('hidden.bs.modal', reset);

            sectionSelect.on('change', async function () {
                elementSelect.prop('disabled', true).val('');
                available.html('<p class="text-muted mb-0">{{ __('Select a section and element to load items.') }}</p>');
                await loadElements(sectionSelect, elementSelect);
            });

            elementSelect.on('change', async function () {
                await loadItemsForCreate(elementSelect, available, selectedMap, selectedList);
            });

            available.on('change', '.library-item-checkbox', function () {
                const id = parseInt(this.value, 10);
                const payload = $(this).data('payload');

                if (this.checked) {
                    if (payload && !selectedMap.has(id)) {
                        selectedMap.set(id, payload);
                        selectedList.push(payload);
                    }
                } else {
                    selectedMap.delete(id);
                    const index = selectedList.findIndex(item => item.id === id);
                    if (index !== -1) {
                        selectedList.splice(index, 1);
                    }
                }

                renderSelected();
            });

            modal.on('click', '.remove-selected-item', function () {
                const id = parseInt($(this).data('item-id'), 10);

                selectedMap.delete(id);
                const index = selectedList.findIndex(item => item.id === id);
                if (index !== -1) {
                    selectedList.splice(index, 1);
                }

                renderSelected();
                available.find(`#create-library-item-${id}`).prop('checked', false);
            });
        })();
                                                                                                                                                
        /**                                                                                                                                       
         * Edit flow                                                                                                                              
         */                                                                                                                                       
        (function initEdit() {                                                                                                                    
            const modal = $('#editLibraryModal');                                                                                                 
            if (!modal.length) return;                                                                                                            
                                                                                                                                                
            const form = $('#edit-library-form');                                                                                                 
            const sectionSelect = $('#edit-library-section');                                                                                     
            const elementSelect = $('#edit-library-element');                                                                                     
            const available = $('#edit-library-items-container');                                                                                 
            const selectedWrapper = $('#edit-library-selected-items');                                                                            
            const hiddenInputs = $('#edit-library-selected-inputs');                                                                              
                                                                                                                                                
            const selectedMap = new Map();                                                                                                        
            const selectedList = [];                                                                                                              
                                                                                                                                                
            function reset() {                                                                                                                    
                form.attr('action', '#');                                                                                                         
                $('#edit-library-name').val('');                                                                                                  
                $('#edit-library-description').val('');                                                                                           
                sectionSelect.val('');                                                                                                            
                elementSelect.val('').prop('disabled', true);                                                                                     
                available.html('<p class="text-muted mb-0">{{ __('Select a section to load items.') }}</p>');
                selectedMap.clear();                                                                                                              
                selectedList.splice(0);                                                                                                           
                renderSelectedItems(selectedWrapper, selectedList);                                                                               
                hiddenInputs.empty();                                                                                                             
            }                                                                                                                                     
                                                                                                                                                
            modal.on('hidden.bs.modal', reset);                                                                                                   
                                                                                                                                                
            $('.edit-library').on('click', async function () {                                                                                    
                reset();                                                                                                                          
                                                                                                                                                
                const fetchUrl = $(this).data('library-url');                                                                                     
                const updateUrl = $(this).data('library-update');                                                                                 
                                                                                                                                                
                try {                                                                                                                             
                    const response = await $.get(fetchUrl);                                                                                       
                    const library = response.library || {};                                                                                       
                    const items = response.items || [];                                                                                           
                                                                                                                                                
                    form.attr('action', updateUrl);                                                                                               
                    $('#edit-library-name').val(library.name || '');                                                                              
                    $('#edit-library-description').val(library.description || '');                                                                
                                                                                                                                                
                    selectedMap.clear();
                    items.forEach(item => {
                        const payload = {
                            id: parseInt(item.item_id, 10),
                            name: item.item,
                            section_id: item.section_id ?? null,
                            section_name: item.section,
                            element_id: item.element_id ?? null,
                            element_name: item.element,
                        };

                        selectedMap.set(payload.id, payload);
                    });
                    selectedList.splice(0, selectedList.length, ...selectedMap.values());
                    renderSelectedItems(selectedWrapper, selectedList);                                                                           
                    syncHiddenInputs(hiddenInputs, selectedList);                                                                                 
                                                                                                                                                
                    sectionSelect.val(library.section_id || '');                                                                                  
                    await loadElements(sectionSelect, elementSelect);                                                                             
                                                                                                                                                
                    if (library.element_id) {
                        elementSelect.val(library.element_id);
                        await loadItems(elementSelect, available, selectedMap);
                    } else {
                        available.html('<p class="text-muted mb-0">{{ __('Select a section to load items.') }}</p>');
                    }
                                                                                                                                                
                    modal.modal('show');                                                                                                          
                } catch (_) {                                                                                                                     
                    alert('{{ __('Unable to load library details. Please try again.') }}');                                                       
                }                                                                                                                                 
            });                                                                                                                                   
                                                                                                                                                
            sectionSelect.on('change', async function () {                                                                                        
                elementSelect.prop('disabled', true).val('');                                                                                     
                await loadElements(sectionSelect, elementSelect);                                                                                 
                available.html('<p class="text-muted mb-0">{{ __('Select a section to load items.') }}</p>');                                     
            });                                                                                                                                   
                                                                                                                                                
            elementSelect.on('change', async function () {                                                                                        
                await loadItems(elementSelect, available, selectedMap);                                                             
            });                                                                                                                                   
                                                                                                                                                
            available.on('change', '.library-item-checkbox', function () {
                const id = parseInt(this.value, 10);
                const payload = $(this).data('payload');

                if (this.checked) {
                    if (payload && !selectedMap.has(id)) {
                        selectedMap.set(id, payload);
                        selectedList.push(payload);
                    }
                } else {
                    selectedMap.delete(id);
                    const index = selectedList.findIndex(item => item.id === id);
                    if (index !== -1) {
                        selectedList.splice(index, 1);
                    }
                }

                renderSelectedItems(selectedWrapper, selectedList);
                syncHiddenInputs(hiddenInputs, selectedList);
            });

            modal.on('click', '.remove-selected-item', function () {
                const id = parseInt($(this).data('item-id'), 10);

                selectedMap.delete(id);
                const index = selectedList.findIndex(item => item.id === id);
                if (index !== -1) {
                    selectedList.splice(index, 1);
                }

                renderSelectedItems(selectedWrapper, selectedList);
                syncHiddenInputs(hiddenInputs, selectedList);
                available.find(`#item-${id}`).prop('checked', false);
            });
        })();                                                                                                                                     
                                                                                                                                                
        /**                                                                                                                                       
         * View-only flow (unchanged)                                                                                                             
         */                                                                                                                                       
        (function initViewer() {                                                                                                                  
            const modal = $('#libraryItemsModal');                                                                                                
            if (!modal.length) return;                                                                                                            
                                                                                                                                                
            const tableWrapper = $('#library-items-table-wrapper');                                                                               
                                                                                                                                                
            $('.view-library-items').on('click', async function () {                                                                              
                const url = $(this).data('library-url');                                                                                          
                tableWrapper.html('<p class="text-muted mb-0">{{ __('Loading items...') }}</p>');                                                 
                modal.find('.modal-title').text($(this).data('library-name'));                                                                    
                modal.modal('show');                                                                                                              
                                                                                                                                                
                try {                                                                                                                             
                    const response = await $.get(url);                                                                                            
                    const items = response.items;                                                                                                 
                                                                                                                                                
                    if (!items || !items.length) {                                                                                                
                        tableWrapper.html('<p class="text-muted mb-0">{{ __('This library does not contain any items yet.') }}</p>');             
                        return;
                    }                                                                                                                             
                                                                                                                                                
                    const rows = items.map(item => `                                                                                              
                        <tr>                                                                                                                      
                            <td>${item.section || '-'}</td>                                                                                       
                            <td>${item.element || '-'}</td>                                                                                       
                            <td>${item.item || '-'}</td>                                                                                          
                            <td>${item.unit || '-'}</td>                                                                                          
                        </tr>                                                                                                                     
                    `).join('');                                                                                                                  
                                                                                                                                                
                    tableWrapper.html(`                                                                                                           
                        <div class="table-responsive">                                                                                            
                            <table class="table table-sm align-middle mb-0">                                                                      
                                <thead class="table-light">                                                                                       
                                    <tr>                                                                                                          
                                        <th>{{ __('Section') }}</th>                                                                              
                                        <th>{{ __('Element') }}</th>                                                                              
                                        <th>{{ __('Item') }}</th>                                                                                 
                                        <th>{{ __('Unit') }}</th>                                                                                 
                                    </tr>                                                                                                         
                                </thead>                                                                                                          
                                <tbody>${rows}</tbody>                                                                                            
                            </table>                                                                                                              
                        </div>                                                                                                                    
                    `);                                                                                                                           
                } catch (_) {                                                                                                                     
                    tableWrapper.html('<p class="text-muted mb-0 text-danger">{{ __('Failed to load library items.') }}</p>');                    
                }                                                                                                                                 
            });
        })();                                                                                                                                     
    })();                                                                                                                                         
</script>                                                                                                                                         
@endpush           
