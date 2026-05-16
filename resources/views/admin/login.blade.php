<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Puffcart</title>
    <script>
        (function () {
            try {
                var theme = localStorage.getItem('puffcart-theme');
                document.documentElement.dataset.theme = theme === 'light' ? 'light' : 'dark';
            } catch (error) {
                document.documentElement.dataset.theme = 'dark';
            }

            if (document.documentElement.dataset.theme === 'dark') {
                document.documentElement.style.backgroundColor = '#0A0A0A';
            }
        })();
    </script>
    <meta name="color-scheme" content="light dark">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #0066ff;
            --primary-light: #e6f0ff;
            --primary-hover: #0052cc;
            --text-primary: #1a1a1a;
            --text-secondary: #666666;
            --text-muted: #999999;
            --border: #e0e0e0;
            --bg-light: #f9f9f9;
            --bg-white: #ffffff;
            --shadow-md: 0 2px 8px rgba(0, 0, 0, 0.08);
            --radius: 8px;
            --radius-lg: 12px;
        }

        :root[data-theme="dark"] {
            --primary: #66A9FF;
            --primary-light: #1F1F1F;
            --primary-hover: #9B7CFF;
            --text-primary: #FFFFFF;
            --text-secondary: #B3B3B3;
            --text-muted: #808080;
            --border: #2A2A2A;
            --bg-light: #0A0A0A;
            --bg-white: #181818;
            --shadow-md: 0 24px 70px rgba(0, 0, 0, 0.48);
            color-scheme: dark;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            background: linear-gradient(135deg, var(--bg-light), var(--bg-white));
            color: var(--text-primary);
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            display: grid;
            place-items: center;
            padding: 20px;
        }

        .login-card {
            background: var(--bg-white);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-md);
            max-width: 420px;
            padding: 38px;
            width: 100%;
        }

        .brand {
            color: var(--primary);
            font-family: 'Poppins', sans-serif;
            font-size: 28px;
            font-weight: 700;
            text-align: center;
        }

        h1 {
            color: var(--text-primary);
            font-family: 'Poppins', sans-serif;
            font-size: 24px;
            margin: 18px 0 6px;
            text-align: center;
        }

        .subtitle {
            color: var(--text-muted);
            font-size: 14px;
            margin: 0 0 28px;
            text-align: center;
        }

        .form-group {
            display: grid;
            gap: 6px;
            margin-bottom: 16px;
        }

        label {
            color: var(--text-primary);
            font-size: 13px;
            font-weight: 700;
        }

        input {
            border: 1px solid var(--border);
            border-radius: var(--radius);
            font: inherit;
            min-height: 42px;
            padding: 10px 12px;
            width: 100%;
        }

        input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px var(--primary-light);
            outline: none;
        }

        .password-field {
            position: relative;
        }

        .password-field input {
            padding-right: 46px;
        }

        .password-toggle {
            align-items: center;
            background: transparent;
            border: 0;
            border-radius: 8px;
            color: var(--text-muted);
            cursor: pointer;
            display: inline-flex;
            height: 34px;
            justify-content: center;
            padding: 0;
            position: absolute;
            right: 7px;
            top: 50%;
            transform: translateY(-50%);
            width: 34px;
        }

        .password-toggle:hover,
        .password-toggle:focus {
            background: var(--primary-light);
            color: var(--primary);
            outline: none;
        }

        .password-toggle svg {
            height: 18px;
            width: 18px;
        }

        .password-toggle .icon-eye-off {
            display: none;
        }

        .password-toggle.is-visible .icon-eye {
            display: none;
        }

        .password-toggle.is-visible .icon-eye-off {
            display: block;
        }

        .remember {
            align-items: center;
            color: var(--text-secondary);
            display: flex;
            gap: 8px;
            font-size: 14px;
            margin: 2px 0 20px;
        }

        .remember input {
            min-height: auto;
            width: auto;
        }

        .btn {
            background: var(--primary);
            border: 0;
            border-radius: var(--radius);
            color: #fff;
            cursor: pointer;
            font: inherit;
            font-weight: 700;
            min-height: 42px;
            width: 100%;
        }

        .btn:hover {
            background: var(--primary-hover);
        }

        .alert {
            background: #fef2f2;
            border: 1px solid #fecaca;
            border-radius: var(--radius);
            color: #b91c1c;
            font-size: 14px;
            margin-bottom: 16px;
            padding: 12px 14px;
        }

        .back-link {
            color: var(--primary);
            display: block;
            font-size: 14px;
            font-weight: 700;
            margin-top: 18px;
            text-align: center;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <main class="login-card">
        <div class="brand">Puffcart</div>
        <h1>Admin Login</h1>
        <p class="subtitle">Sign in to manage the ecommerce store.</p>

        @if($errors->any())
            <div class="alert">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('admin.login.submit') }}">
            @csrf

            <div class="form-group">
                <label for="login">Username or Email</label>
                <input id="login" type="text" name="login" value="{{ old('login') }}" placeholder="admin" required autofocus>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <div class="password-field">
                    <input id="password" type="password" name="password" placeholder="admin123" autocomplete="current-password" required>
                    <button class="password-toggle" type="button" data-password-toggle="password" aria-label="Show password" aria-pressed="false">
                        <svg class="icon-eye" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                        <svg class="icon-eye-off" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="m3 3 18 18"/><path d="M10.6 10.6A3 3 0 0 0 13.4 13.4"/><path d="M9.9 4.2A10.8 10.8 0 0 1 12 4c6.5 0 10 8 10 8a18.8 18.8 0 0 1-3.2 4.4"/><path d="M6.6 6.6A18.3 18.3 0 0 0 2 12s3.5 8 10 8a10.7 10.7 0 0 0 4.1-.8"/></svg>
                    </button>
                </div>
            </div>

            <label class="remember">
                <input type="checkbox" name="remember" value="1">
                Remember this device
            </label>

            <button type="submit" class="btn">Sign In</button>
        </form>

        <a href="{{ route('home') }}" class="back-link">Back to store</a>
    </main>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('[data-password-toggle]').forEach((button) => {
                button.addEventListener('click', function () {
                    const input = document.getElementById(this.dataset.passwordToggle);
                    if (!input) {
                        return;
                    }

                    const shouldShow = input.type === 'password';
                    input.type = shouldShow ? 'text' : 'password';
                    this.classList.toggle('is-visible', shouldShow);
                    this.setAttribute('aria-label', shouldShow ? 'Hide password' : 'Show password');
                    this.setAttribute('aria-pressed', shouldShow ? 'true' : 'false');
                });
            });
        });
    </script>
    @include('partials.dark-mode-overrides')
</body>
</html>
