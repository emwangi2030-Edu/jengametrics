@extends('layouts.appbar')

@section('content')
<div class="container mt-5">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert" id="success-alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">{{ __('BOMs') }}</h1>
        <a href="{{ route('boms.create') }}" class="btn btn-primary">{{ __('Create New Stage') }}</a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-bordered">
                    <thead class="thead-light">
                        <tr>
                            <th>{{ __('BOM Name') }}</th>
                            <th class="text-center">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($boms as $bom)
                            <tr>
                                <td class="align-middle"><span class="ml-4">{{ $bom->bom_name }}</span></td>
                                <td class="text-center">
                                    <a href="{{ route('boms.show', $bom->id) }}" class="btn btn-info btn-sm">{{ __('View') }}</a>
                                    <form action="{{ route('boms.destroy', $bom->id) }}" method="POST" class="d-inline-block" onsubmit="return confirm('{{ __('Are you sure you want to delete this BOM?') }}');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">{{ __('Delete') }}</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

<script>
    $(document).ready(function() {
        setTimeout(function() {
            if ($('#success-alert').length) {
                setTimeout(function() {
                    $('#success-alert').fadeOut('slow');
                }, 4000);
            }
        }, 1000); 
    });
</script>
