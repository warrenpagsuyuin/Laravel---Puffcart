<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - Puffcart Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
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
            --success: #15803d;
            --warning: #b45309;
            --danger: #b91c1c;
            --shadow-sm: 0 1px 2px rgba(0, 0, 0, 0.05);
            --shadow-md: 0 2px 8px rgba(0, 0, 0, 0.08);
            --radius: 8px;
            --radius-lg: 12px;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: var(--bg-light);
            color: var(--text-primary);
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            line-height: 1.5;
        }

        h1, h2, h3 {
            font-family: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            margin: 0;
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        button, input, textarea, select {
            font-family: inherit;
        }

        .admin-shell {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 264px;
            background: var(--bg-white);
            border-right: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            position: sticky;
            top: 0;
            height: 100vh;
        }

        .brand {
            padding: 24px;
            border-bottom: 1px solid var(--border);
        }

        .brand-name {
            color: var(--primary);
            font-family: 'Poppins', sans-serif;
            font-size: 22px;
            font-weight: 700;
        }

        .brand-subtitle {
            color: var(--text-muted);
            font-size: 13px;
            margin-top: 2px;
        }

        .sidebar-nav {
            display: grid;
            gap: 4px;
            padding: 16px;
            flex: 1;
        }

        .sidebar-link {
            border-radius: var(--radius);
            color: var(--text-secondary);
            display: flex;
            font-size: 14px;
            font-weight: 600;
            justify-content: space-between;
            padding: 11px 12px;
            transition: background 0.2s ease, color 0.2s ease;
        }

        .sidebar-link:hover,
        .sidebar-link.active {
            background: var(--primary-light);
            color: var(--primary);
        }

        .sidebar-footer {
            border-top: 1px solid var(--border);
            padding: 16px;
        }

        .admin-user {
            color: var(--text-primary);
            font-weight: 700;
            font-size: 14px;
        }

        .admin-role {
            color: var(--text-muted);
            font-size: 12px;
        }

        .main {
            flex: 1;
            min-width: 0;
        }

        .topbar {
            align-items: center;
            background: var(--bg-white);
            border-bottom: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            min-height: 72px;
            padding: 16px 28px;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .page-title {
            font-size: 24px;
            line-height: 1.2;
        }

        .page-subtitle {
            color: var(--text-muted);
            font-size: 14px;
            margin-top: 3px;
        }

        .topbar-actions,
        .actions {
            align-items: center;
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .content {
            padding: 36px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .admin-stack {
            display: grid;
            gap: 16px;
        }

        .admin-toolbar {
            align-items: center;
            background: linear-gradient(180deg, #ffffff 0%, #fbfdff 100%);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-sm);
            display: flex;
            gap: 12px;
            justify-content: space-between;
            padding: 14px 16px;
        }

        .admin-toolbar-title {
            display: grid;
            gap: 2px;
            min-width: 180px;
        }

        .admin-toolbar-title strong {
            color: #0f2747;
            font-size: 15px;
        }

        .admin-toolbar-title span {
            color: var(--text-muted);
            font-size: 12px;
        }

        .toolbar-form {
            align-items: center;
            display: flex;
            flex: 1;
            flex-wrap: wrap;
            gap: 8px;
            justify-content: flex-end;
        }

        .toolbar-form input,
        .toolbar-form select {
            min-height: 36px;
            padding: 8px 10px;
        }

        .toolbar-form .search-control {
            max-width: 280px;
            min-width: 220px;
        }

        .toolbar-form .filter-control {
            max-width: 170px;
        }

        .toolbar-form .checkbox-row {
            color: var(--text-secondary);
            font-size: 13px;
            min-height: 36px;
            padding: 0 4px;
        }

        .metric-strip {
            display: grid;
            gap: 12px;
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }

        .metric-card {
            background: var(--bg-white);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-sm);
            padding: 14px 16px;
        }

        .metric-label {
            color: var(--text-muted);
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .metric-value {
            color: #0f2747;
            font-family: 'Poppins', sans-serif;
            font-size: 24px;
            font-weight: 700;
            margin-top: 4px;
        }

        .compact-table th {
            background: #f8fafc;
            border-top: 1px solid var(--border);
            color: #5d6b7c;
            padding: 10px 12px;
        }

        .compact-table td {
            padding: 11px 12px;
        }

        .product-cell {
            align-items: center;
            display: flex;
            gap: 10px;
            min-width: 260px;
        }

        .product-thumb {
            align-items: center;
            background: #f4f7fb;
            border: 1px solid var(--border);
            border-radius: var(--radius);
            display: inline-flex;
            height: 44px;
            justify-content: center;
            overflow: hidden;
            width: 44px;
        }

        .product-thumb img {
            max-height: 38px;
            max-width: 38px;
            object-fit: contain;
        }

        .inline-stock-form {
            align-items: center;
            display: flex;
            gap: 8px;
        }

        .inline-stock-form input {
            min-height: 34px;
            padding: 7px 9px;
            width: 82px;
        }

        .btn-compact {
            min-height: 36px;
            padding: 7px 12px;
        }

        /* page grid for list + filter column */
        .page-grid {
            display: grid;
            grid-template-columns: 300px 1fr;
            gap: 24px;
            align-items: start;
        }

        .panel--soft {
            background: #fbfdff;
            border: 1px solid rgba(14,30,50,0.04);
            box-shadow: 0 4px 18px rgba(12,36,80,0.03);
        }

        /* Cards grid for modern product display */
        .cards-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 18px;
        }

        .product-card {
            border-radius: 10px;
            overflow: hidden;
            background: var(--bg-white);
            border: 1px solid var(--border);
            box-shadow: var(--shadow-sm);
            display: flex;
            flex-direction: column;
            min-height: 260px;
        }

        .product-card .media {
            background: #f6f8fb;
            height: 140px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .product-card img { max-height: 130px; width: auto; max-width: 100%; object-fit: contain; }

        .product-card .body {
            padding: 12px 14px;
            display: flex;
            flex-direction: column;
            gap: 8px;
            flex: 1 1 auto;
        }

        .product-card .foot {
            padding: 10px 12px;
            border-top: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 8px;
        }

        .product-name { font-weight: 700; color: var(--text-primary); }
        .product-meta { color: var(--text-muted); font-size: 13px; }

        .card-actions { display:flex; gap:8px; align-items:center; }

        .alert {
            border-radius: var(--radius);
            font-size: 14px;
            margin-bottom: 18px;
            padding: 12px 14px;
        }

        .alert-success {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            color: var(--success);
        }

        .alert-error {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: var(--danger);
        }

        .grid {
            display: grid;
            gap: 16px;
        }

        .grid-2 {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .grid-3 {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }

        .grid-4 {
            grid-template-columns: repeat(4, minmax(0, 1fr));
        }

        .panel,
        .stat-card {
            background: var(--bg-white);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-sm);
        }

        .panel {
            padding: 20px;
        }

        .stat-card {
            padding: 18px;
        }

        .stat-label {
            color: var(--text-muted);
            font-size: 13px;
            font-weight: 600;
        }

        .stat-value {
            color: var(--text-primary);
            font-family: 'Poppins', sans-serif;
            font-size: 30px;
            font-weight: 700;
            margin-top: 8px;
        }

        .section-title {
            align-items: center;
            display: flex;
            justify-content: space-between;
            margin-bottom: 14px;
        }

        .section-title h2 {
            font-size: 18px;
        }

        .muted {
            color: var(--text-muted);
        }

        .table-wrap {
            overflow-x: auto;
        }

        .admin-table {
            border-collapse: collapse;
            min-width: 760px;
            width: 100%;
        }

        .admin-table th {
            color: var(--text-muted);
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 0;
            padding: 11px 10px;
            text-align: left;
            text-transform: uppercase;
        }

        .admin-table td {
            border-top: 1px solid var(--border);
            color: var(--text-secondary);
            font-size: 14px;
            padding: 13px 10px;
            vertical-align: middle;
        }

        .admin-table strong {
            color: var(--text-primary);
        }

        .badge {
            border-radius: 999px;
            display: inline-flex;
            font-size: 12px;
            font-weight: 700;
            padding: 4px 9px;
            white-space: nowrap;
        }

        .badge-blue {
            background: var(--primary-light);
            color: var(--primary);
        }

        .badge-green {
            background: #dcfce7;
            color: var(--success);
        }

        .badge-yellow {
            background: #fef3c7;
            color: var(--warning);
        }

        .badge-red {
            background: #fee2e2;
            color: var(--danger);
        }

        .badge-gray {
            background: #f3f4f6;
            color: var(--text-secondary);
        }

        .btn {
            align-items: center;
            border: 1px solid transparent;
            border-radius: var(--radius);
            cursor: pointer;
            display: inline-flex;
            font-size: 14px;
            font-weight: 700;
            justify-content: center;
            min-height: 40px;
            padding: 9px 13px;
            transition: all 0.2s ease;
        }

        .btn-primary {
            background: var(--primary);
            color: white;
        }

        .btn-primary:hover {
            background: var(--primary-hover);
        }

        .btn-secondary {
            background: var(--bg-white);
            border-color: var(--border);
            color: var(--text-primary);
        }

        .btn-secondary:hover {
            border-color: var(--primary);
            color: var(--primary);
        }

        .btn-danger {
            background: #fee2e2;
            color: var(--danger);
        }

        /* Small icon-style action buttons */
        .icon-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            padding: 6px;
            border-radius: 8px;
            background: transparent;
            border: 1px solid transparent;
            color: var(--text-secondary);
            transition: background 0.12s ease, color 0.12s ease, border-color 0.12s ease;
            text-decoration: none;
        }

        .icon-btn:hover {
            background: var(--bg-white);
            border-color: var(--border);
            color: var(--primary);
        }

        .icon-btn.danger { color: var(--danger); }
        .icon-btn.danger:hover { background: #fff2f2; border-color: rgba(185,28,28,0.08); }

        .admin-table tr:hover td { background: rgba(0,0,0,0.01); }

        .actions { display: flex; gap: 8px; align-items: center; }

        .form-grid {
            display: grid;
            gap: 14px;
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .form-group {
            display: grid;
            gap: 6px;
        }

        .form-group.full {
            grid-column: 1 / -1;
        }

        label {
            color: var(--text-primary);
            font-size: 13px;
            font-weight: 700;
        }

        input, textarea, select {
            background: var(--bg-white);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            color: var(--text-primary);
            font-size: 14px;
            min-height: 40px;
            padding: 10px 11px;
            width: 100%;
        }

        textarea {
            min-height: 108px;
            resize: vertical;
        }

        input:focus, textarea:focus, select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px var(--primary-light);
            outline: none;
        }

        .checkbox-row {
            align-items: center;
            display: flex;
            gap: 8px;
        }

        .checkbox-row input {
            min-height: auto;
            width: auto;
        }

        .pagination {
            margin-top: 16px;
        }

        @media (max-width: 980px) {
            .admin-shell {
                display: block;
            }

            .sidebar {
                height: auto;
                position: static;
                width: 100%;
            }

            .sidebar-nav {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .grid-2,
            .grid-3,
            .grid-4,
            .metric-strip,
            .form-grid {
                grid-template-columns: 1fr;
            }

            .admin-toolbar {
                align-items: stretch;
                flex-direction: column;
            }

            .toolbar-form {
                justify-content: flex-start;
            }

            .toolbar-form .search-control,
            .toolbar-form .filter-control {
                max-width: none;
                min-width: 0;
                width: 100%;
            }

            .topbar {
                align-items: flex-start;
                flex-direction: column;
                gap: 12px;
            }

            .content {
                padding: 18px;
            }
        }
    </style>
    @stack('styles')
</head>
<body>
    <div class="admin-shell">
        <aside class="sidebar">
            <div class="brand">
                <div class="brand-name">Puffcart</div>
                <div class="brand-subtitle">Admin panel</div>
            </div>

            <nav class="sidebar-nav">
                <a class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">Dashboard</a>
                <a class="sidebar-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}" href="{{ route('admin.products.index') }}">Products</a>
                <a class="sidebar-link {{ request()->routeIs('admin.inventory.*') ? 'active' : '' }}" href="{{ route('admin.inventory.index') }}">Inventory</a>
                <a class="sidebar-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}" href="{{ route('admin.orders.index') }}">Orders</a>
                <a class="sidebar-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">Customers</a>
                <a class="sidebar-link {{ request()->routeIs('admin.verifications.*') ? 'active' : '' }}" href="{{ route('admin.verifications.index') }}">Age Verification</a>
                <a class="sidebar-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}" href="{{ route('admin.reports.index') }}">Reports</a>
                <a class="sidebar-link {{ request()->routeIs('admin.ml-insights.*') ? 'active' : '' }}" href="{{ route('admin.ml-insights.index') }}">Machine Learning Insights</a>
            </nav>

            <div class="sidebar-footer">
                <div class="admin-user">{{ auth()->user()->name }}</div>
                <div class="admin-role">Administrator</div>
            </div>
        </aside>

        <div class="main">
            <header class="topbar">
                <div>
                    <h1 class="page-title">@yield('page-title', 'Dashboard')</h1>
                    <div class="page-subtitle">@yield('page-subtitle', 'Manage Puffcart operations')</div>
                </div>
                <div class="topbar-actions">
                    @yield('actions')
                    <form method="POST" action="{{ route('admin.logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-secondary">Logout</button>
                    </form>
                </div>
            </header>

            <main class="content">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                @if(session('error'))
                    <div class="alert alert-error">{{ session('error') }}</div>
                @endif

                @if($errors->any())
                    <div class="alert alert-error">
                        @foreach($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>
    @stack('scripts')
</body>
</html>
