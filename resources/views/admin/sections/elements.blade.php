@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Elements for Section: {{ $section->name }}</h1>

    <!-- Form to add a new element -->
    <div class="card">
        <div class="card-header">Add New Element</div>
        <div class="card-body">
            <form action="{{ route('elements.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="elementNameInput">Element Name</label>
                    <input type="text" class="form-control" id="elementNameInput" name="name" required>
                </div>
                <!-- Store the section_id in the form (hidden input) -->
                <input type="hidden" name="section_id" value="{{ $section->id }}">
                <button type="submit" class="btn btn-primary mt-3">Save Element</button>
            </form>
        </div>
    </div>

    <!-- List of elements -->
    <div class="card my-4">
        <div class="card-header">Elements</div>
        <div class="card-body">
            <ul class="list-group">
                @forelse($section->elements as $element)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <strong>{{ $loop->iteration }}. </strong>
                            <strong>{{ $element->name }}</strong>
                        </div>
                        <div>


                                <!-- Items button -->
                            <a href="{{ route('subelements.items', $element->id) }}" class="btn btn-info btn-sm">
                                Items
                            </a>


                            <!-- Edit Element button -->
                            <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editElementModal{{ $element->id }}">
                                Edit
                            </button>

                            <!-- Delete Element form -->
                            <form action="{{ route('elements.destroy', $element->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this element?');">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </li>

                    <!-- Edit Element Modal -->
                    <div class="modal fade" id="editElementModal{{ $element->id }}" tabindex="-1" aria-labelledby="editElementModalLabel{{ $element->id }}" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editElementModalLabel{{ $element->id }}">Edit Element</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form action="{{ route('elements.update', $element->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="form-group">
                                            <label for="editElementNameInput{{ $element->id }}">Element Name</label>
                                            <input type="text" class="form-control" id="editElementNameInput{{ $element->id }}" name="name" value="{{ $element->name }}" required>
                                        </div>
                                        <button type="submit" class="btn btn-primary mt-3">Save Changes</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <li class="list-group-item">No elements available for this section.</li>
                @endforelse
            </ul>
        </div>
    </div>

    <!-- Back to Sections button -->
    <a href="{{ route('sections.index') }}" class="btn btn-secondary" aria-label="Back" title="Back"><span data-feather="arrow-left-circle"></span></a>
</div>
@endsection
