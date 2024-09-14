@extends('layouts.appbar')

@section('content')
<div class="container">
    @if(session('success'))
        <div class="alert alert-success" id="success-alert" style="display: block;">
            {{ session('success') }}
        </div>
    @endif
    
    <h1>BOMs</h1>

    <a href="{{ route('boms.create') }}" class="btn btn-primary mb-3">Create New Stage</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>BOM Name</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($boms as $bom)
                <tr>
                    <td>{{ $bom->bom_name }}</td>
                    <td>
                        <a href="{{ route('boms.show', $bom->id) }}" class="btn btn-primary btn-sm">View</a>
                        <form action="{{ route('boms.destroy', $bom->id) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

<script>
    $(document).ready(function() {
        setTimeout(function() {
            if ($('#success-alert').length) {
                console.log('Success alert found. It will fade out in 4 seconds.');
                setTimeout(function() {
                    $('#success-alert').fadeOut('slow');
                }, 4000);
            } else {
                console.log('No success alert found.');
            }
        }, 1000); // Add a slight delay before checking for the alert
    });
</script>
