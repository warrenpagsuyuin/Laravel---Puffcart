<style id="puffcart-dark-mode-overrides">
    :root[data-theme="dark"] {
        --primary: #66A9FF;
        --primary-light: #111827;
        --primary-hover: #9B7CFF;
        --accent: #66A9FF;
        --accent-purple: #9B7CFF;
        --text-primary: #FFFFFF;
        --text-secondary: #B3B3B3;
        --text-muted: #808080;
        --border: #2A2A2A;
        --bg-app: #0A0A0A;
        --bg-light: #111111;
        --bg-white: #181818;
        --surface: #1F1F1F;
        --surface-soft: #141414;
        --surface-raised: #1A1A1A;
        --success: #86EFAC;
        --warning: #FACC15;
        --danger: #FCA5A5;
        --shadow-sm: 0 1px 2px rgba(0, 0, 0, 0.45);
        --shadow-md: 0 14px 34px rgba(0, 0, 0, 0.46);
        --shadow-lg: 0 28px 70px rgba(0, 0, 0, 0.58);
        color-scheme: dark;
    }

    :root[data-theme="dark"],
    :root[data-theme="dark"] body {
        background: #0A0A0A !important;
        color: #FFFFFF !important;
    }

    :root[data-theme="dark"] body::before {
        background: #0A0A0A;
        content: "";
        inset: 0;
        pointer-events: none;
        position: fixed;
        z-index: -2147483648;
    }

    :root[data-theme="dark"] * {
        scrollbar-color: #3B82F6 #111111;
    }

    :root[data-theme="dark"] ::-webkit-scrollbar-track {
        background: #111111 !important;
    }

    :root[data-theme="dark"] ::-webkit-scrollbar-thumb {
        background: linear-gradient(180deg, #66A9FF, #9B7CFF) !important;
    }

    :root[data-theme="dark"] h1,
    :root[data-theme="dark"] h2,
    :root[data-theme="dark"] h3,
    :root[data-theme="dark"] h4,
    :root[data-theme="dark"] h5,
    :root[data-theme="dark"] h6,
    :root[data-theme="dark"] strong,
    :root[data-theme="dark"] label,
    :root[data-theme="dark"] .page-title,
    :root[data-theme="dark"] .section-title,
    :root[data-theme="dark"] .product-title,
    :root[data-theme="dark"] .product-name,
    :root[data-theme="dark"] .item-name,
    :root[data-theme="dark"] .line-price,
    :root[data-theme="dark"] .summary-row strong,
    :root[data-theme="dark"] .admin-toolbar-title strong,
    :root[data-theme="dark"] .metric-value {
        color: #FFFFFF !important;
    }

    :root[data-theme="dark"] p,
    :root[data-theme="dark"] .muted,
    :root[data-theme="dark"] .text-muted,
    :root[data-theme="dark"] .product-meta,
    :root[data-theme="dark"] .product-detail-line,
    :root[data-theme="dark"] .stock,
    :root[data-theme="dark"] .item-detail,
    :root[data-theme="dark"] .section-desc,
    :root[data-theme="dark"] .brand-subtitle,
    :root[data-theme="dark"] .admin-role,
    :root[data-theme="dark"] .stat-label,
    :root[data-theme="dark"] .metric-label,
    :root[data-theme="dark"] .nav-section {
        color: #B3B3B3 !important;
    }

    :root[data-theme="dark"] a:not(.btn):not(.btn-primary):not(.btn-secondary):not(.btn-danger):not(.sidebar-link):not(.nav-links a):not(.product-title):hover {
        color: #9B7CFF !important;
    }

    :root[data-theme="dark"] .site,
    :root[data-theme="dark"] .shop-shell,
    :root[data-theme="dark"] .cart-shell,
    :root[data-theme="dark"] .checkout-page,
    :root[data-theme="dark"] .checkout-shell,
    :root[data-theme="dark"] .payment-page,
    :root[data-theme="dark"] .success-page,
    :root[data-theme="dark"] .product-page,
    :root[data-theme="dark"] .tracking-page,
    :root[data-theme="dark"] .orders-page,
    :root[data-theme="dark"] .account-page,
    :root[data-theme="dark"] .account-shell,
    :root[data-theme="dark"] .auth-page,
    :root[data-theme="dark"] .register-page,
    :root[data-theme="dark"] .admin-shell,
    :root[data-theme="dark"] .main,
    :root[data-theme="dark"] .content,
    :root[data-theme="dark"] main {
        background:
            radial-gradient(circle at 80% 0%, rgba(102, 169, 255, 0.08), transparent 30%),
            radial-gradient(circle at 10% 12%, rgba(155, 124, 255, 0.06), transparent 26%),
            #0A0A0A !important;
        color: #FFFFFF !important;
    }

    :root[data-theme="dark"] .nav,
    :root[data-theme="dark"] .store-nav,
    :root[data-theme="dark"] .account-nav,
    :root[data-theme="dark"] .topbar,
    :root[data-theme="dark"] .sidebar,
    :root[data-theme="dark"] .filters {
        backdrop-filter: blur(18px);
        background: rgba(17, 17, 17, 0.94) !important;
        border-color: #2A2A2A !important;
        box-shadow: 0 16px 40px rgba(0, 0, 0, 0.34) !important;
        color: #FFFFFF !important;
    }

    :root[data-theme="dark"] .logo,
    :root[data-theme="dark"] .brand,
    :root[data-theme="dark"] .brand-name {
        color: #66A9FF !important;
        text-shadow: 0 0 24px rgba(102, 169, 255, 0.22);
    }

    :root[data-theme="dark"] .nav a,
    :root[data-theme="dark"] .nav-links a,
    :root[data-theme="dark"] .sidebar-link {
        color: #D6D6D6 !important;
    }

    :root[data-theme="dark"] .nav a:hover,
    :root[data-theme="dark"] .nav-links a:hover,
    :root[data-theme="dark"] .sidebar-link:hover,
    :root[data-theme="dark"] .sidebar-link.active {
        background: #1F1F1F !important;
        color: #FFFFFF !important;
        box-shadow: inset 3px 0 0 #66A9FF !important;
    }

    :root[data-theme="dark"] .panel,
    :root[data-theme="dark"] .card,
    :root[data-theme="dark"] .category-card,
    :root[data-theme="dark"] .product-card,
    :root[data-theme="dark"] .stat-card,
    :root[data-theme="dark"] .stat-item,
    :root[data-theme="dark"] .metric-card,
    :root[data-theme="dark"] .summary,
    :root[data-theme="dark"] .summary-card,
    :root[data-theme="dark"] .checkout-header,
    :root[data-theme="dark"] .checkout-note,
    :root[data-theme="dark"] .shop-sortbar,
    :root[data-theme="dark"] .admin-toolbar,
    :root[data-theme="dark"] .panel--soft,
    :root[data-theme="dark"] .empty,
    :root[data-theme="dark"] .modal,
    :root[data-theme="dark"] .modal-content,
    :root[data-theme="dark"] .dialog,
    :root[data-theme="dark"] .dropdown,
    :root[data-theme="dark"] .banner,
    :root[data-theme="dark"] .footer,
    :root[data-theme="dark"] .footer-bottom,
    :root[data-theme="dark"] .puff-pagination,
    :root[data-theme="dark"] [class*="bg-white"],
    :root[data-theme="dark"] .bg-white {
        background: rgba(31, 31, 31, 0.88) !important;
        border-color: #2A2A2A !important;
        box-shadow: 0 18px 48px rgba(0, 0, 0, 0.35) !important;
        color: #FFFFFF !important;
    }

    :root[data-theme="dark"] .product-media,
    :root[data-theme="dark"] .product-image,
    :root[data-theme="dark"] .hero-visual,
    :root[data-theme="dark"] .hero-product-image,
    :root[data-theme="dark"] .category-image-wrap,
    :root[data-theme="dark"] .product-thumb,
    :root[data-theme="dark"] .media,
    :root[data-theme="dark"] .image-shell,
    :root[data-theme="dark"] .preview,
    :root[data-theme="dark"] .loader,
    :root[data-theme="dark"] .loading {
        background:
            radial-gradient(circle at 50% 30%, rgba(102, 169, 255, 0.12), transparent 34%),
            linear-gradient(180deg, #181818 0%, #111111 100%) !important;
        border-color: #2A2A2A !important;
        color: #B3B3B3 !important;
    }

    :root[data-theme="dark"] .product-card:hover,
    :root[data-theme="dark"] .category-card:hover,
    :root[data-theme="dark"] .panel:hover,
    :root[data-theme="dark"] .stat-card:hover {
        border-color: rgba(102, 169, 255, 0.62) !important;
        box-shadow:
            0 24px 70px rgba(0, 0, 0, 0.45),
            0 0 0 1px rgba(102, 169, 255, 0.08) !important;
    }

    :root[data-theme="dark"] input,
    :root[data-theme="dark"] textarea,
    :root[data-theme="dark"] select,
    :root[data-theme="dark"] .form-control,
    :root[data-theme="dark"] .search-control,
    :root[data-theme="dark"] .filter-control,
    :root[data-theme="dark"] .sort-select,
    :root[data-theme="dark"] [type="text"],
    :root[data-theme="dark"] [type="email"],
    :root[data-theme="dark"] [type="password"],
    :root[data-theme="dark"] [type="number"],
    :root[data-theme="dark"] [type="tel"],
    :root[data-theme="dark"] [type="date"],
    :root[data-theme="dark"] [type="search"] {
        background: #111111 !important;
        border-color: #2A2A2A !important;
        color: #FFFFFF !important;
        box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.03) !important;
    }

    :root[data-theme="dark"] input::placeholder,
    :root[data-theme="dark"] textarea::placeholder {
        color: #808080 !important;
    }

    :root[data-theme="dark"] input:focus,
    :root[data-theme="dark"] textarea:focus,
    :root[data-theme="dark"] select:focus {
        border-color: #66A9FF !important;
        box-shadow: 0 0 0 3px rgba(102, 169, 255, 0.18) !important;
        outline: none !important;
    }

    :root[data-theme="dark"] option,
    :root[data-theme="dark"] optgroup {
        background: #111111 !important;
        color: #FFFFFF !important;
    }

    :root[data-theme="dark"] table,
    :root[data-theme="dark"] .table,
    :root[data-theme="dark"] .table-wrap {
        background: #181818 !important;
        border-color: #2A2A2A !important;
        color: #FFFFFF !important;
    }

    :root[data-theme="dark"] th,
    :root[data-theme="dark"] thead,
    :root[data-theme="dark"] .compact-table th {
        background: #111111 !important;
        border-color: #2A2A2A !important;
        color: #B3B3B3 !important;
    }

    :root[data-theme="dark"] td,
    :root[data-theme="dark"] tr,
    :root[data-theme="dark"] .cart-row,
    :root[data-theme="dark"] .select-all-row,
    :root[data-theme="dark"] .panel-header,
    :root[data-theme="dark"] .product-card .foot,
    :root[data-theme="dark"] .total-row,
    :root[data-theme="dark"] .recommendations,
    :root[data-theme="dark"] hr {
        border-color: #2A2A2A !important;
    }

    :root[data-theme="dark"] tr:hover,
    :root[data-theme="dark"] tbody tr:hover {
        background: rgba(255, 255, 255, 0.035) !important;
    }

    :root[data-theme="dark"] .btn-primary,
    :root[data-theme="dark"] button[type="submit"]:not(.btn-secondary):not(.btn-danger) {
        background: linear-gradient(135deg, #2563EB, #7C3AED) !important;
        border-color: rgba(102, 169, 255, 0.55) !important;
        color: #FFFFFF !important;
        box-shadow: 0 14px 34px rgba(37, 99, 235, 0.22) !important;
    }

    :root[data-theme="dark"] .btn-primary:hover,
    :root[data-theme="dark"] button[type="submit"]:not(.btn-secondary):not(.btn-danger):hover {
        filter: brightness(1.08);
    }

    :root[data-theme="dark"] .btn-secondary,
    :root[data-theme="dark"] .sort-pill,
    :root[data-theme="dark"] .puff-pagination__button,
    :root[data-theme="dark"] .puff-pagination__page,
    :root[data-theme="dark"] .pagination a {
        background: #111111 !important;
        border-color: #2A2A2A !important;
        color: #FFFFFF !important;
    }

    :root[data-theme="dark"] .btn-secondary:hover,
    :root[data-theme="dark"] .sort-pill:hover,
    :root[data-theme="dark"] .puff-pagination__button:hover,
    :root[data-theme="dark"] .puff-pagination__page:hover,
    :root[data-theme="dark"] .pagination a:hover {
        background: #1F1F1F !important;
        border-color: #66A9FF !important;
        color: #66A9FF !important;
    }

    :root[data-theme="dark"] .sort-pill.active,
    :root[data-theme="dark"] .puff-pagination__page.is-active,
    :root[data-theme="dark"] .pagination [aria-current="page"] span,
    :root[data-theme="dark"] .pagination .active span {
        background: #2563EB !important;
        border-color: #2563EB !important;
        color: #FFFFFF !important;
    }

    :root[data-theme="dark"] .btn-danger,
    :root[data-theme="dark"] .icon-btn.danger {
        background: rgba(127, 29, 29, 0.24) !important;
        border-color: rgba(248, 113, 113, 0.3) !important;
        color: #FCA5A5 !important;
    }

    :root[data-theme="dark"] .notice-success,
    :root[data-theme="dark"] .alert-success,
    :root[data-theme="dark"] .status-approved,
    :root[data-theme="dark"] .badge-green {
        background: rgba(22, 101, 52, 0.24) !important;
        border-color: rgba(134, 239, 172, 0.28) !important;
        color: #86EFAC !important;
    }

    :root[data-theme="dark"] .notice-error,
    :root[data-theme="dark"] .alert-error,
    :root[data-theme="dark"] .status-rejected,
    :root[data-theme="dark"] .badge-red {
        background: rgba(127, 29, 29, 0.26) !important;
        border-color: rgba(252, 165, 165, 0.28) !important;
        color: #FCA5A5 !important;
    }

    :root[data-theme="dark"] .notice-warning,
    :root[data-theme="dark"] .status-pending,
    :root[data-theme="dark"] .badge-yellow {
        background: rgba(120, 53, 15, 0.26) !important;
        border-color: rgba(250, 204, 21, 0.3) !important;
        color: #FACC15 !important;
    }

    :root[data-theme="dark"] .badge-gray,
    :root[data-theme="dark"] .status-chip,
    :root[data-theme="dark"] .product-badge,
    :root[data-theme="dark"] .feature-badge {
        background: #1F1F1F !important;
        border-color: #2A2A2A !important;
        color: #D6D6D6 !important;
    }

    :root[data-theme="dark"] .price,
    :root[data-theme="dark"] .stat-value,
    :root[data-theme="dark"] .product-price,
    :root[data-theme="dark"] .hero-eyebrow,
    :root[data-theme="dark"] .section-label {
        color: #66A9FF !important;
    }

    :root[data-theme="dark"] .old-price {
        color: #808080 !important;
    }

    :root[data-theme="dark"] iframe,
    :root[data-theme="dark"] embed,
    :root[data-theme="dark"] object,
    :root[data-theme="dark"] canvas {
        background-color: #0A0A0A !important;
    }

    :root[data-theme="dark"] img {
        background-color: transparent;
    }

    :root[data-theme="dark"] .shadow,
    :root[data-theme="dark"] .shadow-sm,
    :root[data-theme="dark"] .shadow-md,
    :root[data-theme="dark"] .shadow-lg,
    :root[data-theme="dark"] [class*="shadow"] {
        --tw-shadow-color: rgba(0, 0, 0, 0.55) !important;
        box-shadow: var(--shadow-md) !important;
    }

    :root[data-theme="dark"] .text-black,
    :root[data-theme="dark"] .text-gray-900,
    :root[data-theme="dark"] .text-gray-800,
    :root[data-theme="dark"] .text-slate-900,
    :root[data-theme="dark"] .text-slate-800 {
        color: #FFFFFF !important;
    }

    :root[data-theme="dark"] .text-gray-700,
    :root[data-theme="dark"] .text-gray-600,
    :root[data-theme="dark"] .text-slate-700,
    :root[data-theme="dark"] .text-slate-600 {
        color: #B3B3B3 !important;
    }

    :root[data-theme="dark"] .text-gray-500,
    :root[data-theme="dark"] .text-slate-500 {
        color: #808080 !important;
    }

    :root[data-theme="dark"] .border,
    :root[data-theme="dark"] .border-gray-100,
    :root[data-theme="dark"] .border-gray-200,
    :root[data-theme="dark"] .border-slate-100,
    :root[data-theme="dark"] .border-slate-200,
    :root[data-theme="dark"] [class*="border-gray"],
    :root[data-theme="dark"] [class*="border-slate"] {
        border-color: #2A2A2A !important;
    }

    :root[data-theme="dark"] .bg-gray-50,
    :root[data-theme="dark"] .bg-gray-100,
    :root[data-theme="dark"] .bg-slate-50,
    :root[data-theme="dark"] .bg-slate-100,
    :root[data-theme="dark"] [class*="bg-gray"],
    :root[data-theme="dark"] [class*="bg-slate"],
    :root[data-theme="dark"] [class*="bg-[#f"],
    :root[data-theme="dark"] [class*="bg-[#F"],
    :root[data-theme="dark"] [class*="bg-[white"],
    :root[data-theme="dark"] [class*="bg-[rgb(24"],
    :root[data-theme="dark"] [class*="bg-[rgb(25"] {
        background-color: #181818 !important;
    }

    :root[data-theme="dark"] [style*="background:#fff"],
    :root[data-theme="dark"] [style*="background: #fff"],
    :root[data-theme="dark"] [style*="background:#ffffff"],
    :root[data-theme="dark"] [style*="background: #ffffff"],
    :root[data-theme="dark"] [style*="background:#FFFFFF"],
    :root[data-theme="dark"] [style*="background: #FFFFFF"],
    :root[data-theme="dark"] [style*="background-color:#fff"],
    :root[data-theme="dark"] [style*="background-color: #fff"],
    :root[data-theme="dark"] [style*="background-color:#ffffff"],
    :root[data-theme="dark"] [style*="background-color: #ffffff"] {
        background: #181818 !important;
        background-color: #181818 !important;
    }

    :root[data-theme="dark"] [style*="color:#0"],
    :root[data-theme="dark"] [style*="color: #0"],
    :root[data-theme="dark"] [style*="color:#1"],
    :root[data-theme="dark"] [style*="color: #1"],
    :root[data-theme="dark"] [style*="color:#334155"],
    :root[data-theme="dark"] [style*="color: #334155"] {
        color: #FFFFFF !important;
    }

    :root[data-theme="dark"] .chatbot-widget,
    :root[data-theme="dark"] .chatbot-header,
    :root[data-theme="dark"] .chatbot-body,
    :root[data-theme="dark"] .chatbot-footer,
    :root[data-theme="dark"] .chatbot-suggestions {
        background: #181818 !important;
        border-color: #2A2A2A !important;
        color: #FFFFFF !important;
    }

    :root[data-theme="dark"] .chatbot-message.bot {
        background: #111111 !important;
        color: #FFFFFF !important;
    }

    :root[data-theme="dark"] .chatbot-message.user {
        background: #2563EB !important;
        color: #FFFFFF !important;
    }

    :root[data-theme="dark"] * {
        transition:
            background-color 0.18s ease,
            border-color 0.18s ease,
            color 0.18s ease,
            box-shadow 0.18s ease;
    }

    @media (prefers-reduced-motion: reduce) {
        :root[data-theme="dark"] * {
            transition: none !important;
        }
    }
</style>
