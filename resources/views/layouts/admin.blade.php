<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - PuffCart Admin</title>
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
            --shadow-sm: 0 1px 2px rgba(0,0,0,0.05);
            --shadow-md: 0 4px 16px rgba(0,0,0,0.08);
            --shadow-lg: 0 8px 32px rgba(0,0,0,0.10);
            --radius: 8px;
            --radius-lg: 12px;
            --transition: 0.18s ease;
        }

        *, *::before, *::after { box-sizing: border-box; }

        html { scroll-behavior: smooth; }

        body {
            margin: 0;
            background: var(--bg-light);
            color: var(--text-primary);
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            line-height: 1.5;
        }

        h1, h2, h3 {
            font-family: 'Poppins', -apple-system, BlinkMacSystemFont, sans-serif;
            margin: 0;
        }

        a { color: inherit; text-decoration: none; }
        button, input, textarea, select { font-family: inherit; }

        /* ── Shell ── */
        .admin-shell {
            display: flex;
            min-height: 100vh;
        }

        /* ── Sidebar ── */
        .sidebar {
            width: 264px;
            background: var(--bg-white);
            border-right: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            position: sticky;
            top: 0;
            height: 100vh;
            overflow-y: auto;
            transition: box-shadow var(--transition);
        }

        .sidebar:hover {
            box-shadow: 2px 0 16px rgba(0,0,0,0.06);
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
            transition: opacity var(--transition);
        }

        .brand-name:hover { opacity: 0.85; }

        .brand-subtitle {
            color: var(--text-muted);
            font-size: 13px;
            margin-top: 2px;
        }

        /* ── Nav ── */
        .sidebar-nav {
            display: grid;
            gap: 2px;
            padding: 14px;
            flex: 1;
        }

        .nav-section {
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: var(--text-muted);
            padding: 14px 10px 5px;
        }

        .sidebar-link {
            border-radius: var(--radius);
            color: var(--text-secondary);
            display: flex;
            align-items: center;
            gap: 9px;
            font-size: 14px;
            font-weight: 600;
            justify-content: space-between;
            padding: 10px 12px;
            position: relative;
            transition:
                background var(--transition),
                color var(--transition),
                transform 0.12s ease,
                box-shadow var(--transition);
            cursor: pointer;
        }

        .sidebar-link:hover {
            background: var(--primary-light);
            color: var(--primary);
            transform: translateX(2px);
        }

        .sidebar-link.active {
            background: var(--primary-light);
            color: var(--primary);
            box-shadow: inset 3px 0 0 var(--primary);
        }

        .sidebar-link svg {
            width: 16px;
            height: 16px;
            flex-shrink: 0;
            opacity: 0.7;
            transition: opacity var(--transition);
        }

        .sidebar-link:hover svg,
        .sidebar-link.active svg { opacity: 1; }

        .link-inner {
            display: flex;
            align-items: center;
            gap: 9px;
        }

        /* ── Sidebar footer ── */
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

        /* ── Main ── */
        .main { flex: 1; min-width: 0; }

        /* ── Topbar ── */
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
            box-shadow: var(--shadow-sm);
            transition: box-shadow var(--transition);
        }

        .topbar.scrolled {
            box-shadow: var(--shadow-md);
        }

        .page-title {
            font-size: 24px;
            line-height: 1.2;
        }

        .topbar-actions, .actions {
            align-items: center;
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        /* ── Content ── */
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

            padding: 28px;
            animation: fadeIn 0.22s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(6px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ── Alerts ── */
        .alert {
            border-radius: var(--radius);
            font-size: 14px;
            margin-bottom: 18px;
            padding: 12px 14px;
            animation: slideDown 0.2s ease;
        }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-8px); }
            to   { opacity: 1; transform: translateY(0); }
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

        /* ── Grid ── */
        .grid { display: grid; gap: 16px; }
        .grid-2 { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        .grid-3 { grid-template-columns: repeat(3, minmax(0, 1fr)); }
        .grid-4 { grid-template-columns: repeat(4, minmax(0, 1fr)); }

        /* ── Cards ── */
        .panel, .stat-card {
            background: var(--bg-white);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-sm);
            transition:
                box-shadow var(--transition),
                transform var(--transition),
                border-color var(--transition);
        }

        .panel { padding: 20px; }
        .stat-card { padding: 18px; }

        .stat-card:hover {
            box-shadow: var(--shadow-md);
            transform: translateY(-2px);
            border-color: #d0d0d0;
        }

        .panel:hover {
            box-shadow: var(--shadow-md);
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

        /* ── Section titles ── */
        .section-title {
            align-items: center;
            display: flex;
            justify-content: space-between;
            margin-bottom: 14px;
        }

        .section-title h2 { font-size: 18px; }
        .muted { color: var(--text-muted); }

        /* ── Tables ── */
        .table-wrap { overflow-x: auto; }

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
            transition: background var(--transition);
        }

        .admin-table tbody tr {
            transition: background var(--transition);
        }

        .admin-table tbody tr:hover td {
            background: var(--bg-light);
        }

        .admin-table strong { color: var(--text-primary); }

        /* ── Badges ── */
        .badge {
            border-radius: 999px;
            display: inline-flex;
            font-size: 12px;
            font-weight: 700;
            padding: 4px 9px;
            white-space: nowrap;
            transition: opacity var(--transition);
        }

        .badge-blue   { background: var(--primary-light); color: var(--primary); }
        .badge-green  { background: #dcfce7; color: var(--success); }
        .badge-yellow { background: #fef3c7; color: var(--warning); }
        .badge-red    { background: #fee2e2; color: var(--danger); }
        .badge-gray   { background: #f3f4f6; color: var(--text-secondary); }

        /* ── Buttons ── */
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
            padding: 9px 14px;
            transition:
                background var(--transition),
                border-color var(--transition),
                color var(--transition),
                transform 0.1s ease,
                box-shadow var(--transition);
            gap: 6px;
        }

        .btn:hover  { transform: translateY(-1px); box-shadow: var(--shadow-md); }
        .btn:active { transform: translateY(0);    box-shadow: var(--shadow-sm); }

        .btn-primary {
            background: var(--primary);
            color: white;
        }

        .btn-primary:hover { background: var(--primary-hover); }

        .btn-secondary {
            background: var(--bg-white);
            border-color: var(--border);
            color: var(--text-primary);
        }

        .btn-secondary:hover {
            border-color: var(--primary);
            color: var(--primary);
            background: var(--primary-light);
        }

        .btn-danger {
            background: #fee2e2;
            color: var(--danger);
        }

<<<<<<< HEAD
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

=======
        .btn-danger:hover { background: #fecaca; }

        /* ── Forms ── */
>>>>>>> origin/feat/admin-fix1
        .form-grid {
            display: grid;
            gap: 14px;
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .form-group { display: grid; gap: 6px; }
        .form-group.full { grid-column: 1 / -1; }

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
            transition:
                border-color var(--transition),
                box-shadow var(--transition);
        }

        textarea { min-height: 108px; resize: vertical; }

        input:focus, textarea:focus, select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px var(--primary-light);
            outline: none;
        }

        input:hover, textarea:hover, select:hover {
            border-color: #c0c0c0;
        }

        .checkbox-row { align-items: center; display: flex; gap: 8px; }
        .checkbox-row input { min-height: auto; width: auto; }

        .pagination { margin-top: 16px; }

        /* ── Responsive ── */
        @media (max-width: 980px) {
            .admin-shell { display: block; }

            .sidebar {
                height: auto;
                position: static;
                width: 100%;
            }

            .sidebar-nav { grid-template-columns: repeat(2, minmax(0, 1fr)); }
            .nav-section { display: none; }

<<<<<<< HEAD
            .grid-2,
            .grid-3,
            .grid-4,
            .metric-strip,
            .form-grid {
=======
            .grid-2, .grid-3, .grid-4, .form-grid {
>>>>>>> origin/feat/admin-fix1
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

            .content { padding: 18px; }
        }
    </style>
    @stack('styles')
</head>
<body>
<div class="admin-shell">

    <aside class="sidebar">
        <div class="brand">
            <div class="brand-name">PuffCart</div>
            <div class="brand-subtitle">Admin</div>
        </div>

        <nav class="sidebar-nav">
            <div class="nav-section">Main</div>

            <a class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
               href="{{ route('admin.dashboard') }}">
                <span class="link-inner">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/>
                        <rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/>
                    </svg>
                    Dashboard
                </span>
            </a>

            <a class="sidebar-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}"
               href="{{ route('admin.products.index') }}">
                <span class="link-inner">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/>
                        <line x1="3" y1="6" x2="21" y2="6"/>
                        <path d="M16 10a4 4 0 01-8 0"/>
                    </svg>
                    Products
                </span>
            </a>

            <a class="sidebar-link {{ request()->routeIs('admin.inventory.*') ? 'active' : '' }}"
               href="{{ route('admin.inventory.index') }}">
                <span class="link-inner">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="2" y="7" width="20" height="14" rx="2"/>
                        <path d="M16 7V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v2"/>
                    </svg>
                    Inventory
                </span>
            </a>

            <div class="nav-section">Sales</div>

            <a class="sidebar-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}"
               href="{{ route('admin.orders.index') }}">
                <span class="link-inner">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"/>
                        <rect x="9" y="3" width="6" height="4" rx="1"/>
                        <path d="M9 12h6M9 16h4"/>
                    </svg>
                    Orders
                </span>
            </a>

            <a class="sidebar-link {{ request()->routeIs('admin.walk-in.*') ? 'active' : '' }}"
                href="{{ route('admin.walk-in.index') }}">
                 <span class="link-inner">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                        Walk-In
                 </span>
            </a>

            <a class="sidebar-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}"
               href="{{ route('admin.users.index') }}">
                <span class="link-inner">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/>
                        <circle cx="9" cy="7" r="4"/>
                        <path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/>
                    </svg>
                    Customers
                </span>
            </a>

            <a class="sidebar-link {{ request()->routeIs('admin.verifications.*') ? 'active' : '' }}"
               href="{{ route('admin.verifications.index') }}">
                <span class="link-inner">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                        <polyline points="9 12 11 14 15 10"/>
                    </svg>
                    Age Verification
                </span>
            </a>

            <div class="nav-section">Reports</div>

            <a class="sidebar-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}"
               href="{{ route('admin.reports.index') }}">
                <span class="link-inner">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="20" x2="18" y2="10"/>
                        <line x1="12" y1="20" x2="12" y2="4"/>
                        <line x1="6" y1="20" x2="6" y2="14"/>
                    </svg>
                    Reports
                </span>
            </a>

            <a class="sidebar-link {{ request()->routeIs('admin.audit-logs.*') ? 'active' : '' }}"
               href="{{ route('admin.audit-logs.index') }}">
                <span class="link-inner">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/>
                        <polyline points="14 2 14 8 20 8"/>
                        <line x1="16" y1="13" x2="8" y2="13"/>
                        <line x1="16" y1="17" x2="8" y2="17"/>
                    </svg>
                    Audit Logs
                </span>
            </a>
        </nav>

        <div class="sidebar-footer">
            <div class="admin-user">{{ auth()->user()->name }}</div>
            <div class="admin-role">Administrator</div>
        </div>
    </aside>

    <div class="main">
        <header class="topbar" id="topbar">
            <div>
                <h1 class="page-title">@yield('page-title', 'Dashboard')</h1>
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

<script>
    // Topbar shadow on scroll
    const topbar = document.getElementById('topbar');
    const main   = topbar?.closest('.main');
    if (main) {
        main.addEventListener('scroll', () => {
            topbar.classList.toggle('scrolled', main.scrollTop > 4);
        }, { passive: true });
    }

    // Auto-dismiss alerts after 4s
    document.querySelectorAll('.alert').forEach(el => {
        setTimeout(() => {
            el.style.transition = 'opacity 0.4s ease, transform 0.4s ease';
            el.style.opacity    = '0';
            el.style.transform  = 'translateY(-6px)';
            setTimeout(() => el.remove(), 400);
        }, 4000);
    });
</script>

@stack('scripts')
</body>
</html>