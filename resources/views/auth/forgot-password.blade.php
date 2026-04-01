<!DOCTYPE html>
<html lang="en">
@include("auth.partials.head", ["title" => "Forgot Password"])
<body class="jm-auth">
    <div class="jm-auth-shell">
        <div class="jm-auth-brand">
            <div class="jm-auth-logo-row">
                <div class="jm-auth-logo-mark">🏗️</div>
                <div class="jm-auth-logo-text">Jenga<span>Metrics</span></div>
            </div>
            <h1>Recover your access</h1>
            <p>Enter your account email and we will send a secure password reset link.</p>
            @include("auth.partials.environment-badge")
        </div>

        <div class="jm-auth-card">
            <h2 class="jm-auth-title">Forgot Password</h2>

            <p class="jm-auth-muted text-center">
                {{ __('Forgot your password? No problem. Just enter your email address below and we will send you a password reset link.') }}
            </p>

            @if (session('status'))
                <div class="alert alert-success text-center" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <div class="mb-3">
                    <label for="email" class="jm-auth-label">{{ __('Email') }}</label>
                    <input type="email" id="email" name="email" class="form-control jm-auth-input" value="{{ old('email') }}" required autofocus>
                    @if ($errors->has('email'))
                        <div class="text-danger mt-1">{{ $errors->first('email') }}</div>
                    @endif
                </div>

                <button type="submit" class="btn jm-auth-btn mt-4">{{ __('Send Password Reset Link') }}</button>

                <p class="text-center mt-3 jm-auth-muted">
                    <a href="{{ route('login') }}" class="jm-auth-link fw-bold">{{ __('Back to login') }}</a>
                </p>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
