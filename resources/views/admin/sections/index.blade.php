@extends('layouts.appbar')

@section('content')
<div class="container">
    <h1>Sections</h1>

    <!-- Form to add a new section -->
    <div class="card">
        <div class="card-header">Add New Section</div>
        <div class="card-body">
            <form action="{{ route('sections.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="sectionNameInput">Section Name</label>
                    <input type="text" class="form-control" id="sectionNameInput" name="name" required>
                </div>
                <button type="submit" class="btn btn-primary mt-3">Save Section</button>
            </form>
        </div>
    </div>

    <!-- List of sections -->
    <div class="card my-4">
        <div class="card-header">Sections</div>
        <div class="card-body">
            <ul class="list-group">
                @forelse($sections as $section)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <strong>{{ $loop->iteration }}. </strong>
                            <strong>{{ $section->name }}</strong>
                        </div>
                        
                        <div>
                            <!-- Elements button (links to elements page for this section) -->
                            <a href="{{ route('sections.elements', $section->id) }}" class="btn btn-info btn-sm">Elements</a>

                            <!-- Edit button (opens edit form in a modal) -->
                            <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editSectionModal{{ $section->id }}">
                                Edit
                            </button>

                            <!-- Delete button (triggers delete action) -->
                            <form action="{{ route('sections.destroy', $section->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this section?');">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </li>

                    <!-- Edit Section Modal -->
                    <div class="modal fade" id="editSectionModal{{ $section->id }}" tabindex="-1" aria-labelledby="editSectionModalLabel{{ $section->id }}" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editSectionModalLabel{{ $section->id }}">Edit Section</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form action="{{ route('sections.update', $section->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="form-group">
                                            <label for="editSectionNameInput{{ $section->id }}">Section Name</label>
                                            <input type="text" class="form-control" id="editSectionNameInput{{ $section->id }}" name="name" value="{{ $section->name }}" required>
                                        </div>
                                        <button type="submit" class="btn btn-primary mt-3">Save Changes</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <li class="list-group-item">No sections available.</li>
                @endforelse
            </ul>
        </div>
    </div>
</div>
@endsection
