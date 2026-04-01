<!DOCTYPE html>
<html lang="en">
@include("auth.partials.head", ["title" => "Login"])
<body class="jm-auth">
    <div class="jm-auth-shell">
        <div class="jm-auth-brand">
            <div class="jm-auth-logo-row">
                <div class="jm-auth-logo-mark">🏗️</div>
                <div class="jm-auth-logo-text">Jenga<span>Metrics</span></div>
            </div>
            <h1>Build Smarter, Deliver Faster</h1>
            <p>Track BoQ, BoM, labour, material flow and project cost performance from one control center.</p>
            @include("auth.partials.environment-badge")
        </div>

        <div class="jm-auth-card">
            <h2 class="jm-auth-title">Welcome Back</h2>

            @if (session('status'))
                <div class="alert alert-success text-center" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-3">
                    <label for="email" class="jm-auth-label">{{ __('Email') }}</label>
                    <input type="email" id="email" name="email" class="form-control jm-auth-input" value="{{ old('email') }}" required autofocus autocomplete="username">
                    @if ($errors->has('email'))
                        <div class="text-danger mt-1">
                            {{ $errors->first('email') }}
                        </div>
                    @endif
                </div>

                <div class="mb-3">
                    <label for="password" class="jm-auth-label">{{ __('Password') }}</label>
                    <div class="input-group">
                        <input type="password" id="password" name="password" class="form-control jm-auth-input" required autocomplete="current-password">
                        <button type="button" class="btn btn-outline-light" id="togglePassword" tabindex="-1">
                            <span id="togglePasswordIcon" class="bi bi-eye"></span>
                        </button>
                    </div>
                    @if ($errors->has('password'))
                        <div class="text-danger mt-1">
                            {{ $errors->first('password') }}
                        </div>
                    @endif
                </div>

                <div class="form-check mb-3">
                    <input type="checkbox" class="form-check-input" id="remember_me" name="remember">
                    <label for="remember_me" class="form-check-label jm-auth-muted">
                        {{ __('Remember me') }}
                    </label>
                </div>

                <div class="d-flex justify-content-between align-items-center jm-auth-muted">
                    @if (Route::has('password.request'))
                        <a class="jm-auth-link" href="{{ route('password.request') }}">{{ __('Forgot your password?') }}</a>
                    @endif
                </div>

                <button type="submit" class="btn jm-auth-btn mt-4">{{ __('Log in') }}</button>

                <p class="text-center mt-3 jm-auth-muted">
                    {{ __("Don't have an account?") }}
                    <a href="{{ route('register') }}" class="jm-auth-link fw-bold">Register</a>
                </p>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const toggleBtn = document.getElementById('togglePassword');
            const pwdInput = document.getElementById('password');
            const icon = document.getElementById('togglePasswordIcon');

            if (toggleBtn && pwdInput && icon) {
                toggleBtn.addEventListener('click', function () {
                    const isHidden = pwdInput.getAttribute('type') === 'password';
                    pwdInput.setAttribute('type', isHidden ? 'text' : 'password');
                    icon.classList.toggle('bi-eye', !isHidden);
                    icon.classList.toggle('bi-eye-slash', isHidden);
                });
            }
        });
    </script>
</body>
</html>
