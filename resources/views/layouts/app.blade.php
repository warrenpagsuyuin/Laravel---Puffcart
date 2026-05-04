<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - VapeVault</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html, body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background-color: #ffffff;
            color: #1a1a1a;
            line-height: 1.6;
        }

        /* Color System */
        :root {
            --primary: #0066ff; /* Electric Blue */
            --primary-light: #e6f0ff;
            --primary-hover: #0052cc;
            --text-primary: #1a1a1a;
            --text-secondary: #666666;
            --text-muted: #999999;
            --border: #e0e0e0;
            --bg-light: #f9f9f9;
            --bg-white: #ffffff;
            --shadow-sm: 0 1px 2px rgba(0, 0, 0, 0.05);
            --shadow-md: 0 2px 8px rgba(0, 0, 0, 0.08);
            --shadow-lg: 0 4px 16px rgba(0, 0, 0, 0.1);
            --radius: 8px;
            --radius-lg: 12px;
        }

        /* Base Styles */
        h1, h2, h3, h4, h5, h6 {
            font-family: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            font-weight: 600;
            color: var(--text-primary);
        }

        h1 { font-size: 36px; line-height: 1.2; }
        h2 { font-size: 28px; line-height: 1.3; }
        h3 { font-size: 20px; line-height: 1.4; }
        h4 { font-size: 16px; line-height: 1.5; }

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

        button, .btn {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            font-weight: 500;
            transition: all 0.2s ease;
            cursor: pointer;
            border-radius: var(--radius);
            border: none;
        }

        input, textarea, select {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            border-radius: var(--radius);
            border: 1px solid var(--border);
            padding: 10px 12px;
            transition: all 0.2s ease;
        }

        input:focus, textarea:focus, select:focus {
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