@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
<style>
    .password-visibility-btn {
        background-color: #027333 !important;
        border-color: #027333 !important;
        color: #fff !important;
    }

    .password-visibility-btn:hover,
    .password-visibility-btn:focus,
    .password-visibility-btn:active {
        background-color: #02632c !important;
        border-color: #02632c !important;
        color: #fff !important;
    }

    .password-match-indicator {
        min-width: 42px;
        justify-content: center;
    }
</style>
@endpush

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
                <div>
                    <h2 class="jm-page-title">{{ __('Add Users') }}</h2>
                    <p class="text-muted mb-0">{{ __('Add and manage users linked to your account.') }}</p>
                </div>
                <button type="button" class="btn btn-success mt-3 mt-md-0" data-bs-toggle="modal" data-bs-target="#createSubAccountModal">
                    {{ __('Add User') }}
                </button>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-body">
                    @if($subAccounts->isEmpty())
                        <p class="text-muted mb-0">{{ __('No users added yet.') }}</p>
                    @else
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>{{ __('Name') }}</th>
                                        <th>{{ __('Email') }}</th>
                                        <th class="text-center">{{ __('BoQ/BoM') }}</th>
                                        <th class="text-center">{{ __('Materials') }}</th>
                                        <th class="text-center">{{ __('Labour') }}</th>
                                        <th class="text-end">{{ __('Actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($subAccounts as $subAccount)
                                        <tr>
                                            <td class="fw-semibold">{{ $subAccount->name }}</td>
                                            <td>{{ $subAccount->email }}</td>
                                            <td class="text-center">
                                                @if($subAccount->can_manage_boq)
                                                    <span class="badge border-0 p-0"><span class="text-success" aria-label="{{ __('Write') }}"><span data-feather="check"></span></span></span>
                                                @else
                                                    <span class="badge border-0 p-0"><span class="text-danger" aria-label="{{ __('Read') }}"><span data-feather="x"></span></span></span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if($subAccount->can_manage_materials)
                                                    <span class="badge border-0 p-0"><span class="text-success" aria-label="{{ __('Write') }}"><span data-feather="check"></span></span></span>
                                                @else
                                                    <span class="badge border-0 p-0"><span class="text-danger" aria-label="{{ __('Read') }}"><span data-feather="x"></span></span></span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if($subAccount->can_manage_labour)
                                                    <span class="badge border-0 p-0"><span class="text-success" aria-label="{{ __('Write') }}"><span data-feather="check"></span></span></span>
                                                @else
                                                    <span class="badge border-0 p-0"><span class="text-danger" aria-label="{{ __('Read') }}"><span data-feather="x"></span></span></span>
                                                @endif
                                            </td>
                                            <td class="text-end">
                                                <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#editSubAccountModal{{ $subAccount->id }}">
                                                    {{ __('Edit') }}
                                                </button>
                                                <form action="{{ route('sub_accounts.destroy', $subAccount) }}" method="POST" class="d-inline ms-2" data-confirm-message="{{ __('Remove this sub-account?') }}">
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
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="createSubAccountModal" tabindex="-1" aria-labelledby="createSubAccountModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('sub_accounts.store') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title text-success" id="createSubAccountModalLabel">{{ __('Add User') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('Close') }}"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label" for="sub-account-name">{{ __('Name') }}</label>
                        <input type="text" class="form-control" id="sub-account-name" name="name" required maxlength="255">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="sub-account-email">{{ __('Email') }}</label>
                        <input type="email" class="form-control" id="sub-account-email" name="email" required maxlength="255">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="sub-account-password">{{ __('Password') }}</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="sub-account-password" name="password" required minlength="8">
                            <button type="button" class="btn password-visibility-btn" id="togglePassword" tabindex="-1" aria-label="{{ __('Toggle password visibility') }}">
                                <span id="togglePasswordIcon" class="bi bi-eye"></span>
                            </button>
                            <span class="input-group-text password-match-indicator" id="subAccountPasswordMatchIndicator">
                                <span class="bi bi-dash-circle text-muted"></span>
                            </span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="sub-account-password-confirmation">{{ __('Confirm Password') }}</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="sub-account-password-confirmation" name="password_confirmation" required minlength="8">
                            <button type="button" class="btn password-visibility-btn" id="togglePasswordConfirmation" tabindex="-1" aria-label="{{ __('Toggle confirm password visibility') }}">
                                <span id="togglePasswordConfirmationIcon" class="bi bi-eye"></span>
                            </button>
                            <span class="input-group-text password-match-indicator" id="subAccountPasswordConfirmationMatchIndicator">
                                <span class="bi bi-dash-circle text-muted"></span>
                            </span>
                        </div>
                    </div>
                    <div class="mb-2 fw-semibold text-success">{{ __('Role Access') }}</div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="role-boq" name="can_manage_boq" value="1">
                        <label class="form-check-label" for="role-boq">{{ __('Manage BoQ and BoM') }}</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="role-materials" name="can_manage_materials" value="1">
                        <label class="form-check-label" for="role-materials">{{ __('Manage Materials') }}</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="role-labour" name="can_manage_labour" value="1">
                        <label class="form-check-label" for="role-labour">{{ __('Manage Labour') }}</label>
                    </div>
                    <small class="text-muted d-block mt-2">{{ __('Unchecked roles will remain read-only.') }}</small>

                    <div class="mt-4 mb-2 fw-semibold text-success">{{ __('Project Access') }}</div>
                    @if($projects->isEmpty())
                        <div class="alert alert-warning py-2 mb-0">
                            {{ __('No projects available. Create a project first to assign access.') }}
                        </div>
                    @else
                        @foreach($projects as $project)
                            <div class="form-check">
                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    id="project-access-{{ $project->id }}"
                                    name="projects[]"
                                    value="{{ $project->id }}"
                                >
                                <label class="form-check-label" for="project-access-{{ $project->id }}">
                                    {{ $project->name }}
                                    @if(!empty($project->project_uid))
                                        <small class="text-muted">({{ $project->project_uid }})</small>
                                    @endif
                                </label>
                            </div>
                        @endforeach
                        <small class="text-muted d-block mt-2">{{ __('Select one or more projects this user can access.') }}</small>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                    <button type="submit" class="btn btn-primary" id="createSubAccountSubmitBtn">{{ __('Create') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="toast-container position-fixed top-0 end-0 p-3 jm-toast-layer" id="subAccountPasswordToastContainer"></div>

@foreach($subAccounts as $subAccount)
    <div class="modal fade" id="editSubAccountModal{{ $subAccount->id }}" tabindex="-1" aria-labelledby="editSubAccountModalLabel{{ $subAccount->id }}" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="{{ route('sub_accounts.update', $subAccount) }}" data-edit-sub-account-form="1">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title" id="editSubAccountModalLabel{{ $subAccount->id }}">{{ __('Edit User') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('Close') }}"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label" for="edit-sub-account-name-{{ $subAccount->id }}">{{ __('Name') }}</label>
                            <input type="text" class="form-control" id="edit-sub-account-name-{{ $subAccount->id }}" name="name" value="{{ $subAccount->name }}" required maxlength="255">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="edit-sub-account-email-{{ $subAccount->id }}">{{ __('Email') }}</label>
                            <input type="email" class="form-control" id="edit-sub-account-email-{{ $subAccount->id }}" name="email" value="{{ $subAccount->email }}" required maxlength="255">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="edit-sub-account-password-{{ $subAccount->id }}">{{ __('New Password (optional)') }}</label>
                            <div class="input-group">
                                <input type="password" class="form-control edit-sub-password-input" id="edit-sub-account-password-{{ $subAccount->id }}" name="password" minlength="8">
                                <button
                                    type="button"
                                    class="btn password-visibility-btn edit-sub-password-toggle"
                                    data-input-id="edit-sub-account-password-{{ $subAccount->id }}"
                                    data-icon-id="edit-sub-account-password-icon-{{ $subAccount->id }}"
                                    tabindex="-1"
                                    aria-label="{{ __('Toggle password visibility') }}"
                                >
                                    <span id="edit-sub-account-password-icon-{{ $subAccount->id }}" class="bi bi-eye"></span>
                                </button>
                                <span class="input-group-text password-match-indicator edit-sub-password-indicator">
                                    <span class="bi bi-dash-circle text-muted"></span>
                                </span>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="edit-sub-account-password-confirmation-{{ $subAccount->id }}">{{ __('Confirm Password') }}</label>
                            <div class="input-group">
                                <input type="password" class="form-control edit-sub-password-confirmation-input" id="edit-sub-account-password-confirmation-{{ $subAccount->id }}" name="password_confirmation" minlength="8">
                                <button
                                    type="button"
                                    class="btn password-visibility-btn edit-sub-password-confirmation-toggle"
                                    data-input-id="edit-sub-account-password-confirmation-{{ $subAccount->id }}"
                                    data-icon-id="edit-sub-account-password-confirmation-icon-{{ $subAccount->id }}"
                                    tabindex="-1"
                                    aria-label="{{ __('Toggle confirm password visibility') }}"
                                >
                                    <span id="edit-sub-account-password-confirmation-icon-{{ $subAccount->id }}" class="bi bi-eye"></span>
                                </button>
                                <span class="input-group-text password-match-indicator edit-sub-password-confirmation-indicator">
                                    <span class="bi bi-dash-circle text-muted"></span>
                                </span>
                            </div>
                        </div>
                        <div class="mb-2 fw-semibold">{{ __('Role Access (Write)') }}</div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="edit-role-boq-{{ $subAccount->id }}" name="can_manage_boq" value="1" @checked($subAccount->can_manage_boq)>
                            <label class="form-check-label" for="edit-role-boq-{{ $subAccount->id }}">{{ __('Manage BoQ and BoM') }}</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="edit-role-materials-{{ $subAccount->id }}" name="can_manage_materials" value="1" @checked($subAccount->can_manage_materials)>
                            <label class="form-check-label" for="edit-role-materials-{{ $subAccount->id }}">{{ __('Manage Materials') }}</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="edit-role-labour-{{ $subAccount->id }}" name="can_manage_labour" value="1" @checked($subAccount->can_manage_labour)>
                            <label class="form-check-label" for="edit-role-labour-{{ $subAccount->id }}">{{ __('Manage Labour') }}</label>
                        </div>
                        <small class="text-muted d-block mt-2">{{ __('Unchecked roles will remain read-only.') }}</small>

                        <div class="mt-4 mb-2 fw-semibold">{{ __('Project Access') }}</div>
                        @if($projects->isEmpty())
                            <div class="alert alert-warning py-2 mb-0">
                                {{ __('No projects available. Create a project first to assign access.') }}
                            </div>
                        @else
                            @php
                                $assignedProjectIds = $subAccount->projects->pluck('id')->all();
                            @endphp
                            @foreach($projects as $project)
                                <div class="form-check">
                                    <input
                                        class="form-check-input"
                                        type="checkbox"
                                        id="edit-project-access-{{ $subAccount->id }}-{{ $project->id }}"
                                        name="projects[]"
                                        value="{{ $project->id }}"
                                        @checked(in_array($project->id, $assignedProjectIds))
                                    >
                                    <label class="form-check-label" for="edit-project-access-{{ $subAccount->id }}-{{ $project->id }}">
                                        {{ $project->name }}
                                        @if(!empty($project->project_uid))
                                            <small class="text-muted">({{ $project->project_uid }})</small>
                                        @endif
                                    </label>
                                </div>
                            @endforeach
                            <small class="text-muted d-block mt-2">{{ __('Select one or more projects this user can access.') }}</small>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                        <button type="submit" class="btn btn-primary edit-sub-account-submit-btn">{{ __('Save Changes') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const toggleBtn = document.getElementById('togglePassword');
        const pwdInput = document.getElementById('sub-account-password');
        const icon = document.getElementById('togglePasswordIcon');
        const confirmToggleBtn = document.getElementById('togglePasswordConfirmation');
        const confirmPwdInput = document.getElementById('sub-account-password-confirmation');
        const confirmIcon = document.getElementById('togglePasswordConfirmationIcon');
        const createSubAccountSubmitBtn = document.getElementById('createSubAccountSubmitBtn');
        const passwordMatchIndicator = document.getElementById('subAccountPasswordMatchIndicator');
        const passwordConfirmationMatchIndicator = document.getElementById('subAccountPasswordConfirmationMatchIndicator');
        const toastContainer = document.getElementById('subAccountPasswordToastContainer');
        let lastMatchState = 'neutral';

        function bindToggle(toggleButton, inputNode, iconNode) {
            if (!toggleButton || !inputNode || !iconNode) {
                return;
            }

            toggleButton.addEventListener('click', function () {
                const isHidden = inputNode.getAttribute('type') === 'password';
                inputNode.setAttribute('type', isHidden ? 'text' : 'password');
                iconNode.classList.toggle('bi-eye', !isHidden);
                iconNode.classList.toggle('bi-eye-slash', isHidden);
            });
        }

        function setIndicators(state) {
            const indicators = [passwordMatchIndicator, passwordConfirmationMatchIndicator];
            indicators.forEach(function (indicator) {
                if (!indicator) {
                    return;
                }

                let iconClass = 'bi-dash-circle text-muted';
                if (state === 'match') {
                    iconClass = 'bi-check-circle-fill text-success';
                } else if (state === 'mismatch') {
                    iconClass = 'bi-x-circle-fill text-danger';
                }

                indicator.innerHTML = '<span class="bi ' + iconClass + '"></span>';
            });
        }

        function showToast(state) {
            if (!toastContainer || typeof bootstrap === 'undefined') {
                return;
            }

            const isMatch = state === 'match';
            const toast = document.createElement('div');
            toast.className = 'toast align-items-center text-bg-' + (isMatch ? 'success' : 'danger') + ' border-0';
            toast.setAttribute('role', 'alert');
            toast.setAttribute('aria-live', 'assertive');
            toast.setAttribute('aria-atomic', 'true');
            toast.innerHTML =
                '<div class="d-flex">' +
                '<div class="toast-body">' + (isMatch ? 'Passwords match.' : 'Passwords do not match.') + '</div>' +
                '<button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>' +
                '</div>';
            toastContainer.appendChild(toast);

            const toastInstance = bootstrap.Toast.getOrCreateInstance(toast, { delay: 1800 });
            toast.addEventListener('hidden.bs.toast', function () {
                toast.remove();
            });
            toastInstance.show();
        }

        function updateMatchStatus(showToastOnChange) {
            if (!pwdInput || !confirmPwdInput || !createSubAccountSubmitBtn) {
                return;
            }

            const passwordValue = pwdInput.value;
            const confirmValue = confirmPwdInput.value;
            let state = 'neutral';

            if (passwordValue.length > 0 || confirmValue.length > 0) {
                state = passwordValue.length > 0 && confirmValue.length > 0 && passwordValue === confirmValue ? 'match' : 'mismatch';
            }

            setIndicators(state);
            createSubAccountSubmitBtn.disabled = state !== 'match';

            if (showToastOnChange && state !== 'neutral' && state !== lastMatchState) {
                showToast(state);
            }
            lastMatchState = state;
        }

        bindToggle(toggleBtn, pwdInput, icon);
        bindToggle(confirmToggleBtn, confirmPwdInput, confirmIcon);

        if (pwdInput && confirmPwdInput) {
            pwdInput.addEventListener('input', function () {
                updateMatchStatus(false);
            });
            confirmPwdInput.addEventListener('input', function () {
                updateMatchStatus(true);
            });
            updateMatchStatus(false);
        }

        document.querySelectorAll('form[data-edit-sub-account-form="1"]').forEach(function (form) {
            const editPasswordInput = form.querySelector('.edit-sub-password-input');
            const editConfirmInput = form.querySelector('.edit-sub-password-confirmation-input');
            const editPasswordIndicator = form.querySelector('.edit-sub-password-indicator');
            const editConfirmIndicator = form.querySelector('.edit-sub-password-confirmation-indicator');
            const editSubmitBtn = form.querySelector('.edit-sub-account-submit-btn');
            const editToggleBtn = form.querySelector('.edit-sub-password-toggle');
            const editConfirmToggleBtn = form.querySelector('.edit-sub-password-confirmation-toggle');
            const editToggleIcon = editToggleBtn ? document.getElementById(editToggleBtn.getAttribute('data-icon-id')) : null;
            const editConfirmToggleIcon = editConfirmToggleBtn ? document.getElementById(editConfirmToggleBtn.getAttribute('data-icon-id')) : null;
            let editLastMatchState = 'neutral';

            bindToggle(editToggleBtn, editPasswordInput, editToggleIcon);
            bindToggle(editConfirmToggleBtn, editConfirmInput, editConfirmToggleIcon);

            if (!editPasswordInput || !editConfirmInput || !editSubmitBtn) {
                return;
            }

            function setEditIndicators(state) {
                const indicators = [editPasswordIndicator, editConfirmIndicator];
                indicators.forEach(function (indicator) {
                    if (!indicator) {
                        return;
                    }

                    let iconClass = 'bi-dash-circle text-muted';
                    if (state === 'match') {
                        iconClass = 'bi-check-circle-fill text-success';
                    } else if (state === 'mismatch') {
                        iconClass = 'bi-x-circle-fill text-danger';
                    }
                    indicator.innerHTML = '<span class="bi ' + iconClass + '"></span>';
                });
            }

            function updateEditMatchStatus(showToastOnChange) {
                const passwordValue = editPasswordInput.value || '';
                const confirmValue = editConfirmInput.value || '';

                let state = 'neutral';
                let canSubmit = true;

                if (passwordValue.length === 0 && confirmValue.length === 0) {
                    state = 'neutral';
                    canSubmit = true;
                } else if (passwordValue.length > 0 && confirmValue.length > 0 && passwordValue === confirmValue) {
                    state = 'match';
                    canSubmit = true;
                } else {
                    state = 'mismatch';
                    canSubmit = false;
                }

                setEditIndicators(state);
                editSubmitBtn.disabled = !canSubmit;

                if (showToastOnChange && state !== 'neutral' && state !== editLastMatchState) {
                    showToast(state);
                }
                editLastMatchState = state;
            }

            editPasswordInput.addEventListener('input', function () {
                updateEditMatchStatus(false);
            });
            editConfirmInput.addEventListener('input', function () {
                updateEditMatchStatus(true);
            });

            form.addEventListener('submit', function (event) {
                updateEditMatchStatus(false);
                if (editSubmitBtn.disabled) {
                    event.preventDefault();
                    event.stopPropagation();
                }
            });

            updateEditMatchStatus(false);
        });
    });
</script>
@endpush
