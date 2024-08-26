@extends('layouts.appbar')

@section('content')
    <h1>Edit Class</h1>
    <form action="{{ route('classes.update', $class) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="name">Class Name</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $class->name) }}" required>
        </div>
        <div class="form-group">
            <label for="school_id">School</label>
            <select name="school_id" id="school_id" class="form-control" required>
                @foreach($schools as $school)
                    <option value="{{ $school->id }}" {{ $school->id == $class->school_id ? 'selected' : '' }}>
                        {{ $school->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
@endsection
