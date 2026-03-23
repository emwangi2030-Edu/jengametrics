@extends('layouts.app')

@php
    $photoUrl = null;
    $picturePath = auth()->user()->photo;

    if (!empty($picturePath)) {
        if (preg_match('/^https?:\/\//i', $picturePath)) {
            $photoUrl = $picturePath;
        } elseif (\Illuminate\Support\Str::startsWith($picturePath, ['storage/', '/storage/'])) {
            $photoUrl = asset($picturePath);
        } else {
            $photoUrl = asset('storage/' . ltrim($picturePath, '/'));
        }
    }
@endphp

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/cropperjs@1.6.1/dist/cropper.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        .jm-account-page {
            max-width: 1240px;
            margin: 0 auto;
        }
        .jm-account-header {
            background: linear-gradient(135deg, #f6fbf7 0%, #eef7f1 100%);
            border: 1px solid #dce9df;
            border-radius: 16px;
            padding: 18px 20px;
        }
        .jm-account-title {
            color: #0f5131;
            font-weight: 800;
            letter-spacing: -0.01em;
        }
        .profile-shell {
            background: #f7faf8;
            border-radius: 16px;
            border: 1px solid #dce9df;
            padding: 20px;
        }
        .jm-profile-card {
            border: 1px solid #e1ebe4 !important;
            border-radius: 16px !important;
            box-shadow: 0 8px 20px rgba(24, 39, 28, 0.06) !important;
        }
        .jm-profile-card .card-body {
            padding: 1.25rem 1.25rem 1.3rem;
        }
        .jm-section-title {
            color: #0f5131;
            font-size: 0.95rem;
            letter-spacing: 0.01em;
        }
        .profile-chip {
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
        .profile-avatar {
            border: 4px solid #ebf5ee;
            box-shadow: 0 8px 20px rgba(23, 77, 47, 0.12);
        }
        .jm-muted-note {
            color: #5e6c63;
        }
        .jm-danger-block {
            border: 1px solid #f1d5d8 !important;
            background: #fff8f8;
        }
        .password-match-indicator {
            min-width: 42px;
            justify-content: center;
        }
    </style>
@endpush

@section('content')
<div class="container mt-4 jm-account-page">
    <div class="row mb-4">
        <div class="col-12 d-flex flex-column flex-md-row justify-content-between align-items-md-center">
            <div class="jm-account-header w-100 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <div>
                    <h2 class="jm-account-title mb-1">{{ __('Profile Settings') }}</h2>
                    <p class="jm-muted-note mb-0">{{ __('Manage your account details and security settings.') }}</p>
                </div>
                <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary" aria-label="Back" title="Back">
                    <span data-feather="arrow-left-circle"></span>
                </a>
            </div>
        </div>
    </div>

    <div class="row g-4 profile-shell">
        <div class="col-lg-4">
            <div class="card jm-profile-card border-0">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <img
                            id="profile-photo-preview"
                            class="rounded-circle profile-avatar"
                            src="{{ $photoUrl ?? asset('assets/media/svg/avatars/blank.svg') }}"
                            alt="{{ auth()->user()->name }}"
                            width="120"
                            height="120"
                            class="jm-object-cover">
                    </div>
                    <h4 class="fw-bold mb-1" id="profile-display-name">{{ auth()->user()->name }}</h4>
                    <p class="text-muted mb-3" id="profile-display-email">{{ auth()->user()->email }}</p>
                    @if(!auth()->user()->isSubAccount())
                        <div class="d-flex flex-column align-items-center mb-3">
                            <span class="text-muted">{{ __('Linked Users') }}</span>
                            <a href="{{ route('sub_accounts.index') }}" class="fs-5 fw-bold text-primary text-decoration-underline" id="profile-linked-count">
                                {{ auth()->user()->subAccounts()->count() }}
                            </a>
                        </div>
                    @endif
                    <div class="d-flex justify-content-center gap-2">
                        <span class="profile-chip">{{ auth()->user()->isSubAccount() ? __('Sub-Account') : __('Primary Account') }}</span>
                    </div>
                </div>
            </div>

            @if(auth()->user()->isSubAccount())
                <div class="card jm-profile-card border-0 mt-4">
                    <div class="card-body">
                        <h5 class="fw-bold mb-3 jm-section-title">{{ __('Role Access') }}</h5>
                        <ul class="list-unstyled mb-0">
                            <li class="d-flex justify-content-between py-2 border-bottom">
                                <span>{{ __('Manage BoQ & BoM') }}</span>
                                <span class="badge border-0 p-0">
                                    @if(auth()->user()->can_manage_boq)
                                        <span class="text-success" aria-label="{{ __('Write') }}"><span data-feather="check"></span></span>
                                    @else
                                        <span class="text-danger" aria-label="{{ __('Read') }}"><span data-feather="x"></span></span>
                                    @endif
                                </span>
                            </li>
                            <li class="d-flex justify-content-between py-2 border-bottom">
                                <span>{{ __('Manage Materials') }}</span>
                                <span class="badge border-0 p-0">
                                    @if(auth()->user()->can_manage_materials)
                                        <span class="text-success" aria-label="{{ __('Write') }}"><span data-feather="check"></span></span>
                                    @else
                                        <span class="text-danger" aria-label="{{ __('Read') }}"><span data-feather="x"></span></span>
                                    @endif
                                </span>
                            </li>
                            <li class="d-flex justify-content-between py-2">
                                <span>{{ __('Manage Labour') }}</span>
                                <span class="badge border-0 p-0">
                                    @if(auth()->user()->can_manage_labour)
                                        <span class="text-success" aria-label="{{ __('Write') }}"><span data-feather="check"></span></span>
                                    @else
                                        <span class="text-danger" aria-label="{{ __('Read') }}"><span data-feather="x"></span></span>
                                    @endif
                                </span>
                            </li>
                        </ul>
                    </div>
                </div>
            @endif
        </div>

        <div class="col-lg-8">
            <div class="card jm-profile-card border-0 mb-4">
                <div class="card-body">
                    <h5 class="fw-bold mb-3 jm-section-title">{{ __('Account Details') }}</h5>
                    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" id="profile-details-form">
                        @csrf
                        @method('PATCH')
                        <div class="mb-3">
                            <label class="form-label" for="profile-photo">{{ __('Profile Photo') }}</label>
                            <input type="file" class="form-control" id="profile-photo" name="photo" accept="image/*">
                            @error('photo')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="profile-name">{{ __('Name') }}</label>
                            <input type="text" class="form-control" id="profile-name" name="name" value="{{ old('name', auth()->user()->name) }}" required maxlength="255">
                            @error('name')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="profile-email">{{ __('Email') }}</label>
                            <input type="email" class="form-control" id="profile-email" name="email" value="{{ old('email', auth()->user()->email) }}" required maxlength="255">
                            @error('email')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-success" id="profile-save-button">
                            {{ __('Save Changes') }}
                        </button>
                        <small class="text-muted ms-2" id="profile-save-status"></small>
                    </form>
                </div>
            </div>

            <div class="card jm-profile-card border-0 mb-4">
                <div class="card-body">
                    <h5 class="fw-bold mb-3 jm-section-title">{{ __('Security') }}</h5>
                    <form method="POST" action="{{ route('password.update') }}" id="password-update-form" novalidate>
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label class="form-label" for="current-password">{{ __('Current Password') }}</label>
                            <input type="password" class="form-control" id="current-password" name="current_password" autocomplete="current-password" required>
                            @error('current_password')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                            <div class="invalid-feedback" id="current-password-feedback"></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="new-password">{{ __('New Password') }}</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="new-password" name="password" autocomplete="new-password" minlength="8" required>
                                <span class="input-group-text password-match-indicator" id="accountPasswordMatchIndicator">
                                    <span class="bi bi-dash-circle text-muted"></span>
                                </span>
                            </div>
                            @error('password')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                            <div class="invalid-feedback" id="new-password-feedback"></div>
                            <small class="text-muted d-block mt-1">
                                {{ __('Password must be at least 8 characters and include uppercase, lowercase, number, and symbol.') }}
                            </small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="password-confirmation">{{ __('Confirm Password') }}</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="password-confirmation" name="password_confirmation" autocomplete="new-password" minlength="8" required>
                                <span class="input-group-text password-match-indicator" id="accountPasswordConfirmationMatchIndicator">
                                    <span class="bi bi-dash-circle text-muted"></span>
                                </span>
                            </div>
                            <div class="invalid-feedback" id="password-confirmation-feedback"></div>
                        </div>
                        <button type="submit" class="btn btn-success" id="password-update-submit">
                            {{ __('Update Password') }}
                        </button>
                    </form>
                </div>
            </div>

            @if(!auth()->user()->isSubAccount())
                <div class="card jm-profile-card jm-danger-block border-0">
                    <div class="card-body">
                        <h5 class="fw-bold mb-3 text-danger">{{ __('Delete Account') }}</h5>
                        <p class="text-muted">
                            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
                        </p>
                        <button type="button" class="btn btn-danger text-white" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
                            {{ __('Delete Account') }}
                        </button>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<div class="toast-container position-fixed top-0 end-0 p-3 jm-toast-layer" id="accountPasswordToastContainer"></div>

<div class="modal fade" id="profileCropperModal" tabindex="-1" aria-labelledby="profileCropperModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="profileCropperModalLabel">{{ __('Crop Profile Photo') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('Close') }}"></button>
            </div>
            <div class="modal-body">
                <div class="w-100 jm-max-h-60vh">
                    <img id="profile-cropper-image" src="" alt="{{ __('Crop image') }}" class="jm-max-w-full-block">
                </div>
                <p class="text-muted small mt-2 mb-0">{{ __('Drag to crop. Freeform cropping is enabled.') }}</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                <button type="button" class="btn btn-success" id="profile-cropper-apply">{{ __('Use Photo') }}</button>
            </div>
        </div>
    </div>
</div>

@if(!auth()->user()->isSubAccount())
    <div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-labelledby="deleteAccountModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="{{ route('profile.destroy') }}">
                    @csrf
                    @method('DELETE')
                    <div class="modal-header">
                        <h5 class="modal-title text-danger" id="deleteAccountModalLabel">{{ __('Delete Account') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('Close') }}"></button>
                    </div>
                    <div class="modal-body">
                        <p class="text-muted">
                            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
                        </p>
                        <div class="mb-3">
                            <label class="form-label" for="delete-password">{{ __('Password') }}</label>
                            <input type="password" class="form-control" id="delete-password" name="password" required>
                            @error('password')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                        <button type="submit" class="btn btn-danger text-white">{{ __('Delete Account') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/cropperjs@1.6.1/dist/cropper.min.js"></script>
    <script>
        window.addEventListener('load', function () {
            const profileForm = document.getElementById('profile-details-form');
            const profileNameInput = document.getElementById('profile-name');
            const profileEmailInput = document.getElementById('profile-email');
            const profilePhotoPreview = document.getElementById('profile-photo-preview');
            const profileDisplayName = document.getElementById('profile-display-name');
            const profileDisplayEmail = document.getElementById('profile-display-email');
            const profileLinkedCount = document.getElementById('profile-linked-count');
            const profileSaveStatus = document.getElementById('profile-save-status');
            const profileSaveButton = document.getElementById('profile-save-button');

            let apiToken = null;
            const getApiToken = async () => {
                if (apiToken) return apiToken;
                const response = await fetch('{{ route('dashboard.api-token') }}', {
                    method: 'GET',
                    credentials: 'include',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                if (!response.ok) return null;
                const payload = await response.json();
                apiToken = payload?.data?.token || null;
                return apiToken;
            };

            const hydrateProfile = async () => {
                if (!profileForm) return;
                const token = await getApiToken();
                if (!token) return;

                const response = await fetch('/api/v1/profile', {
                    method: 'GET',
                    credentials: 'include',
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': `Bearer ${token}`,
                    }
                });
                if (!response.ok) return;
                const payload = await response.json();
                const data = payload?.data || {};

                if (profileNameInput && data.name) profileNameInput.value = data.name;
                if (profileEmailInput && data.email) profileEmailInput.value = data.email;
                if (profileDisplayName && data.name) profileDisplayName.textContent = data.name;
                if (profileDisplayEmail && data.email) profileDisplayEmail.textContent = data.email;
                if (profilePhotoPreview && data.avatar_url) profilePhotoPreview.src = data.avatar_url;
                if (profileLinkedCount && typeof data.linked_users_count === 'number') {
                    profileLinkedCount.textContent = String(data.linked_users_count);
                }
            };

            if (profileForm) {
                profileForm.addEventListener('submit', async function (event) {
                    event.preventDefault();
                    const token = await getApiToken();
                    if (!token) {
                        if (profileSaveStatus) profileSaveStatus.textContent = 'Unable to authenticate request.';
                        return;
                    }

                    const formData = new FormData(profileForm);
                    formData.append('_method', 'PATCH');
                    if (profileSaveButton) profileSaveButton.disabled = true;
                    if (profileSaveStatus) profileSaveStatus.textContent = 'Saving...';

                    const response = await fetch('/api/v1/profile', {
                        method: 'POST',
                        credentials: 'include',
                        headers: {
                            'Accept': 'application/json',
                            'Authorization': `Bearer ${token}`,
                        },
                        body: formData,
                    });

                    if (profileSaveButton) profileSaveButton.disabled = false;

                    const payload = await response.json().catch(() => null);
                    if (!response.ok) {
                        if (profileSaveStatus) profileSaveStatus.textContent = payload?.error?.message || 'Save failed.';
                        return;
                    }

                    const data = payload?.data || {};
                    if (profileDisplayName && data.name) profileDisplayName.textContent = data.name;
                    if (profileDisplayEmail && data.email) profileDisplayEmail.textContent = data.email;
                    if (profilePhotoPreview && data.avatar_url) profilePhotoPreview.src = data.avatar_url;
                    if (profileSaveStatus) profileSaveStatus.textContent = payload?.message || 'Saved.';
                });
            }

            hydrateProfile();

            const fileInput = document.getElementById('profile-photo');
            const previewImage = document.getElementById('profile-photo-preview');
            const cropperModalEl = document.getElementById('profileCropperModal');
            const cropperImage = document.getElementById('profile-cropper-image');
            const applyButton = document.getElementById('profile-cropper-apply');

            if (fileInput && cropperModalEl && cropperImage && applyButton && window.bootstrap) {
                let cropper = null;
                let objectUrl = null;
                let applyingCrop = false;
                const modal = new bootstrap.Modal(cropperModalEl, { backdrop: 'static' });

                fileInput.addEventListener('change', function () {
                    const file = this.files && this.files[0];
                    if (!file) {
                        return;
                    }

                    if (objectUrl) {
                        URL.revokeObjectURL(objectUrl);
                    }
                    objectUrl = URL.createObjectURL(file);
                    cropperImage.src = objectUrl;
                    applyingCrop = false;
                    modal.show();
                });

                cropperModalEl.addEventListener('shown.bs.modal', function () {
                    if (cropper) {
                        cropper.destroy();
                    }
                    cropper = new Cropper(cropperImage, {
                        viewMode: 1,
                        autoCropArea: 0.9,
                        dragMode: 'move',
                        responsive: true,
                        movable: true,
                        zoomable: true,
                        rotatable: false,
                        scalable: false
                    });
                });

                cropperModalEl.addEventListener('hidden.bs.modal', function () {
                    if (cropper) {
                        cropper.destroy();
                        cropper = null;
                    }
                    if (objectUrl) {
                        URL.revokeObjectURL(objectUrl);
                        objectUrl = null;
                    }
                    if (!applyingCrop) {
                        fileInput.value = '';
                    }
                });

                applyButton.addEventListener('click', function () {
                    if (!cropper) {
                        return;
                    }

                    cropper.getCroppedCanvas({
                        imageSmoothingQuality: 'high'
                    }).toBlob(function (blob) {
                        if (!blob) {
                            return;
                        }

                        const file = new File([blob], 'profile-photo.jpg', { type: 'image/jpeg', lastModified: Date.now() });
                        const dataTransfer = new DataTransfer();
                        dataTransfer.items.add(file);
                        fileInput.files = dataTransfer.files;

                        const newPreviewUrl = URL.createObjectURL(file);
                        previewImage.src = newPreviewUrl;

                        applyingCrop = true;
                        modal.hide();
                    }, 'image/jpeg', 0.9);
                });
            }

            const passwordForm = document.getElementById('password-update-form');
            const currentPasswordInput = document.getElementById('current-password');
            const newPasswordInput = document.getElementById('new-password');
            const confirmPasswordInput = document.getElementById('password-confirmation');
            const passwordSubmit = document.getElementById('password-update-submit');

            if (!passwordForm || !currentPasswordInput || !newPasswordInput || !confirmPasswordInput || !passwordSubmit) {
                return;
            }

            const currentPasswordFeedback = document.getElementById('current-password-feedback');
            const newPasswordFeedback = document.getElementById('new-password-feedback');
            const confirmPasswordFeedback = document.getElementById('password-confirmation-feedback');
            const passwordMatchIndicator = document.getElementById('accountPasswordMatchIndicator');
            const passwordConfirmationMatchIndicator = document.getElementById('accountPasswordConfirmationMatchIndicator');
            const toastContainer = document.getElementById('accountPasswordToastContainer');
            let lastMatchState = 'neutral';

            const meetsPasswordPolicy = (value) => {
                return /[a-z]/.test(value)
                    && /[A-Z]/.test(value)
                    && /\d/.test(value)
                    && /[^A-Za-z0-9]/.test(value)
                    && value.length >= 8;
            };

            const setFieldState = (input, feedbackNode, isValid, message) => {
                input.classList.remove('is-valid', 'is-invalid');
                if (isValid) {
                    input.classList.add('is-valid');
                    if (feedbackNode) {
                        feedbackNode.textContent = '';
                    }
                } else {
                    input.classList.add('is-invalid');
                    if (feedbackNode) {
                        feedbackNode.textContent = message || '';
                    }
                }
            };

            const setMatchIndicators = (state) => {
                const indicators = [passwordMatchIndicator, passwordConfirmationMatchIndicator];
                indicators.forEach((indicator) => {
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
            };

            const showMatchToast = (state) => {
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
            };

            const validatePasswordForm = (showToastOnChange = false) => {
                const currentPassword = currentPasswordInput.value || '';
                const newPassword = newPasswordInput.value || '';
                const confirmPassword = confirmPasswordInput.value || '';
                let matchState = 'neutral';

                let isFormValid = true;

                if (!currentPassword.trim()) {
                    setFieldState(currentPasswordInput, currentPasswordFeedback, false, '{{ __('Current password is required.') }}');
                    isFormValid = false;
                } else {
                    setFieldState(currentPasswordInput, currentPasswordFeedback, true, '');
                }

                if (!newPassword) {
                    setFieldState(newPasswordInput, newPasswordFeedback, false, '{{ __('New password is required.') }}');
                    isFormValid = false;
                } else if (!meetsPasswordPolicy(newPassword)) {
                    setFieldState(newPasswordInput, newPasswordFeedback, false, '{{ __('Use at least 8 characters with uppercase, lowercase, number, and symbol.') }}');
                    isFormValid = false;
                } else if (newPassword === currentPassword) {
                    setFieldState(newPasswordInput, newPasswordFeedback, false, '{{ __('New password must be different from current password.') }}');
                    isFormValid = false;
                } else {
                    setFieldState(newPasswordInput, newPasswordFeedback, true, '');
                }

                if (!confirmPassword) {
                    setFieldState(confirmPasswordInput, confirmPasswordFeedback, false, '{{ __('Please confirm your new password.') }}');
                    isFormValid = false;
                    if (newPassword) {
                        matchState = 'mismatch';
                    }
                } else if (confirmPassword !== newPassword) {
                    setFieldState(confirmPasswordInput, confirmPasswordFeedback, false, '{{ __('Password confirmation does not match.') }}');
                    isFormValid = false;
                    matchState = 'mismatch';
                } else {
                    setFieldState(confirmPasswordInput, confirmPasswordFeedback, true, '');
                    if (newPassword) {
                        matchState = 'match';
                    }
                }

                setMatchIndicators(matchState);
                passwordSubmit.disabled = !isFormValid;

                if (showToastOnChange && matchState !== 'neutral' && matchState !== lastMatchState) {
                    showMatchToast(matchState);
                }
                lastMatchState = matchState;
                return isFormValid;
            };

            currentPasswordInput.addEventListener('input', function () {
                validatePasswordForm(false);
            });
            newPasswordInput.addEventListener('input', function () {
                validatePasswordForm(false);
            });
            confirmPasswordInput.addEventListener('input', function () {
                validatePasswordForm(true);
            });

            passwordForm.addEventListener('submit', function (event) {
                if (!validatePasswordForm(false)) {
                    event.preventDefault();
                    event.stopPropagation();
                    return;
                }

                event.preventDefault();
                const submitPasswordUpdate = async () => {
                    const token = await getApiToken();
                    if (!token) {
                        if (newPasswordFeedback) {
                            newPasswordFeedback.textContent = 'Unable to authenticate request.';
                        }
                        return;
                    }

                    passwordSubmit.disabled = true;
                    const response = await fetch('/api/v1/profile/password', {
                        method: 'POST',
                        credentials: 'include',
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                            'Authorization': `Bearer ${token}`,
                        },
                        body: JSON.stringify({
                            current_password: currentPasswordInput.value,
                            password: newPasswordInput.value,
                            password_confirmation: confirmPasswordInput.value,
                        }),
                    });

                    const payload = await response.json().catch(() => null);
                    passwordSubmit.disabled = false;

                    if (!response.ok) {
                        const details = payload?.error?.details || {};
                        if (details.current_password?.[0]) {
                            setFieldState(currentPasswordInput, currentPasswordFeedback, false, details.current_password[0]);
                        }
                        if (details.password?.[0]) {
                            setFieldState(newPasswordInput, newPasswordFeedback, false, details.password[0]);
                        }
                        return;
                    }

                    currentPasswordInput.value = '';
                    newPasswordInput.value = '';
                    confirmPasswordInput.value = '';
                    validatePasswordForm(false);
                    showMatchToast('match');
                };

                submitPasswordUpdate();
            });

            validatePasswordForm();
        });
    </script>
@endpush
