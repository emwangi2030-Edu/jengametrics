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

    </style>
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
                <input type="password" id="password" name="password" class="form-control" required autocomplete="new-password">
                @if ($errors->has('password'))
                    <div class="text-danger mt-1">
                        {{ $errors->first('password') }}
                    </div>
                @endif
            </div>

            <!-- Confirm Password -->
            <div class="mb-3">
                <label for="password_confirmation" class="form-label text-white">{{ __('Confirm Password') }}</label>
                <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required autocomplete="new-password">
                @if ($errors->has('password_confirmation'))
                    <div class="text-danger mt-1">
                        {{ $errors->first('password_confirmation') }}
                    </div>
                @endif
            </div>

            <div class="d-flex justify-content-between align-items-center text-light">
                <a href="{{ route('login') }}">{{ __('Already registered?') }}</a>
            </div>

            <button type="submit" class="btn btn-modern mt-4">{{ __('Register') }}</button>
        </form>
    </div>

    <!-- Bootstrap 5 JS Bundle (including Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
