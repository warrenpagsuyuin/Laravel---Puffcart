<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- Needed for chatbot POST request --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Home') - Puffcart</title>

    <script>
        (function () {
            try {
                var theme = localStorage.getItem('puffcart-theme');
                document.documentElement.dataset.theme = theme === 'dark' ? 'dark' : 'light';
            } catch (error) {
                document.documentElement.dataset.theme = 'light';
            }
        })();
    </script>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary: #0b63f6;
            --primary-light: #eaf2ff;
            --primary-hover: #084ec1;
            --text-primary: #0f172a;
            --text-secondary: #53657d;
            --text-muted: #8492a6;
            --border: #dbe4ef;
            --bg-light: #f5f8fc;
            --bg-white: #ffffff;
            --shadow-sm: 0 1px 2px rgba(0, 0, 0, 0.05);
            --shadow-md: 0 8px 20px rgba(15, 23, 42, 0.08);
            --shadow-lg: 0 18px 38px rgba(15, 23, 42, 0.1);
            --radius: 8px;
            --radius-lg: 12px;
        }

        :root[data-theme="dark"] {
            --primary: #7db7ff;
            --primary-light: #112b4f;
            --primary-hover: #a9ceff;
            --text-primary: #eef5ff;
            --text-secondary: #b7c6da;
            --text-muted: #8ea2bc;
            --border: #26384f;
            --bg-light: #0b1220;
            --bg-white: #121b2b;
            --shadow-sm: 0 1px 2px rgba(0, 0, 0, 0.24);
            --shadow-md: 0 14px 30px rgba(0, 0, 0, 0.34);
            --shadow-lg: 0 24px 46px rgba(0, 0, 0, 0.4);
            color-scheme: dark;
        }

        html,
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background-color: #ffffff;
            color: #1a1a1a;
            line-height: 1.6;
        }

        :root[data-theme="dark"] body {
            background-color: #0b1220;
            color: #dbe7f5;
        }

        :root[data-theme="dark"] .nav,
        :root[data-theme="dark"] .store-nav,
        :root[data-theme="dark"] .account-nav {
            background: #0a101d;
            border-bottom-color: #203047;
        }

        :root[data-theme="dark"] .logo,
        :root[data-theme="dark"] .brand {
            color: #7db7ff;
        }

        :root[data-theme="dark"] .nav a,
        :root[data-theme="dark"] .nav-links a {
            color: #cbd5e1;
        }

        :root[data-theme="dark"] .nav a:hover,
        :root[data-theme="dark"] .nav-links a:hover,
        :root[data-theme="dark"] .nav-links .active {
            background: #112b4f;
            color: #d7e9ff;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            font-family: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            font-weight: 600;
            color: var(--text-primary);
        }

        h1 {
            font-size: 36px;
            line-height: 1.2;
        }

        h2 {
            font-size: 28px;
            line-height: 1.3;
        }

        h3 {
            font-size: 20px;
            line-height: 1.4;
        }

        h4 {
            font-size: 16px;
            line-height: 1.5;
        }

        p {
            color: var(--text-secondary);
        }

        a {
            color: var(--primary);
            text-decoration: none;
            transition: color 0.2s ease;
        }

        a:hover {
            color: var(--primary-hover);
        }

        button,
        .btn {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            font-weight: 500;
            transition: all 0.2s ease;
            cursor: pointer;
            border-radius: var(--radius);
            border: none;
        }

        input,
        textarea,
        select {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            border-radius: var(--radius);
            border: 1px solid var(--border);
            padding: 10px 12px;
            transition: all 0.2s ease;
        }

        input:focus,
        textarea:focus,
        select:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px var(--primary-light);
        }
    </style>
</head>
<body>
    @yield('content')
</body>
</html>
