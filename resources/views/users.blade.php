@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="jm-page-header">
        <div>
            <h2 class="jm-page-title jm-ui-title">{{ __('Users') }}</h2>
            <p class="jm-page-subtitle jm-ui-muted mb-0">{{ __('Manage account users and access controls.') }}</p>
        </div>
        <a href="{{ route('settings.index') }}" class="btn btn-outline-secondary" aria-label="Back" title="Back"><span data-feather="arrow-left-circle"></span></a>
    </div>

    <div class="card jm-ui-card shadow-sm border-0">
        <div class="card-body">
            <p class="mb-3">{{ __('Use the user management module to add and edit users linked to your account.') }}</p>
            <a href="{{ route('sub_accounts.index') }}" class="btn btn-primary">{{ __('Open User Management') }}</a>
        </div>
    </div>
</div>
@endsection
