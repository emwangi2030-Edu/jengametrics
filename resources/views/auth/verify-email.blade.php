<!DOCTYPE html>
<html lang="en">
@include("auth.partials.head", ["title" => "Verify Email"])
<body class="jm-auth">
    <div class="jm-auth-shell">
        <div class="jm-auth-brand">
            <div class="jm-auth-logo-row">
                <div class="jm-auth-logo-mark">🏗️</div>
                <div class="jm-auth-logo-text">Jenga<span>Metrics</span></div>
            </div>
            <h1>Verify your email</h1>
            <p>One quick confirmation keeps your account protected and ready for project onboarding.</p>
            @include("auth.partials.environment-badge")
        </div>

        <div class="jm-auth-card">
            <h2 class="jm-auth-title">Email Verification</h2>
            <p class="jm-auth-muted text-center">
                {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
            </p>

            @if (session('status') == 'verification-link-sent')
                <div class="alert alert-success text-center" role="alert">
                    {{ __('A new verification link has been sent to the email address you provided during registration.') }}
                </div>
            @endif

            <form method="POST" action="{{ route('verification.send') }}" class="mt-3">
                @csrf
                <button type="submit" class="btn jm-auth-btn">{{ __('Resend Verification Email') }}</button>
            </form>

            <form method="POST" action="{{ route('logout') }}" class="mt-3 text-center">
                @csrf
                <button type="submit" class="btn btn-link jm-auth-link">{{ __('Log Out') }}</button>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
