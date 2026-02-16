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
@endpush

@section('content')
<div class="container mt-4">
    <div class="row mb-4">
        <div class="col-12 d-flex flex-column flex-md-row justify-content-between align-items-md-center">
            <div>
                <h2 class="fw-bold mb-1" style="color:#027333;">{{ __('Profile') }}</h2>
                <p class="text-muted mb-0">{{ __('Manage your account details and security settings.') }}</p>
            </div>
            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary mt-3 mt-md-0">{{ __('Back') }}</a>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <img
                            id="profile-photo-preview"
                            class="rounded-circle"
                            src="{{ $photoUrl ?? asset('assets/media/svg/avatars/blank.svg') }}"
                            alt="{{ auth()->user()->name }}"
                            width="120"
                            height="120"
                            style="object-fit: cover;">
                    </div>
                    <h4 class="fw-bold mb-1">{{ auth()->user()->name }}</h4>
                    <p class="text-muted mb-3">{{ auth()->user()->email }}</p>
                    @if(!auth()->user()->isSubAccount())
                        <div class="d-flex flex-column align-items-center mb-3">
                            <span class="text-muted">{{ __('Linked Users') }}</span>
                            <a href="{{ route('sub_accounts.index') }}" class="fs-5 fw-bold text-primary text-decoration-underline">
                                {{ auth()->user()->subAccounts()->count() }}
                            </a>
                        </div>
                    @endif
                    <div class="d-flex justify-content-center gap-2">
                        <span class="badge bg-success">{{ auth()->user()->isSubAccount() ? __('Sub-Account') : __('Primary Account') }}</span>
                        @if(auth()->user()->isSubAccount())
                            <span class="badge bg-success">{{ __('Role Access') }}</span>
                        @endif
                    </div>
                </div>
            </div>

            @if(auth()->user()->isSubAccount())
                <div class="card shadow-sm border-0 mt-4">
                    <div class="card-body">
                        <h5 class="fw-bold mb-3" style="color:#027333;">{{ __('Role Access') }}</h5>
                        <ul class="list-unstyled mb-0">
                            <li class="d-flex justify-content-between py-2 border-bottom">
                                <span>{{ __('Manage BoQ & BoM') }}</span>
                                <span class="badge {{ auth()->user()->can_manage_boq ? 'bg-success' : 'bg-secondary' }}">
                                    {{ auth()->user()->can_manage_boq ? __('Write') : __('Read') }}
                                </span>
                            </li>
                            <li class="d-flex justify-content-between py-2 border-bottom">
                                <span>{{ __('Manage Materials') }}</span>
                                <span class="badge {{ auth()->user()->can_manage_materials ? 'bg-success' : 'bg-secondary' }}">
                                    {{ auth()->user()->can_manage_materials ? __('Write') : __('Read') }}
                                </span>
                            </li>
                            <li class="d-flex justify-content-between py-2">
                                <span>{{ __('Manage Labour') }}</span>
                                <span class="badge {{ auth()->user()->can_manage_labour ? 'bg-success' : 'bg-secondary' }}">
                                    {{ auth()->user()->can_manage_labour ? __('Write') : __('Read') }}
                                </span>
                            </li>
                        </ul>
                    </div>
                </div>
            @endif
        </div>

        <div class="col-lg-8">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <h5 class="fw-bold mb-3" style="color:#027333;">{{ __('Account Details') }}</h5>
                    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
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
                        <button type="submit" class="btn btn-success">
                            {{ __('Save Changes') }}
                        </button>
                    </form>
                </div>
            </div>

            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <h5 class="fw-bold mb-3" style="color:#027333;">{{ __('Security') }}</h5>
                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label class="form-label" for="current-password">{{ __('Current Password') }}</label>
                            <input type="password" class="form-control" id="current-password" name="current_password" autocomplete="current-password">
                            @error('current_password')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="new-password">{{ __('New Password') }}</label>
                            <input type="password" class="form-control" id="new-password" name="password" autocomplete="new-password">
                            @error('password')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="password-confirmation">{{ __('Confirm Password') }}</label>
                            <input type="password" class="form-control" id="password-confirmation" name="password_confirmation" autocomplete="new-password">
                        </div>
                        <button type="submit" class="btn btn-success">
                            {{ __('Update Password') }}
                        </button>
                    </form>
                </div>
            </div>

            <div class="card shadow-sm border-0">
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
        </div>
    </div>
</div>

<div class="modal fade" id="profileCropperModal" tabindex="-1" aria-labelledby="profileCropperModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="profileCropperModalLabel">{{ __('Crop Profile Photo') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('Close') }}"></button>
            </div>
            <div class="modal-body">
                <div class="w-100" style="max-height:60vh;">
                    <img id="profile-cropper-image" src="" alt="{{ __('Crop image') }}" style="max-width:100%; display:block;">
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
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/cropperjs@1.6.1/dist/cropper.min.js"></script>
    <script>
        window.addEventListener('load', function () {
            const fileInput = document.getElementById('profile-photo');
            const previewImage = document.getElementById('profile-photo-preview');
            const cropperModalEl = document.getElementById('profileCropperModal');
            const cropperImage = document.getElementById('profile-cropper-image');
            const applyButton = document.getElementById('profile-cropper-apply');

            if (!fileInput || !cropperModalEl || !cropperImage || !applyButton || !window.bootstrap) {
                return;
            }

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
        });
    </script>
@endpush
