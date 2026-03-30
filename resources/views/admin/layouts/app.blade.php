<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — Vitai Admin</title>

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap"
        rel="stylesheet">

    {{-- Tailwind CSS CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- DataTables CSS --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">

    {{-- jQuery --}}
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        navy: {
                            DEFAULT: '#1B3A6B',
                            light: '#2D5499',
                            dark: '#0D2454',
                            xlight: '#EEF2FA',
                        }
                    },
                    fontFamily: {
                        sora: ['Sora', 'sans-serif'],
                        mono: ['JetBrains Mono', 'monospace'],
                    }
                }
            }
        }
    </script>

    <style>
        body {
            font-family: 'Sora', sans-serif;
        }

        /* Sidebar scrollbar */
        .sidebar-nav::-webkit-scrollbar {
            width: 4px;
        }

        .sidebar-nav::-webkit-scrollbar-track {
            background: transparent;
        }

        .sidebar-nav::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 4px;
        }

        /* Active nav indicator */
        .nav-active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 4px;
            bottom: 4px;
            width: 3px;
            background: #fff;
            border-radius: 0 3px 3px 0;
        }

        /* Submenu transition */
        .submenu {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.25s ease;
        }

        .submenu.open {
            max-height: 300px;
        }

        .arrow-icon {
            transition: transform 0.2s;
        }

        .nav-parent.open .arrow-icon {
            transform: rotate(90deg);
        }

        /* ── DataTables Custom Styles ── */
        .dataTables_wrapper {
            font-family: 'Sora', sans-serif;
            font-size: 13px;
        }

        /* Search & Length */
        .dataTables_wrapper .dataTables_filter input {
            border: 1.5px solid #E5EAF2;
            border-radius: 8px;
            padding: 6px 12px;
            font-family: 'Sora', sans-serif;
            font-size: 13px;
            outline: none;
            transition: border-color 0.2s;
            margin-left: 6px;
        }

        .dataTables_wrapper .dataTables_filter input:focus {
            border-color: #1B3A6B;
        }

        .dataTables_wrapper .dataTables_length select {
            border: 1.5px solid #E5EAF2;
            border-radius: 8px;
            padding: 5px 10px;
            font-family: 'Sora', sans-serif;
            font-size: 13px;
            outline: none;
            margin: 0 6px;
        }

        /* Table */
        table.dataTable {
            width: 100% !important;
            border-collapse: collapse !important;
        }

        table.dataTable thead th {
            background: #EEF2FA;
            color: #1B3A6B;
            font-weight: 600;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            padding: 12px 16px !important;
            border-bottom: 2px solid #E5EAF2 !important;
            border-top: none !important;
            white-space: nowrap;
        }

        table.dataTable thead th.sorting::after,
        table.dataTable thead th.sorting_asc::after,
        table.dataTable thead th.sorting_desc::after {
            opacity: 0.5;
        }

        table.dataTable tbody td {
            padding: 13px 16px !important;
            border-bottom: 1px solid #F4F6FB !important;
            vertical-align: middle;
            color: #1B3A6B;
            font-size: 13.5px;
        }

        table.dataTable tbody tr:hover td {
            background: #F8FAFC;
        }

        table.dataTable tbody tr:last-child td {
            border-bottom: none !important;
        }

        /* Pagination */
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            padding: 4px 10px !important;
            border-radius: 7px !important;
            border: 1.5px solid #E5EAF2 !important;
            margin: 0 2px !important;
            font-size: 12px !important;
            color: #1B3A6B !important;
            background: #fff !important;
            cursor: pointer;
            transition: all 0.15s;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background: #EEF2FA !important;
            border-color: #1B3A6B !important;
            color: #1B3A6B !important;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background: #1B3A6B !important;
            border-color: #1B3A6B !important;
            color: #fff !important;
            font-weight: 600;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.disabled {
            opacity: 0.4;
            cursor: not-allowed;
        }

        /* Info text */
        .dataTables_wrapper .dataTables_info {
            font-size: 12.5px;
            color: #6B7280;
            padding-top: 10px !important;
        }

        /* Top/Bottom row */
        .dataTables_wrapper .dataTables_filter,
        .dataTables_wrapper .dataTables_length {
            padding: 12px 16px;
        }

        .dataTables_wrapper .dataTables_paginate {
            padding: 10px 16px;
        }
    </style>

    @stack('styles')
</head>

