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

            if (document.documentElement.dataset.theme === 'dark') {
                document.documentElement.style.backgroundColor = '#0A0A0A';
            }
        })();
    </script>
    <meta name="color-scheme" content="light dark">

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
            --primary: #66A9FF;
            --primary-light: #111111;
            --primary-hover: #9B7CFF;
            --text-primary: #FFFFFF;
            --text-secondary: #B3B3B3;
            --text-muted: #808080;
            --border: #2A2A2A;
            --bg-light: #111111;
            --bg-white: #181818;
            --surface: #1F1F1F;
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
            background-color: #0A0A0A;
            color: #FFFFFF;
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

        .puff-pagination {
            align-items: center;
            background: var(--bg-white);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            display: flex;
            gap: 14px;
            justify-content: space-between;
            padding: 14px 16px;
            width: 100%;
        }

        .puff-pagination__summary {
            color: var(--text-secondary);
            display: block !important;
            font-size: 13px;
            font-weight: 600;
            white-space: nowrap;
        }

        .puff-pagination__summary strong {
            color: var(--text-primary);
            font-weight: 800;
        }

        .puff-pagination__controls,
        .puff-pagination__pages {
            align-items: center;
            display: flex !important;
            gap: 6px;
        }

        .puff-pagination__button,
        .puff-pagination__page,
        .puff-pagination__ellipsis {
            align-items: center;
            border-radius: 7px;
            display: inline-flex;
            font-size: 13px;
            font-weight: 700;
            justify-content: center;
            min-height: 36px;
        }

        .puff-pagination__button,
        .puff-pagination__page {
            background: #ffffff;
            border: 1px solid #cbd5e1;
            color: var(--text-primary);
            min-width: 36px;
            padding: 8px 12px;
        }

        .puff-pagination__button:hover,
        .puff-pagination__page:hover {
            background: var(--primary-light);
            border-color: var(--primary);
            color: var(--primary);
        }

        .puff-pagination__page.is-active {
            background: var(--primary);
            border-color: var(--primary);
            color: #ffffff;
        }

        .puff-pagination__button.is-disabled,
        .puff-pagination__ellipsis {
            color: var(--text-muted);
            cursor: not-allowed;
        }

        .puff-pagination__button.is-disabled {
            background: #f8fafc;
            border-color: var(--border);
        }

        .puff-pagination__ellipsis {
            min-width: 28px;
            padding: 8px 4px;
        }

        @media (max-width: 720px) {
            .puff-pagination {
                align-items: stretch;
                flex-direction: column;
            }

            .puff-pagination__summary {
                white-space: normal;
            }

            .puff-pagination__controls {
                flex-wrap: wrap;
            }
        }
    </style>
</head>
<body>
    @yield('content')
    @include('partials.dark-mode-overrides')
</body>
</html>
