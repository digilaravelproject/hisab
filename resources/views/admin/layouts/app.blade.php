<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — Vitai Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap"
        rel="stylesheet">
    <style>
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        :root {
            --primary: #1B3A6B;
            --primary-light: #2D5499;
            --primary-dark: #0D2454;
            --primary-xlight: #EEF2FA;
            --bg: #F0F4F8;
            --surface: #FFFFFF;
            --surface-2: #F8FAFC;
            --text-primary: #1B3A6B;
            --text-secondary: #6B7280;
            --text-muted: #9CA3AF;
            --border: #E5EAF2;
            --success: #16A34A;
            --success-bg: #F0FDF4;
            --danger: #DC2626;
            --danger-bg: #FEF2F2;
            --warning: #D97706;
            --warning-bg: #FFFBEB;
            --info: #2563EB;
            --info-bg: #EFF6FF;
            --sidebar-w: 260px;
        }

        body {
            font-family: 'Sora', sans-serif;
            background: var(--bg);
            color: var(--text-primary);
            display: flex;
            min-height: 100vh;
            font-size: 14px;
        }

        /* ━━━━━━━━━━━━━━━━━━━━━━━━━━━━ SIDEBAR ━━━━━━━━━━━━━━━━━━━━━━━━━━━━ */
        .sidebar {
            width: var(--sidebar-w);
            min-height: 100vh;
            background: var(--primary);
            display: flex;
            flex-direction: column;
            position: fixed;
            left: 0;
            top: 0;
            bottom: 0;
            z-index: 100;
            transition: transform 0.3s ease;
        }

        .sidebar-brand {
            padding: 1.4rem 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .sidebar-brand .icon {
            width: 38px;
            height: 38px;
            background: rgba(255, 255, 255, 0.13);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 17px;
            border: 1px solid rgba(255, 255, 255, 0.18);
            flex-shrink: 0;
        }

        .sidebar-brand .name {
            font-size: 16px;
            font-weight: 700;
            color: #fff;
            letter-spacing: -0.2px;
        }

        .sidebar-brand .sub {
            font-size: 10px;
            color: rgba(255, 255, 255, 0.4);
            font-family: 'JetBrains Mono', monospace;
            letter-spacing: 1.5px;
            text-transform: uppercase;
        }

        /* Nav */
        .sidebar-nav {
            flex: 1;
            overflow-y: auto;
            padding: 1rem 0;
            scrollbar-width: thin;
            scrollbar-color: rgba(255, 255, 255, 0.1) transparent;
        }

        .nav-section-label {
            font-size: 10px;
            font-weight: 600;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: rgba(255, 255, 255, 0.3);
            padding: 0.8rem 1.5rem 0.4rem;
            font-family: 'JetBrains Mono', monospace;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 11px;
            padding: 0.6rem 1.5rem;
            color: rgba(255, 255, 255, 0.65);
            text-decoration: none;
            font-size: 13.5px;
            font-weight: 400;
            transition: all 0.15s;
            position: relative;
            border-radius: 0;
            margin: 1px 0;
        }

        .nav-item .nav-icon {
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            font-size: 15px;
            flex-shrink: 0;
            background: transparent;
            transition: all 0.15s;
        }

        .nav-item:hover {
            color: #fff;
            background: rgba(255, 255, 255, 0.07);
        }

        .nav-item:hover .nav-icon {
            background: rgba(255, 255, 255, 0.1);
        }

        .nav-item.active {
            color: #fff;
            background: rgba(255, 255, 255, 0.12);
        }

        .nav-item.active .nav-icon {
            background: rgba(255, 255, 255, 0.18);
        }

        .nav-item.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 4px;
            bottom: 4px;
            width: 3px;
            background: #fff;
            border-radius: 0 3px 3px 0;
        }

        .nav-badge {
            margin-left: auto;
            background: var(--danger);
            color: #fff;
            font-size: 10px;
            font-weight: 600;
            padding: 2px 7px;
            border-radius: 10px;
            font-family: 'JetBrains Mono', monospace;
        }

        .nav-badge.success {
            background: var(--success);
        }

        .nav-badge.warning {
            background: var(--warning);
        }

        /* Submenu */
        .nav-submenu {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.25s ease;
        }

        .nav-submenu.open {
            max-height: 300px;
        }

        .nav-submenu .nav-item {
            padding-left: 3.8rem;
            font-size: 13px;
        }

        .nav-parent .arrow {
            margin-left: auto;
            font-size: 11px;
            transition: transform 0.2s;
            opacity: 0.5;
        }

        .nav-parent.open .arrow {
            transform: rotate(90deg);
        }

        /* Sidebar Footer */
        .sidebar-footer {
            padding: 1rem 1.5rem;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
        }

        .admin-profile {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .admin-avatar {
            width: 36px;
            height: 36px;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            font-weight: 600;
            color: #fff;
            border: 1.5px solid rgba(255, 255, 255, 0.2);
            flex-shrink: 0;
        }

        .admin-info .name {
            font-size: 13px;
            font-weight: 600;
            color: #fff;
        }

        .admin-info .role {
            font-size: 10px;
            color: rgba(255, 255, 255, 0.4);
            font-family: 'JetBrains Mono', monospace;
        }

        .logout-btn {
            margin-left: auto;
            width: 30px;
            height: 30px;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 14px;
            color: rgba(255, 255, 255, 0.6);
            transition: all 0.15s;
            text-decoration: none;
        }

        .logout-btn:hover {
            background: rgba(220, 38, 38, 0.3);
            border-color: rgba(220, 38, 38, 0.4);
            color: #fff;
        }

        /* ━━━━━━━━━━━━━━━━━━━━━━━━━━━━ MAIN CONTENT ━━━━━━━━━━━━━━━━━━━━━━━ */
        .main-wrapper {
            margin-left: var(--sidebar-w);
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* Top Header */
        .topbar {
            background: var(--surface);
            border-bottom: 1px solid var(--border);
            padding: 0 1.5rem;
            height: 60px;
            display: flex;
            align-items: center;
            gap: 1rem;
            position: sticky;
            top: 0;
            z-index: 50;
        }

        .topbar-left {
            display: flex;
            align-items: center;
            gap: 12px;
            flex: 1;
        }

        .sidebar-toggle {
            width: 34px;
            height: 34px;
            border: 1.5px solid var(--border);
            border-radius: 8px;
            background: transparent;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            gap: 4px;
            padding: 8px;
            transition: all 0.15s;
        }

        .sidebar-toggle span {
            display: block;
            width: 100%;
            height: 1.5px;
            background: var(--text-secondary);
            border-radius: 2px;
            transition: all 0.2s;
        }

        .sidebar-toggle:hover {
            background: var(--bg);
        }

        .page-breadcrumb {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 13px;
            color: var(--text-secondary);
        }

        .page-breadcrumb .current {
            color: var(--text-primary);
            font-weight: 600;
        }

        /* Search */
        .topbar-search {
            position: relative;
            width: 240px;
        }

        .topbar-search input {
            width: 100%;
            padding: 0.45rem 1rem 0.45rem 2.2rem;
            border: 1.5px solid var(--border);
            border-radius: 8px;
            font-family: 'Sora', sans-serif;
            font-size: 13px;
            background: var(--bg);
            color: var(--text-primary);
            outline: none;
            transition: all 0.2s;
        }

        .topbar-search input:focus {
            border-color: var(--primary);
            background: var(--surface);
        }

        .topbar-search .search-icon {
            position: absolute;
            left: 9px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 13px;
            color: var(--text-muted);
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .topbar-btn {
            position: relative;
            width: 36px;
            height: 36px;
            border: 1.5px solid var(--border);
            border-radius: 9px;
            background: transparent;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 15px;
            color: var(--text-secondary);
            transition: all 0.15s;
            text-decoration: none;
        }

        .topbar-btn:hover {
            background: var(--bg);
            color: var(--primary);
        }

        .notif-dot {
            position: absolute;
            top: 4px;
            right: 4px;
            width: 8px;
            height: 8px;
            background: var(--danger);
            border-radius: 50%;
            border: 2px solid var(--surface);
        }

        /* Page Content */
        .page-content {
            flex: 1;
            padding: 1.5rem;
        }

        .page-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            margin-bottom: 1.5rem;
        }

        .page-header h1 {
            font-size: 1.4rem;
            font-weight: 700;
            color: var(--text-primary);
            letter-spacing: -0.3px;
        }

        .page-header p {
            color: var(--text-secondary);
            font-size: 13px;
            margin-top: 3px;
        }

        /* Alerts */
        .alert {
            padding: 0.75rem 1rem;
            border-radius: 10px;
            margin-bottom: 1rem;
            font-size: 13.5px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .alert-success {
            background: var(--success-bg);
            color: var(--success);
            border: 1px solid #BBF7D0;
        }

        .alert-error {
            background: var(--danger-bg);
            color: var(--danger);
            border: 1px solid #FECACA;
        }

        .alert-warning {
            background: var(--warning-bg);
            color: var(--warning);
            border: 1px solid #FDE68A;
        }

        .alert-info {
            background: var(--info-bg);
            color: var(--info);
            border: 1px solid #BFDBFE;
        }

        /* Footer */
        .page-footer {
            padding: 1rem 1.5rem;
            border-top: 1px solid var(--border);
            background: var(--surface);
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 12px;
            color: var(--text-muted);
        }

        .footer-right {
            font-family: 'JetBrains Mono', monospace;
        }

        /* Mobile */
        .overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 99;
        }

        @media (max-width: 900px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.open {
                transform: translateX(0);
            }

            .main-wrapper {
                margin-left: 0;
            }

            .overlay.show {
                display: block;
            }

            .topbar-search {
                width: 160px;
            }
        }

        @media (max-width: 600px) {
            .topbar-search {
                display: none;
            }

            .page-content {
                padding: 1rem;
            }
        }
    </style>
    @stack('styles')
</head>

<body>

    {{-- Mobile Overlay --}}
    <div class="overlay" id="overlay" onclick="closeSidebar()"></div>

    {{-- ━━━━━━━━━━━━━━ SIDEBAR ━━━━━━━━━━━━━━ --}}
    <aside class="sidebar" id="sidebar">

        {{-- Brand --}}
        <div class="sidebar-brand">
            <div class="icon">💰</div>
            <div>
                <div class="name">Vitai Finance</div>
                <div class="sub">Admin Portal</div>
            </div>
        </div>

        {{-- Navigation --}}
        <nav class="sidebar-nav">

            {{-- Main --}}
            <div class="nav-section-label">Main</div>

            <a href="{{ route('admin.dashboard') }}"
                class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <span class="nav-icon">🏠</span>
                Dashboard
            </a>

            <a href="{{ route('admin.users.index') }}"
                class="nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <span class="nav-icon">👥</span>
                Users
                <span class="nav-badge">{{ \App\Models\User::count() }}</span>
            </a>

            {{-- Transactions (with submenu) --}}
            <div>
                <a class="nav-item nav-parent {{ request()->routeIs('admin.transactions.*') ? 'active open' : '' }}"
                    onclick="toggleSubmenu(this)" style="cursor:pointer;">
                    <span class="nav-icon">💳</span>
                    Transactions
                    <span class="arrow">▶</span>
                </a>
                <div class="nav-submenu {{ request()->routeIs('admin.transactions.*') ? 'open' : '' }}">
                    <a href="{{ route('admin.transactions.index') }}"
                        class="nav-item {{ request()->routeIs('admin.transactions.index') ? 'active' : '' }}">
                        All Transactions
                    </a>
                    <a href="{{ route('admin.transactions.uncategorized') }}"
                        class="nav-item {{ request()->routeIs('admin.transactions.uncategorized') ? 'active' : '' }}">
                        Uncategorized
                        <span class="nav-badge warning">!</span>
                    </a>
                </div>
            </div>

            {{-- Finance --}}
            <div class="nav-section-label">Finance</div>

            <a href="{{ route('admin.businesses.index') }}"
                class="nav-item {{ request()->routeIs('admin.businesses.*') ? 'active' : '' }}">
                <span class="nav-icon">🏪</span>
                Businesses
            </a>

            <a href="{{ route('admin.categories.index') }}"
                class="nav-item {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                <span class="nav-icon">🏷️</span>
                Categories
            </a>

            <a href="{{ route('admin.budgets.index') }}"
                class="nav-item {{ request()->routeIs('admin.budgets.*') ? 'active' : '' }}">
                <span class="nav-icon">📊</span>
                Budgets
            </a>

            <a href="{{ route('admin.bills.index') }}"
                class="nav-item {{ request()->routeIs('admin.bills.*') ? 'active' : '' }}">
                <span class="nav-icon">🧾</span>
                Bills
            </a>

            {{-- Reports --}}
            <div class="nav-section-label">Reports</div>

            <a href="{{ route('admin.reports.monthly') }}"
                class="nav-item {{ request()->routeIs('admin.reports.monthly') ? 'active' : '' }}">
                <span class="nav-icon">📅</span>
                Monthly Report
            </a>

            <a href="{{ route('admin.reports.yearly') }}"
                class="nav-item {{ request()->routeIs('admin.reports.yearly') ? 'active' : '' }}">
                <span class="nav-icon">📈</span>
                Yearly Report
            </a>

            <a href="{{ route('admin.reports.export') }}"
                class="nav-item {{ request()->routeIs('admin.reports.export') ? 'active' : '' }}">
                <span class="nav-icon">📤</span>
                Export Data
            </a>

            {{-- System --}}
            <div class="nav-section-label">System</div>

            <a href="{{ route('admin.static-pages.index') }}"
                class="nav-item {{ request()->routeIs('admin.static-pages.*') ? 'active' : '' }}">
                <span class="nav-icon">📄</span>
                Static Pages
            </a>

            <a href="{{ route('admin.contact-queries.index') }}"
                class="nav-item {{ request()->routeIs('admin.contact-queries.*') ? 'active' : '' }}">
                <span class="nav-icon">✉️</span>
                Contact Us Enquiries
            </a>

            <a href="{{ route('admin.settings') }}"
                class="nav-item {{ request()->routeIs('admin.settings') ? 'active' : '' }}">
                <span class="nav-icon">⚙️</span>
                Settings
            </a>

            <a href="{{ route('admin.logs') }}"
                class="nav-item {{ request()->routeIs('admin.logs') ? 'active' : '' }}">
                <span class="nav-icon">📋</span>
                Activity Logs
            </a>

        </nav>

        {{-- Admin Profile --}}
        <div class="sidebar-footer">
            <div class="admin-profile">
                <div class="admin-avatar">
                    {{ strtoupper(substr(auth('admin')->user()->name ?? 'A', 0, 1)) }}
                </div>
                <div class="admin-info">
                    <div class="name">{{ auth('admin')->user()->name ?? 'Admin' }}</div>
                    <div class="role">SUPER ADMIN</div>
                </div>
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit" class="logout-btn" title="Logout">🚪</button>
                </form>
            </div>
        </div>

    </aside>

    {{-- ━━━━━━━━━━━━━━ MAIN WRAPPER ━━━━━━━━━━━━━━ --}}
    <div class="main-wrapper">

        {{-- Topbar --}}
        <header class="topbar">
            <div class="topbar-left">
                <button class="sidebar-toggle" onclick="toggleSidebar()">
                    <span></span><span></span><span></span>
                </button>
                <div class="page-breadcrumb">
                    <span>Admin</span>
                    <span>›</span>
                    <span class="current">@yield('breadcrumb', 'Dashboard')</span>
                </div>
            </div>

            <div class="topbar-search">
                <span class="search-icon">🔍</span>
                <input type="text" placeholder="Search...">
            </div>

            <div class="topbar-right">
                <a href="{{ route('admin.reports.monthly') }}" class="topbar-btn" title="Reports">📊</a>
                <a href="#" class="topbar-btn" title="Notifications">
                    🔔
                    <span class="notif-dot"></span>
                </a>
                <a href="{{ route('admin.settings') }}" class="topbar-btn" title="Settings">⚙️</a>
            </div>
        </header>

        {{-- Page Content --}}
        <main class="page-content">

            {{-- Flash Messages --}}
            @if (session('success'))
                <div class="alert alert-success">✅ {{ session('success') }}</div>
            @endif

            @if (session('error'))
                <div class="alert alert-error">⚠ {{ session('error') }}</div>
            @endif

            @if (session('warning'))
                <div class="alert alert-warning">⚡ {{ session('warning') }}</div>
            @endif

            @yield('content')
        </main>

        {{-- Footer --}}
        <footer class="page-footer">
            <span>© {{ date('Y') }} Vitai Finance. All rights reserved.</span>
            <span class="footer-right">Laravel 13 · PHP {{ phpversion() }} · v1.0.0</span>
        </footer>
    </div>

    <script>
        function toggleSidebar() {
            const s = document.getElementById('sidebar');
            const o = document.getElementById('overlay');
            s.classList.toggle('open');
            o.classList.toggle('show');
        }

        function closeSidebar() {
            document.getElementById('sidebar').classList.remove('open');
            document.getElementById('overlay').classList.remove('show');
        }

        function toggleSubmenu(el) {
            el.classList.toggle('open');
            const sub = el.nextElementSibling;
            if (sub) sub.classList.toggle('open');
        }
    </script>

    @stack('scripts')
</body>

</html>
