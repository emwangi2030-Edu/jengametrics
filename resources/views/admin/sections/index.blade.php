@extends('layouts.appbar')

@section('content')
<div class="container">
    <h1>Sections</h1>

    <!-- List sections -->
    <ul id="sections-list">
        @foreach($sections as $section)
            <li id="section-{{ $section->id }}">{{ $section->name }} - {{ $section->description }}</li>
        @endforeach
    </ul>

    <!-- Button to add more sections -->
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSectionModal">Add Section</button>

    <!-- Modal to add new section -->
    <div class="modal fade" id="addSectionModal" tabindex="-1" aria-labelledby="addSectionModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="add-section-form">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="addSectionModalLabel">Add New Section</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="sectionName" class="form-label">Section Name</label>
                            <input type="text" class="form-control" id="sectionName" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="sectionDescription" class="form-label">Description (Optional)</label>
                            <textarea class="form-control" id="sectionDescription" name="description"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Section</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function() {
        // Handle form submission via AJAX
        $('#add-section-form').submit(function(e) {
            e.preventDefault();
            
            let formData = {
                name: $('#sectionName').val(),
                description: $('#sectionDescription').val(),
                _token: $('input[name="_token"]').val(),
            };

            $.ajax({
                url: "{{ route('sections.store') }}",
                method: 'POST',
                data: formData,
                success: function(response) {
                    // Add the new section to the list without reloading
                    $('#sections-list').append(
                        `<li id="section-${response.section.id}">${response.section.name} - ${response.section.description ? response.section.description : ''}</li>`
                    );

                    // Clear the form
                    $('#sectionName').val('');
                    $('#sectionDescription').val('');

                    // Close the modal
                    $('#addSectionModal').modal('hide');
                },
                error: function(response) {
                    alert('There was an error adding the section.');
                }
            });
        });
    });
</script>
@endsection
