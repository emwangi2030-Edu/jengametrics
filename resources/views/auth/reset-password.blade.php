<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/metrics/assets/css/auth-refresh.css') }}">
</head>
<body class="jm-auth">
    <div class="jm-auth-shell">
        <div class="jm-auth-brand">
            <div class="jm-auth-logo-row">
                <div class="jm-auth-logo-mark">🏗️</div>
                <div class="jm-auth-logo-text">Jenga<span>Metrics</span></div>
            </div>
            <h1>Set a new password</h1>
            <p>Choose a strong password to restore access and secure your account.</p>
            <div class="jm-auth-brand-meta">Staging Environment</div>
        </div>

        <div class="jm-auth-card">
            <h2 class="jm-auth-title">Reset Password</h2>
            <form method="POST" action="{{ route('password.store') }}">
                @csrf
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <div class="mb-3">
                    <label for="email" class="jm-auth-label">{{ __('Email') }}</label>
                    <input id="email" class="form-control jm-auth-input" type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username" />
                    @if ($errors->has('email'))
                        <div class="text-danger mt-1">{{ $errors->first('email') }}</div>
                    @endif
                </div>

                <div class="mb-3">
                    <label for="password" class="jm-auth-label">{{ __('Password') }}</label>
                    <input id="password" class="form-control jm-auth-input" type="password" name="password" required autocomplete="new-password" />
                    @if ($errors->has('password'))
                        <div class="text-danger mt-1">{{ $errors->first('password') }}</div>
                    @endif
                </div>

                <div class="mb-3">
                    <label for="password_confirmation" class="jm-auth-label">{{ __('Confirm Password') }}</label>
                    <input id="password_confirmation" class="form-control jm-auth-input" type="password" name="password_confirmation" required autocomplete="new-password" />
                    @if ($errors->has('password_confirmation'))
                        <div class="text-danger mt-1">{{ $errors->first('password_confirmation') }}</div>
                    @endif
                </div>

                <button type="submit" class="btn jm-auth-btn mt-3">{{ __('Reset Password') }}</button>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
