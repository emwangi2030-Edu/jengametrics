@extends('layouts.app')

@push('styles')
<style>
    .jm-settings-page {
        max-width: 1240px;
        margin: 0 auto;
    }
    .jm-settings-header {
        background: linear-gradient(135deg, #f6fbf7 0%, #eef7f1 100%);
        border: 1px solid #dce9df;
        border-radius: 16px;
        padding: 18px 20px;
    }
    .jm-settings-title {
        color: #0f5131;
        font-weight: 800;
        letter-spacing: -0.01em;
    }
    .jm-settings-grid {
        background: #f7faf8;
        border: 1px solid #dce9df;
        border-radius: 16px;
        padding: 20px;
    }
    .jm-settings-tabs-wrap {
        background: #ffffff;
        border: 1px solid #e1ebe4;
        border-radius: 14px;
        padding: 10px;
        margin-bottom: 14px;
    }
    .jm-settings-tabs .nav-link {
        color: #456355;
        border-radius: 10px;
        border: 1px solid transparent;
        font-weight: 700;
        font-size: 0.92rem;
    }
    .jm-settings-tabs .nav-link.active {
        background: #ebf5ee;
        color: #1b5e35;
        border-color: #d3e8d9;
    }
    .jm-settings-card {
        border: 1px solid #e1ebe4 !important;
        border-radius: 16px !important;
        box-shadow: 0 8px 20px rgba(24, 39, 28, 0.06) !important;
        height: 100%;
    }
    .jm-settings-card .card-title {
        color: #0f5131;
        font-weight: 700;
        font-size: 1rem;
    }
    .jm-settings-chip {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #ebf5ee;
        color: #1b5e35;
        border: 1px solid #d3e8d9;
        border-radius: 999px;
        padding: 5px 10px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .04em;
    }
    .jm-settings-list a {
        text-decoration: none;
    }
    .jm-settings-list li {
        border-bottom: 1px solid #edf3ef;
        padding: 8px 0;
    }
    .jm-settings-list li:last-child {
        border-bottom: 0;
        padding-bottom: 0;
    }
</style>
@endpush

@section('content')
<div class="container mt-4 jm-settings-page">
    <div class="row mb-4">
        <div class="col-12">
            <div class="jm-settings-header d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <div>
                    <h2 class="jm-settings-title mb-1">{{ __('Settings') }}</h2>
                    <p class="text-muted mb-0">{{ __('Configure account, users, permissions, projects, and module preferences from one place.') }}</p>
                </div>
                <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary" aria-label="Back" title="Back">
                    <span data-feather="arrow-left-circle"></span>
                </a>
            </div>
        </div>
    </div>

    <div class="jm-settings-grid">
        <div class="jm-settings-tabs-wrap">
            <ul class="nav nav-pills jm-settings-tabs flex-wrap gap-2" id="settings-tabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="tab-profile" data-bs-toggle="pill" data-bs-target="#pane-profile" type="button" role="tab">{{ __('Profile') }}</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="tab-users" data-bs-toggle="pill" data-bs-target="#pane-users" type="button" role="tab">{{ __('Users') }}</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="tab-project" data-bs-toggle="pill" data-bs-target="#pane-project" type="button" role="tab">{{ __('Project') }}</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="tab-permissions" data-bs-toggle="pill" data-bs-target="#pane-permissions" type="button" role="tab">{{ __('Permissions') }}</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="tab-modules" data-bs-toggle="pill" data-bs-target="#pane-modules" type="button" role="tab">{{ __('Modules') }}</button>
                </li>
            </ul>
        </div>

        <div class="tab-content" id="settings-tab-content">
            <div class="tab-pane fade show active" id="pane-profile" role="tabpanel" aria-labelledby="tab-profile">
                <div class="row g-4">
                    <div class="col-lg-6">
                        <div class="card jm-settings-card border-0">
                            <div class="card-body">
                                <h5 class="card-title mb-2">{{ __('Account & Security') }}</h5>
                                <p class="text-muted small mb-3">{{ __('Profile details, password, and personal preferences.') }}</p>
                                <div class="d-flex flex-wrap gap-2 mb-3">
                                    <span class="jm-settings-chip">{{ $user && $user->isSubAccount() ? __('Sub-Account') : __('Primary Account') }}</span>
                                </div>
                                <a href="{{ route('profile.edit') }}" class="btn btn-success">{{ __('Open Profile Settings') }}</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="card jm-settings-card border-0">
                            <div class="card-body">
                                <h5 class="card-title mb-2">{{ __('Session & Access') }}</h5>
                                <p class="text-muted small mb-3">{{ __('Use the account menu to sign out securely from this session.') }}</p>
                                <ul class="list-unstyled jm-settings-list mb-0">
                                    <li><a href="{{ route('dashboard') }}">{{ __('Return to Dashboard') }}</a></li>
                                    <li><a href="{{ route('reports') }}">{{ __('Open Reporting') }}</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="pane-users" role="tabpanel" aria-labelledby="tab-users">
                <div class="row g-4">
                    <div class="col-lg-8">
                        <div class="card jm-settings-card border-0">
                            <div class="card-body">
                                <h5 class="card-title mb-2">{{ __('Users & Team Management') }}</h5>
                                @if($user && $user->isSubAccount())
                                    <p class="text-muted small mb-3">{{ __('Team membership is managed by your primary account owner.') }}</p>
                                @else
                                    <p class="text-muted small mb-3"><strong>{{ $linkedUsersCount }}</strong> {{ __('linked user(s) currently managed from this account.') }}</p>
                                @endif
                                <a href="{{ route('sub_accounts.index') }}" class="btn btn-success">{{ __('Manage Users') }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="pane-project" role="tabpanel" aria-labelledby="tab-project">
                <div class="row g-4">
                    <div class="col-lg-8">
                        <div class="card jm-settings-card border-0">
                            <div class="card-body">
                                <h5 class="card-title mb-2">{{ __('Project Configuration') }}</h5>
                                <p class="text-muted small mb-3">{{ __('Project identity, metadata, budget, duration, and deletion controls.') }}</p>
                                @if($user && !$user->isSubAccount())
                                    <a href="{{ route('projects.settings') }}" class="btn btn-success">{{ __('Open Project Settings') }}</a>
                                @else
                                    <p class="text-muted small mb-0">{{ __('Project settings are managed by the primary account.') }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="pane-permissions" role="tabpanel" aria-labelledby="tab-permissions">
                <div class="row g-4">
                    <div class="col-lg-8">
                        <div class="card jm-settings-card border-0">
                            <div class="card-body">
                                <h5 class="card-title mb-2">{{ __('Roles & Permissions') }}</h5>
                                <p class="text-muted small mb-3">{{ __('Current permission model follows module-based access for BoQ/BoM, Materials, and Labour.') }}</p>
                                <ul class="list-unstyled mb-0 small">
                                    <li class="d-flex justify-content-between border-bottom py-2"><span>{{ __('BoQ / BoM') }}</span><strong>{{ $user && $user->can_manage_boq ? __('Write') : __('Read') }}</strong></li>
                                    <li class="d-flex justify-content-between border-bottom py-2"><span>{{ __('Materials') }}</span><strong>{{ $user && $user->can_manage_materials ? __('Write') : __('Read') }}</strong></li>
                                    <li class="d-flex justify-content-between py-2"><span>{{ __('Labour') }}</span><strong>{{ $user && $user->can_manage_labour ? __('Write') : __('Read') }}</strong></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="pane-modules" role="tabpanel" aria-labelledby="tab-modules">
                <div class="row g-4">
                    @if($user && $user->is_admin())
                        <div class="col-lg-6">
                            <div class="card jm-settings-card border-0">
                                <div class="card-body">
                                    <h5 class="card-title mb-2">{{ __('Module Administration') }}</h5>
                                    <p class="text-muted small mb-3">{{ __('Configure shared BoQ/BoM structures and material library content.') }}</p>
                                    <ul class="list-unstyled jm-settings-list mb-0">
                                        <li><a href="{{ route('sections.index') }}">{{ __('Sections & Elements') }}</a></li>
                                        <li><a href="{{ route('products.index') }}">{{ __('Materials Library') }}</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="col-lg-6">
                        <div class="card jm-settings-card border-0">
                            <div class="card-body">
                                <h5 class="card-title mb-2">{{ __('Navigation Shortcuts') }}</h5>
                                <p class="text-muted small mb-3">{{ __('Quick access to operational modules.') }}</p>
                                <ul class="list-unstyled jm-settings-list mb-0">
                                    <li><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
                                    <li><a href="{{ route('boq') }}">{{ __('Bills of Quantities') }}</a></li>
                                    <li><a href="{{ route('boms.index') }}">{{ __('Bills of Materials') }}</a></li>
                                    <li><a href="{{ route('reports') }}">{{ __('Reporting') }}</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

