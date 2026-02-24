@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Create New BOM</h1>

    <form method="POST" action="{{ route('boms.store') }}">
        @csrf

        <!-- BOM Name -->
        <div class="form-group mb-4">
            <label for="bom_name" class="form-label">{{ __('BOM Name') }}</label>
            <input type="text" name="bom_name" id="bom_name" class="form-control" placeholder="Enter BOM Name" required>
        </div>



        <!-- Items Container -->
        <div id="items-container">
            <div class="item-entry mb-4">
                <label for="item_description" class="form-label">{{ __('Item Description') }}</label>
                <input type="text" name="items[0][description]" class="form-control mb-2" placeholder="Enter Item Description" required>

                <div class="row">
                    <div class="col-md-3">
                        <label for="unit" class="form-label">{{ __('Unit of Measurement') }}</label>
                        <select name="items[0][unit]" class="form-control mb-2" required>
                            <option value="Square Meter">Square Meter</option>
                            <option value="Square Root">Square Foot</option>
                            <option value="Meter">Meter</option>
                            <option value="Inch">Inch</option>
                            <option value="Millimeter">Millimeter</option>
                            <option value="Ton">Ton</option>
                            <option value="Kilogram">Kilogram</option>
                            <option value="Bag">Bag</option>
                            <option value="Piece">Piece</option>
                            <option value="Foot">Foot</option>
                            <option value="Centimeter">Centimeter</option>
                            <option value="Litre">Litre</option>
                            <option value="Roll">Roll</option>
                            <option value="Packet">Packet</option>
                            <option value="carton">Carton</option>
                            <option value="Bucket">Bucket</option>
                            <option value="Bundle">Bundle</option>
                            <option value="Box">Box</option>
                            <option value="Bale">Bale</option>
                            <option value="Gallon">Gallon</option>
                            <option value="Ream">Ream</option>
                            <option value="Sheet">Sheet</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="rate" class="form-label">{{ __('Price per Unit') }}</label>
                        <input type="number" name="items[0][rate]" class="form-control mb-2 rate" placeholder="Enter Unit Price" step="0.01" required>
                    </div>
                    <div class="col-md-3">
                        <label for="quantity" class="form-label">{{ __('Quantity') }}</label>
                        <input type="number" name="items[0][quantity]" class="form-control mb-2 quantity" placeholder="Enter Quantity" required>
                    </div>
                    <div class="col-md-3">
                        <label for="amount" class="form-label">{{ __('Total Amount') }}</label>
                        <input type="number" name="items[0][amount]" class="form-control mb-2 amount" placeholder="Amount" step="0.01" readonly>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add Item Button -->
        <button type="button" id="add-item" class="btn btn-secondary">{{ __('Add Item') }}</button>

        <!-- Create BOM Button -->
        <button type="submit" class="btn btn-primary">{{ __('Create BOM') }}</button>
        <a href="{{ route('boms.index') }}" class="btn btn-secondary" aria-label="Back" title="Back"><span data-feather="arrow-left-circle"></span></a>
    </form>
</div>

<script>
    // Function to calculate the amount when quantity or rate changes
    function calculateAmount(row) {
        const rate = row.querySelector('.rate').value;
        const quantity = row.querySelector('.quantity').value;
        const amountField = row.querySelector('.amount');
        
        if (rate && quantity) {
            const amount = parseFloat(rate) * parseFloat(quantity);
            amountField.value = amount.toFixed(2); // Round to 2 decimal places
        } else {
            amountField.value = ''; // Clear the amount field if rate or quantity is empty
        }
    }

    // Function to add event listeners to rate and quantity fields
    function addEventListeners(row) {
        row.querySelector('.rate').addEventListener('input', function() {
            calculateAmount(row);
        });
        row.querySelector('.quantity').addEventListener('input', function() {
            calculateAmount(row);
        });
    }

    // Add event listeners for the initial row
    const initialRow = document.querySelector('.item-entry');
    addEventListeners(initialRow);

    // Add event listeners for dynamically added rows
    document.getElementById('add-item').addEventListener('click', function() {
        const itemsContainer = document.getElementById('items-container');
        const itemIndex = itemsContainer.children.length;
        const newItem = `
            <div class="item-entry mb-4">
                <label for="item_description" class="form-label">{{ __('Item Description') }}</label>
                <input type="text" name="items[${itemIndex}][description]" class="form-control mb-2" placeholder="Enter Item Description" required>

                <div class="row">
                    <div class="col-md-3">
                        <label for="unit" class="form-label">{{ __('Unit of Measurement') }}</label>
                        <select name="items[${itemIndex}][unit]" class="form-control mb-2" required>
                            <option value="Square Meter">Square Meter</option>
                            <option value="Square Root">Square Foot</option>
                            <option value="Meter">Meter</option>
                            <option value="Inch">Inch</option>
                            <option value="Millimeter">Millimeter</option>
                            <option value="Ton">Ton</option>
                            <option value="Kilogram">Kilogram</option>
                            <option value="Bag">Bag</option>
                            <option value="Piece">Piece</option>
                            <option value="Foot">Foot</option>
                            <option value="Centimeter">Centimeter</option>
                            <option value="Litre">Litre</option>
                            <option value="Roll">Roll</option>
                            <option value="Packet">Packet</option>
                            <option value="carton">Carton</option>
                            <option value="Bucket">Bucket</option>
                            <option value="Bundle">Bundle</option>
                            <option value="Box">Box</option>
                            <option value="Bale">Bale</option>
                            <option value="Gallon">Gallon</option>
                            <option value="Ream">Ream</option>
                            <option value="Sheet">Sheet</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="rate" class="form-label">{{ __('Rate') }}</label>
                        <input type="number" name="items[${itemIndex}][rate]" class="form-control mb-2 rate" step="0.01" required>
                    </div>
                    <div class="col-md-3">
                        <label for="quantity" class="form-label">{{ __('Quantity') }}</label>
                        <input type="number" name="items[${itemIndex}][quantity]" class="form-control mb-2 quantity" required>
                    </div>
                    <div class="col-md-3">
                        <label for="amount" class="form-label">{{ __('Amount') }}</label>
                        <input type="number" name="items[${itemIndex}][amount]" class="form-control mb-2 amount" step="0.01" readonly>
                    </div>
                </div>
            </div>
        `;
        itemsContainer.insertAdjacentHTML('beforeend', newItem);

        // Add event listeners to the new row
        const newRow = itemsContainer.lastElementChild;
        addEventListeners(newRow);
    });
</script>

@endsection
