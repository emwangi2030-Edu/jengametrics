@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Sub-Elements for Element: {{ $element->name }}</h1>

    <!-- Form to add a new sub-element -->
    <div class="card">
        <div class="card-header">Add New Sub-Element</div>
        <div class="card-body">
            <form action="{{ route('subelements.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="subElementNameInput">Sub-Element Name</label>
                    <input type="text" class="form-control" id="subElementNameInput" name="name" required>
                </div>
                <div class="form-group">
                    <label for="subElementDescriptionInput">Description</label>
                    <textarea class="form-control" id="subElementDescriptionInput" name="description" rows="3"></textarea>
                </div>
                <!-- Hidden input to store the element ID -->
                <input type="hidden" name="element_id" value="{{ $element->id }}">
                <button type="submit" class="btn btn-primary mt-3">Save Sub-Element</button>
            </form>
        </div>
    </div>

    <!-- List of sub-elements -->
    <div class="card my-4">
        <div class="card-header">Sub-Elements</div>
        <div class="card-body">
            <ul class="list-group">
                @forelse($element->subelements as $subelement)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <strong>{{ $subelement->name }}</strong><br>
                            <small>{{ $subelement->description }}</small>
                        </div>
                        <div>
                            <!-- Items button -->
                            <a href="{{ route('subelements.items', $subelement->id) }}" class="btn btn-info btn-sm">
                                Items
                            </a>

                            <!-- Edit and Delete buttons -->
                            <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editSubElementModal{{ $subelement->id }}">
                                Edit
                            </button>

                            <form action="{{ route('subelements.destroy', $subelement->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this sub-element?');">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </li>

                    <!-- Edit Sub-Element Modal -->
                    <div class="modal fade" id="editSubElementModal{{ $subelement->id }}" tabindex="-1" aria-labelledby="editSubElementModalLabel{{ $subelement->id }}" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editSubElementModalLabel{{ $subelement->id }}">Edit Sub-Element</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form action="{{ route('subelements.update', $subelement->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="form-group">
                                            <label for="editSubElementNameInput{{ $subelement->id }}">Sub-Element Name</label>
                                            <input type="text" class="form-control" id="editSubElementNameInput{{ $subelement->id }}" name="name" value="{{ $subelement->name }}" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="editSubElementDescriptionInput{{ $subelement->id }}">Description</label>
                                            <textarea class="form-control" id="editSubElementDescriptionInput{{ $subelement->id }}" name="description" rows="3">{{ $subelement->description }}</textarea>
                                        </div>
                                        <button type="submit" class="btn btn-primary mt-3">Save Changes</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <li class="list-group-item">No sub-elements available for this element.</li>
                @endforelse
            </ul>
        </div>
    </div>

    <!-- Back to Elements button -->
    <a href="{{ route('sections.elements', ['section' => $element->section_id]) }}" class="btn btn-secondary" aria-label="Back" title="Back"><span data-feather="arrow-left-circle"></span></a>
</div>
@endsection