<body class="bg-[#F0F4F8] text-navy min-h-screen flex">

    {{-- Mobile Overlay --}}
    <div id="overlay" onclick="closeSidebar()" class="hidden fixed inset-0 bg-black/50 z-[99] lg:hidden"></div>

    {{-- ━━━━━━━━ SIDEBAR ━━━━━━━━ --}}
    <aside id="sidebar"
        class="fixed left-0 top-0 bottom-0 w-[260px] bg-navy flex flex-col z-[100]
                  -translate-x-full lg:translate-x-0 transition-transform duration-300">

        {{-- Brand --}}
        <div class="flex items-center gap-3 px-6 py-5 border-b border-white/10">
            <div
                class="w-10 h-10 bg-white/15 rounded-xl flex items-center justify-center
                        text-lg border border-white/20 shrink-0">
                💰</div>
            <div>
                <div class="text-white font-bold text-[15px]">Vitai Finance</div>
                <div class="text-white/40 text-[10px] font-mono tracking-widest uppercase">Admin Portal</div>
            </div>
        </div>

        {{-- Nav --}}
        <nav class="sidebar-nav flex-1 overflow-y-auto py-3">

            <div
                class="text-[10px] font-mono font-semibold tracking-widest uppercase
                        text-white/30 px-6 pt-3 pb-1">
                Main</div>

            <a href="{{ route('admin.dashboard') }}"
                class="nav-item relative flex items-center gap-3 px-6 py-[9px] text-[13.5px]
                      no-underline transition-all duration-150
                      {{ request()->routeIs('admin.dashboard')
                          ? 'text-white bg-white/12 nav-active'
                          : 'text-white/65 hover:text-white hover:bg-white/7' }}">
                <span
                    class="w-8 h-8 flex items-center justify-center rounded-lg
                             {{ request()->routeIs('admin.dashboard') ? 'bg-white/18' : 'hover:bg-white/10' }}">🏠</span>
                Dashboard
            </a>

            <a href="{{ route('admin.users.index') }}"
                class="nav-item relative flex items-center gap-3 px-6 py-[9px] text-[13.5px]
                      no-underline transition-all duration-150
                      {{ request()->routeIs('admin.users.*')
                          ? 'text-white bg-white/12 nav-active'
                          : 'text-white/65 hover:text-white hover:bg-white/7' }}">
                <span class="w-8 h-8 flex items-center justify-center rounded-lg">👥</span>
                Users
                <span
                    class="ml-auto bg-red-500 text-white text-[10px] font-bold
                             px-2 py-[2px] rounded-full font-mono">
                    {{ \App\Models\User::count() }}
                </span>
            </a>

            {{-- Transactions submenu --}}
            <div>
                <a class="nav-parent relative flex items-center gap-3 px-6 py-[9px] text-[13.5px]
                          cursor-pointer no-underline transition-all duration-150
                          {{ request()->routeIs('admin.transactions.*')
                              ? 'text-white bg-white/12 nav-active open'
                              : 'text-white/65 hover:text-white hover:bg-white/7' }}"
                    onclick="toggleSubmenu(this)">
                    <span class="w-8 h-8 flex items-center justify-center rounded-lg">💳</span>
                    Transactions
                    <span class="arrow-icon ml-auto text-[11px] opacity-50">▶</span>
                </a>
                <div class="submenu {{ request()->routeIs('admin.transactions.*') ? 'open' : '' }}">
                    <a href="{{ route('admin.transactions.index') }}"
                        class="relative flex items-center gap-3 pl-[3.8rem] pr-6 py-[8px] text-[13px]
                              no-underline transition-all duration-150
                              {{ request()->routeIs('admin.transactions.index')
                                  ? 'text-white bg-white/12 nav-active'
                                  : 'text-white/65 hover:text-white hover:bg-white/7' }}">
                        All Transactions
                    </a>
                    <a href="{{ route('admin.transactions.uncategorized') }}"
                        class="relative flex items-center gap-3 pl-[3.8rem] pr-6 py-[8px] text-[13px]
                              no-underline transition-all duration-150
                              {{ request()->routeIs('admin.transactions.uncategorized')
                                  ? 'text-white bg-white/12 nav-active'
                                  : 'text-white/65 hover:text-white hover:bg-white/7' }}">
                        Uncategorized
                        <span
                            class="ml-auto bg-amber-500 text-white text-[10px] font-bold
                                     px-2 py-[2px] rounded-full">!</span>
                    </a>
                </div>
            </div>

            {{-- Finance --}}
            <div
                class="text-[10px] font-mono font-semibold tracking-widest uppercase
                        text-white/30 px-6 pt-4 pb-1">
                Finance</div>

            @foreach ([['route' => 'admin.businesses.index', 'icon' => '🏪', 'label' => 'Businesses'], ['route' => 'admin.categories.index', 'icon' => '🏷️', 'label' => 'Categories'], ['route' => 'admin.budgets.index', 'icon' => '📊', 'label' => 'Budgets'], ['route' => 'admin.bills.index', 'icon' => '🧾', 'label' => 'Bills']] as $item)
                <a href="{{ route($item['route']) }}"
                    class="nav-item relative flex items-center gap-3 px-6 py-[9px] text-[13.5px]
                      no-underline transition-all duration-150
                      {{ request()->routeIs(str_replace('.index', '.*', $item['route']))
                          ? 'text-white bg-white/12 nav-active'
                          : 'text-white/65 hover:text-white hover:bg-white/7' }}">
                    <span class="w-8 h-8 flex items-center justify-center rounded-lg">{{ $item['icon'] }}</span>
                    {{ $item['label'] }}
                </a>
            @endforeach

            {{-- Reports --}}
            <div
                class="text-[10px] font-mono font-semibold tracking-widest uppercase
                        text-white/30 px-6 pt-4 pb-1">
                Reports</div>

            @foreach ([['route' => 'admin.reports.monthly', 'icon' => '📅', 'label' => 'Monthly Report'], ['route' => 'admin.reports.yearly', 'icon' => '📈', 'label' => 'Yearly Report'], ['route' => 'admin.reports.export', 'icon' => '📤', 'label' => 'Export Data']] as $item)
                <a href="{{ route($item['route']) }}"
                    class="nav-item relative flex items-center gap-3 px-6 py-[9px] text-[13.5px]
                      no-underline transition-all duration-150
                      {{ request()->routeIs($item['route'])
                          ? 'text-white bg-white/12 nav-active'
                          : 'text-white/65 hover:text-white hover:bg-white/7' }}">
                    <span class="w-8 h-8 flex items-center justify-center rounded-lg">{{ $item['icon'] }}</span>
                    {{ $item['label'] }}
                </a>
            @endforeach

            {{-- System --}}
            <div
                class="text-[10px] font-mono font-semibold tracking-widest uppercase
                        text-white/30 px-6 pt-4 pb-1">
                System</div>

            @foreach ([['route' => 'admin.static-pages.index', 'icon' => '📄', 'label' => 'Static Pages'], ['route' => 'admin.contact-queries.index', 'icon' => '✉️', 'label' => 'Contact Enquiries'], ['route' => 'admin.settings', 'icon' => '⚙️', 'label' => 'Settings'], ['route' => 'admin.logs', 'icon' => '📋', 'label' => 'Activity Logs']] as $item)
                <a href="{{ route($item['route']) }}"
                    class="nav-item relative flex items-center gap-3 px-6 py-[9px] text-[13.5px]
                      no-underline transition-all duration-150
                      {{ request()->routeIs($item['route'])
                          ? 'text-white bg-white/12 nav-active'
                          : 'text-white/65 hover:text-white hover:bg-white/7' }}">
                    <span class="w-8 h-8 flex items-center justify-center rounded-lg">{{ $item['icon'] }}</span>
                    {{ $item['label'] }}
                </a>
            @endforeach

        </nav>

        {{-- Admin Profile --}}
        <div class="px-6 py-4 border-t border-white/10">
            <div class="flex items-center gap-3">
                <div
                    class="w-9 h-9 bg-white/15 rounded-full flex items-center justify-center
                            text-white font-semibold text-[13px] border border-white/20 shrink-0">
                    {{ strtoupper(substr(auth('admin')->user()->name ?? 'A', 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <div class="text-white text-[13px] font-semibold truncate">
                        {{ auth('admin')->user()->name ?? 'Admin' }}
                    </div>
                    <div class="text-white/40 text-[10px] font-mono uppercase tracking-wide">Super Admin</div>
                </div>
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit"
                        class="w-8 h-8 bg-white/8 border border-white/10 rounded-lg flex items-center
                                   justify-center text-white/60 cursor-pointer text-sm transition-all
                                   hover:bg-red-500/30 hover:border-red-400/40 hover:text-white">
                        🚪
                    </button>
                </form>
            </div>
        </div>

    </aside>

    {{-- ━━━━━━━━ MAIN WRAPPER ━━━━━━━━ --}}
    <div class="lg:ml-[260px] flex-1 flex flex-col min-h-screen">

        {{-- Topbar --}}
        <header
            class="bg-white border-b border-[#E5EAF2] h-[60px] flex items-center
                       gap-4 px-6 sticky top-0 z-50">

            {{-- Left --}}
            <div class="flex items-center gap-3 flex-1">
                <button onclick="toggleSidebar()"
                    class="w-9 h-9 border border-[#E5EAF2] rounded-lg bg-transparent cursor-pointer
                               flex flex-col items-center justify-center gap-[4px] p-2 hover:bg-[#F0F4F8]
                               transition-all lg:hidden">
                    <span class="block w-full h-[1.5px] bg-gray-500 rounded"></span>
                    <span class="block w-full h-[1.5px] bg-gray-500 rounded"></span>
                    <span class="block w-full h-[1.5px] bg-gray-500 rounded"></span>
                </button>

                <div class="flex items-center gap-2 text-[13px] text-gray-500">
                    <span>Admin</span>
                    <span>›</span>
                    <span class="text-navy font-semibold">@yield('breadcrumb', 'Dashboard')</span>
                </div>
            </div>

            {{-- Search --}}
            <div class="relative w-[220px] hidden md:block">
                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-[13px] text-gray-400">🔍</span>
                <input type="text" placeholder="Search..."
                    class="w-full pl-9 pr-4 py-[7px] border border-[#E5EAF2] rounded-lg text-[13px]
                              bg-[#F0F4F8] text-navy font-[Sora] outline-none transition-all
                              focus:border-navy focus:bg-white">
            </div>

            {{-- Right --}}
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.reports.monthly') }}"
                    class="w-9 h-9 border border-[#E5EAF2] rounded-[9px] flex items-center justify-center
                          text-[15px] text-gray-500 no-underline hover:bg-[#F0F4F8] hover:text-navy transition-all">
                    📊
                </a>
                <a href="#"
                    class="relative w-9 h-9 border border-[#E5EAF2] rounded-[9px] flex items-center
                          justify-center text-[15px] text-gray-500 no-underline hover:bg-[#F0F4F8]
                          hover:text-navy transition-all">
                    🔔
                    <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full border-2 border-white"></span>
                </a>
                <a href="{{ route('admin.settings') }}"
                    class="w-9 h-9 border border-[#E5EAF2] rounded-[9px] flex items-center justify-center
                          text-[15px] text-gray-500 no-underline hover:bg-[#F0F4F8] hover:text-navy transition-all">
                    ⚙️
                </a>
            </div>
        </header>

        {{-- Page Content --}}
        <main class="flex-1 p-6">

            {{-- Flash Messages --}}
            @if (session('success'))
                <div
                    class="flex items-center gap-2 px-4 py-3 mb-4 rounded-xl text-[13.5px]
                            bg-green-50 text-green-700 border border-green-200">
                    ✅ {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div
                    class="flex items-center gap-2 px-4 py-3 mb-4 rounded-xl text-[13.5px]
                            bg-red-50 text-red-600 border border-red-200">
                    ⚠ {{ session('error') }}
                </div>
            @endif

            @if (session('warning'))
                <div
                    class="flex items-center gap-2 px-4 py-3 mb-4 rounded-xl text-[13.5px]
                            bg-amber-50 text-amber-700 border border-amber-200">
                    ⚡ {{ session('warning') }}
                </div>
            @endif

            @yield('content')
        </main>

        {{-- Footer --}}
        <footer
            class="px-6 py-4 border-t border-[#E5EAF2] bg-white flex items-center
                       justify-between text-[12px] text-gray-400">
            <span>© {{ date('Y') }} Vitai Finance. All rights reserved.</span>
            <span class="font-mono">Laravel 13 · PHP {{ phpversion() }} · v1.0.0</span>
        </footer>

    </div>

    {{-- DataTables JS --}}
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>

    <script>
        // Sidebar toggle
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('-translate-x-full');
            document.getElementById('overlay').classList.toggle('hidden');
        }

        function closeSidebar() {
            document.getElementById('sidebar').classList.add('-translate-x-full');
            document.getElementById('overlay').classList.add('hidden');
        }

        // Submenu toggle
        function toggleSubmenu(el) {
            el.classList.toggle('open');
            const sub = el.nextElementSibling;
            if (sub) sub.classList.toggle('open');
        }

        // Init DataTable — har page par jo bhi .data-table class ho
        $(document).ready(function() {
            if ($('.data-table').length) {
                $('.data-table').DataTable({
                    responsive: true,
                    pageLength: 10,
                    lengthMenu: [10, 25, 50, 100],
                    language: {
                        search: 'Search:',
                        lengthMenu: 'Show _MENU_ entries',
                        info: 'Showing _START_ to _END_ of _TOTAL_ entries',
                        paginate: {
                            first: '«',
                            last: '»',
                            next: '›',
                            previous: '‹',
                        },
                        emptyTable: 'No data available.',
                        zeroRecords: 'No matching records found.',
                    },
                    order: [
                        [0, 'desc']
                    ],
                });
            }
        });
    </script>

    @stack('scripts')
</body>

</html>
