<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Background Styling */
        body {
            background: linear-gradient(to right, #4CAF50, #2E8B57); /* Your theme green */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        /* Glassmorphism Card */
        .glass-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            max-width: 450px;
            width: 100%;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        /* Form Input Styling */
        .form-control {
            border-radius: 8px;
            border: 1px solid rgba(255, 255, 255, 0.5);
            background: rgba(255, 255, 255, 0.2);
            color: #fff;
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }

        .form-control:focus {
            background: rgba(255, 255, 255, 0.3);
            border-color: #fff;
            box-shadow: none;
            color: #fff;
        }

        /* Button Styling */
        .btn-modern {
            background: rgba(255, 255, 255, 0.2);
            border: none;
            color: white;
            font-weight: bold;
            padding: 12px;
            border-radius: 8px;
            transition: all 0.3s ease-in-out;
            width: 100%;
        }

        .btn-modern:hover {
            background: rgba(255, 255, 255, 0.4);
            color: #2E8B57;
        }

        /* Link Styling */
        .text-light a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: 0.3s;
        }

        .text-light a:hover {
            color: white;
        }

        .password-match-indicator {
            min-width: 42px;
            justify-content: center;
            border-left: 0;
        }

    </style>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>
<body>
    <div class="glass-card">
        <!-- Header -->
        <h2 class="text-center text-white mb-4">Register</h2>

        <!-- Form -->
        <form method="POST" action="{{ route('register') }}">
            @csrf

            <!-- Name -->
            <div class="mb-3">
                <label for="name" class="form-label text-white">{{ __('Name') }}</label>
                <input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}" required autofocus autocomplete="name">
                @if ($errors->has('name'))
                    <div class="text-danger mt-1">
                        {{ $errors->first('name') }}
                    </div>
                @endif
            </div>

            <!-- Email -->
            <div class="mb-3">
                <label for="email" class="form-label text-white">{{ __('Email') }}</label>
                <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}" required autocomplete="username">
                @if ($errors->has('email'))
                    <div class="text-danger mt-1">
                        {{ $errors->first('email') }}
                    </div>
                @endif
            </div>

            <!-- Password -->
            <div class="mb-3">
                <label for="password" class="form-label text-white">{{ __('Password') }}</label>
                <div class="input-group">
                    <input type="password" id="password" name="password" class="form-control" required autocomplete="new-password">
                    <button type="button" class="btn btn-outline-light" id="togglePassword" tabindex="-1">
                        <span id="togglePasswordIcon" class="bi bi-eye"></span>
                    </button>
                    <span class="input-group-text password-match-indicator" id="passwordMatchIndicator">
                        <span class="bi bi-dash-circle text-muted"></span>
                    </span>
                </div>
                @if ($errors->has('password'))
                    <div class="text-danger mt-1">
                        {{ $errors->first('password') }}
                    </div>
                @endif
            </div>

            <!-- Confirm Password -->
            <div class="mb-3">
                <label for="password_confirmation" class="form-label text-white">{{ __('Confirm Password') }}</label>
                <div class="input-group">
                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required autocomplete="new-password">
                    <button type="button" class="btn btn-outline-light" id="togglePasswordConfirmation" tabindex="-1">
                        <span id="togglePasswordConfirmationIcon" class="bi bi-eye"></span>
                    </button>
                    <span class="input-group-text password-match-indicator" id="passwordConfirmationMatchIndicator">
                        <span class="bi bi-dash-circle text-muted"></span>
                    </span>
                </div>
                @if ($errors->has('password_confirmation'))
                    <div class="text-danger mt-1">
                        {{ $errors->first('password_confirmation') }}
                    </div>
                @endif
            </div>

            <div class="d-flex justify-content-between align-items-center text-light">
                <a href="{{ route('login') }}">{{ __('Already registered?') }}</a>
            </div>

            <button type="submit" class="btn btn-modern mt-4" id="registerSubmitBtn">{{ __('Register') }}</button>
        </form>
    </div>

    <div class="toast-container position-fixed top-0 end-0 p-3" id="passwordToastContainer" style="z-index: 1080;"></div>

    <!-- Bootstrap 5 JS Bundle (including Popper) -->
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
