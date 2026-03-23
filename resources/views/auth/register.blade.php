<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/metrics/assets/css/auth-refresh.css') }}">
    <style>.password-match-indicator { min-width: 42px; justify-content: center; border-left: 0; }</style>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>
<body class="jm-auth">
    <div class="jm-auth-shell">
        <div class="jm-auth-brand">
            <div class="jm-auth-logo-row">
                <div class="jm-auth-logo-mark">🏗️</div>
                <div class="jm-auth-logo-text">Jenga<span>Metrics</span></div>
            </div>
            <h1>Create your workspace</h1>
            <p>Set up your account to track BoQ, BoM, labour, material movement, and project performance in one place.</p>
            <div class="jm-auth-brand-meta">Staging Environment</div>
        </div>

        <div class="jm-auth-card">
            <h2 class="jm-auth-title">Create Account</h2>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="mb-3">
                    <label for="name" class="jm-auth-label">{{ __('Name') }}</label>
                    <input type="text" id="name" name="name" class="form-control jm-auth-input" value="{{ old('name') }}" required autofocus autocomplete="name">
                    @if ($errors->has('name'))
                        <div class="text-danger mt-1">{{ $errors->first('name') }}</div>
                    @endif
                </div>

                <div class="mb-3">
                    <label for="email" class="jm-auth-label">{{ __('Email') }}</label>
                    <input type="email" id="email" name="email" class="form-control jm-auth-input" value="{{ old('email') }}" required autocomplete="username">
                    @if ($errors->has('email'))
                        <div class="text-danger mt-1">{{ $errors->first('email') }}</div>
                    @endif
                </div>

                <div class="mb-3">
                    <label for="password" class="jm-auth-label">{{ __('Password') }}</label>
                    <div class="input-group">
                        <input type="password" id="password" name="password" class="form-control jm-auth-input" required autocomplete="new-password">
                        <button type="button" class="btn btn-outline-light" id="togglePassword" tabindex="-1">
                            <span id="togglePasswordIcon" class="bi bi-eye"></span>
                        </button>
                        <span class="input-group-text password-match-indicator" id="passwordMatchIndicator">
                            <span class="bi bi-dash-circle text-muted"></span>
                        </span>
                    </div>
                    @if ($errors->has('password'))
                        <div class="text-danger mt-1">{{ $errors->first('password') }}</div>
                    @endif
                </div>

                <div class="mb-3">
                    <label for="password_confirmation" class="jm-auth-label">{{ __('Confirm Password') }}</label>
                    <div class="input-group">
                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-control jm-auth-input" required autocomplete="new-password">
                        <button type="button" class="btn btn-outline-light" id="togglePasswordConfirmation" tabindex="-1">
                            <span id="togglePasswordConfirmationIcon" class="bi bi-eye"></span>
                        </button>
                        <span class="input-group-text password-match-indicator" id="passwordConfirmationMatchIndicator">
                            <span class="bi bi-dash-circle text-muted"></span>
                        </span>
                    </div>
                    @if ($errors->has('password_confirmation'))
                        <div class="text-danger mt-1">{{ $errors->first('password_confirmation') }}</div>
                    @endif
                </div>

                <div class="d-flex justify-content-between align-items-center jm-auth-muted">
                    <a class="jm-auth-link" href="{{ route('login') }}">{{ __('Already registered?') }}</a>
                </div>

                <button type="submit" class="btn jm-auth-btn mt-4" id="registerSubmitBtn">{{ __('Register') }}</button>
            </form>
        </div>
    </div>

    <div class="toast-container position-fixed top-0 end-0 p-3 jm-toast-layer" id="passwordToastContainer"></div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const toggleBtn = document.getElementById('togglePassword');
            const pwdInput = document.getElementById('password');
            const icon = document.getElementById('togglePasswordIcon');
            const toggleConfirmBtn = document.getElementById('togglePasswordConfirmation');
            const confirmPwdInput = document.getElementById('password_confirmation');
            const confirmIcon = document.getElementById('togglePasswordConfirmationIcon');
            const registerSubmitBtn = document.getElementById('registerSubmitBtn');
            const passwordMatchIndicator = document.getElementById('passwordMatchIndicator');
            const passwordConfirmationMatchIndicator = document.getElementById('passwordConfirmationMatchIndicator');
            const toastContainer = document.getElementById('passwordToastContainer');
            let lastMatchState = 'neutral';

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
                if (!pwdInput || !confirmPwdInput || !registerSubmitBtn) {
                    return;
                }

                const passwordValue = pwdInput.value;
                const confirmValue = confirmPwdInput.value;
                let state = 'neutral';

                if (passwordValue.length > 0 || confirmValue.length > 0) {
                    state = passwordValue.length > 0 && confirmValue.length > 0 && passwordValue === confirmValue ? 'match' : 'mismatch';
                }

                setIndicators(state);
                registerSubmitBtn.disabled = state !== 'match';

                if (showToastOnChange && state !== 'neutral' && state !== lastMatchState) {
                    showToast(state);
                }
                lastMatchState = state;
            }

            if (toggleBtn && pwdInput && icon) {
                toggleBtn.addEventListener('click', function () {
                    const isHidden = pwdInput.getAttribute('type') === 'password';
                    pwdInput.setAttribute('type', isHidden ? 'text' : 'password');
                    icon.classList.toggle('bi-eye', !isHidden);
                    icon.classList.toggle('bi-eye-slash', isHidden);
                });
            }

            if (toggleConfirmBtn && confirmPwdInput && confirmIcon) {
                toggleConfirmBtn.addEventListener('click', function () {
                    const isHidden = confirmPwdInput.getAttribute('type') === 'password';
                    confirmPwdInput.setAttribute('type', isHidden ? 'text' : 'password');
                    confirmIcon.classList.toggle('bi-eye', !isHidden);
                    confirmIcon.classList.toggle('bi-eye-slash', isHidden);
                });
            }

            if (pwdInput && confirmPwdInput) {
                pwdInput.addEventListener('input', function () {
                    updateMatchStatus(false);
                });
                confirmPwdInput.addEventListener('input', function () {
                    updateMatchStatus(true);
                });
                updateMatchStatus(false);
            }
        });
    </script>
</body>
</html>
