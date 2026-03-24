<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirm Password</title>
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
            <h1>Security confirmation</h1>
            <p>Please verify your password to continue with this protected action.</p>
            <div class="jm-auth-brand-meta">Staging Environment</div>
        </div>

        <div class="jm-auth-card">
            <h2 class="jm-auth-title">Confirm Password</h2>
            <p class="jm-auth-muted text-center">
                {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
            </p>

            <form method="POST" action="{{ route('password.confirm') }}">
                @csrf
                <div class="mb-3">
                    <label for="password" class="jm-auth-label">{{ __('Password') }}</label>
                    <input id="password" class="form-control jm-auth-input" type="password" name="password" required autocomplete="current-password" />
                    @if ($errors->has('password'))
                        <div class="text-danger mt-1">{{ $errors->first('password') }}</div>
                    @endif
                </div>

                <button type="submit" class="btn jm-auth-btn mt-3">{{ __('Confirm') }}</button>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
