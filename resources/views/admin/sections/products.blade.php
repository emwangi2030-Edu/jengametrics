@extends('layouts.appbar')

@section('content')
<div class="container">
    <h1>Materials Selected</h1>

    <ul class="list-group">
        @forelse($materials as $material)
            <li class="list-group-item">{{ $material->name }}</li>
        @empty
            <li class="list-group-item">No materials available</li>
        @endforelse
    </ul>
</div>
@endsection