@extends('admin.layouts.app')

@section('title', 'Dashboard')
@section('breadcrumb', 'Dashboard')

@section('content')

    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3 mb-6">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-navy tracking-tight">Dashboard</h1>
            <p class="text-sm text-gray-500 mt-1">Welcome back, {{ auth('admin')->user()->name }}!</p>
        </div>
        <div class="flex gap-2 shrink-0">
            <a href="{{ route('admin.reports.export') }}"
                class="inline-flex items-center gap-2 px-3 py-2 bg-[#EEF2FA] border border-[#C7D4EA]
                  rounded-lg text-xs sm:text-sm font-medium text-navy no-underline hover:bg-[#dde6f5] transition-all">
                📤 Export
            </a>
            <a href="{{ route('admin.users.create') }}"
                class="inline-flex items-center gap-2 px-3 py-2 bg-navy text-white border border-navy
                  rounded-lg text-xs sm:text-sm font-semibold no-underline hover:bg-navy-dark transition-all">
                ➕ Add User
            </a>
        </div>
    </div>

    {{-- ── STAT CARDS ── --}}
    {{-- Mobile: 1 col | sm: 2 col | lg: 5 col --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3 mb-6">

        {{-- Total Users --}}
        <div
            class="bg-white border border-[#E5EAF2] rounded-2xl p-4 sm:p-5 flex items-center gap-4
                hover:shadow-[0_4px_16px_rgba(27,58,107,0.08)] transition-shadow">
            <div
                class="w-11 h-11 sm:w-12 sm:h-12 rounded-xl bg-blue-50 flex items-center
                    justify-center text-lg sm:text-xl shrink-0">
                👥</div>
            <div class="min-w-0">
                <div class="text-[11px] font-semibold text-gray-500 uppercase tracking-wider mb-1 truncate">
                    Total Users
                </div>
                <div class="text-xl sm:text-2xl font-bold text-navy font-mono leading-none">
                    {{ number_format($stats['total_users']) }}
                </div>
                <div class="text-[11px] text-green-600 mt-1">
                    ▲ +{{ $stats['new_users_this_month'] }} this month
                </div>
            </div>
        </div>

        {{-- Total Credit --}}
        <div
            class="bg-white border border-[#E5EAF2] rounded-2xl p-4 sm:p-5 flex items-center gap-4
                hover:shadow-[0_4px_16px_rgba(27,58,107,0.08)] transition-shadow">
            <div
                class="w-11 h-11 sm:w-12 sm:h-12 rounded-xl bg-green-50 flex items-center
                    justify-center text-lg sm:text-xl shrink-0">
                💰</div>
            <div class="min-w-0">
                <div class="text-[11px] font-semibold text-gray-500 uppercase tracking-wider mb-1 truncate">
                    Total Credit
                </div>
                <div class="text-xl sm:text-2xl font-bold text-navy font-mono leading-none">
                    ₹{{ number_format($stats['total_credit'] / 100000, 1) }}L
                </div>
                <div class="text-[11px] text-green-600 mt-1">▲ This month</div>
            </div>
        </div>

        {{-- Total Debit --}}
        <div
            class="bg-white border border-[#E5EAF2] rounded-2xl p-4 sm:p-5 flex items-center gap-4
                hover:shadow-[0_4px_16px_rgba(27,58,107,0.08)] transition-shadow">
            <div
                class="w-11 h-11 sm:w-12 sm:h-12 rounded-xl bg-red-50 flex items-center
                    justify-center text-lg sm:text-xl shrink-0">
                💸</div>
            <div class="min-w-0">
                <div class="text-[11px] font-semibold text-gray-500 uppercase tracking-wider mb-1 truncate">
                    Total Debit
                </div>
                <div class="text-xl sm:text-2xl font-bold text-navy font-mono leading-none">
                    ₹{{ number_format($stats['total_debit'] / 100000, 1) }}L
                </div>
                <div class="text-[11px] text-red-500 mt-1">▼ This month</div>
            </div>
        </div>

        {{-- Transactions --}}
        <div
            class="bg-white border border-[#E5EAF2] rounded-2xl p-4 sm:p-5 flex items-center gap-4
                hover:shadow-[0_4px_16px_rgba(27,58,107,0.08)] transition-shadow">
            <div
                class="w-11 h-11 sm:w-12 sm:h-12 rounded-xl bg-amber-50 flex items-center
                    justify-center text-lg sm:text-xl shrink-0">
                📋</div>
            <div class="min-w-0">
                <div class="text-[11px] font-semibold text-gray-500 uppercase tracking-wider mb-1 truncate">
                    Transactions
                </div>
                <div class="text-xl sm:text-2xl font-bold text-navy font-mono leading-none">
                    {{ number_format($stats['total_transactions']) }}
                </div>
                <div class="text-[11px] text-green-600 mt-1">▲ +{{ $stats['today_transactions'] }} today</div>
            </div>
        </div>

        {{-- Businesses --}}
        <div
            class="bg-white border border-[#E5EAF2] rounded-2xl p-4 sm:p-5 flex items-center gap-4
                hover:shadow-[0_4px_16px_rgba(27,58,107,0.08)] transition-shadow sm:col-span-2 lg:col-span-1">
            <div
                class="w-11 h-11 sm:w-12 sm:h-12 rounded-xl bg-purple-50 flex items-center
                    justify-center text-lg sm:text-xl shrink-0">
                🏪</div>
            <div class="min-w-0">
                <div class="text-[11px] font-semibold text-gray-500 uppercase tracking-wider mb-1 truncate">
                    Businesses
                </div>
                <div class="text-xl sm:text-2xl font-bold text-navy font-mono leading-none">
                    {{ number_format($stats['total_businesses']) }}
                </div>
                <div class="text-[11px] text-gray-400 mt-1">Registered</div>
            </div>
        </div>

    </div>

    {{-- ── MAIN GRID ── --}}
    <div class="grid grid-cols-1 xl:grid-cols-[1fr_340px] gap-4">

        {{-- LEFT --}}
        <div class="flex flex-col gap-4">

            {{-- Recent Transactions --}}
            <div class="bg-white border border-[#E5EAF2] rounded-2xl overflow-hidden">
                <div class="flex items-center justify-between px-5 py-4 border-b border-[#E5EAF2]">
                    <h3 class="text-sm font-semibold text-navy">📋 Recent Transactions</h3>
                    <a href="{{ route('admin.transactions.index') }}"
                        class="text-xs text-navy-light font-medium no-underline hover:underline">View all →</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[500px]">
                        <thead>
                            <tr class="bg-[#EEF2FA]">
                                <th
                                    class="text-left text-[11px] font-semibold text-gray-500 uppercase
                                       tracking-wider px-5 py-3 whitespace-nowrap">
                                    User</th>
                                <th
                                    class="text-left text-[11px] font-semibold text-gray-500 uppercase
                                       tracking-wider px-5 py-3 whitespace-nowrap">
                                    Type</th>
                                <th
                                    class="text-left text-[11px] font-semibold text-gray-500 uppercase
                                       tracking-wider px-5 py-3 whitespace-nowrap">
                                    Source</th>
                                <th
                                    class="text-left text-[11px] font-semibold text-gray-500 uppercase
                                       tracking-wider px-5 py-3 whitespace-nowrap">
                                    Amount</th>
                                <th
                                    class="text-left text-[11px] font-semibold text-gray-500 uppercase
                                       tracking-wider px-5 py-3 whitespace-nowrap">
                                    Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#F4F6FB]">
                            @forelse($recentTransactions as $txn)
                                <tr class="hover:bg-[#F8FAFC] transition-colors">
                                    <td class="px-5 py-3 text-[13px] text-navy font-medium whitespace-nowrap">
                                        {{ $txn->user->name ?? 'Unknown' }}
                                    </td>
                                    <td class="px-5 py-3">
                                        @if ($txn->type === 'credit')
                                            <span
                                                class="inline-flex items-center gap-1 px-2 py-[3px] rounded-md
                                                 text-[11px] font-semibold bg-green-50 text-green-700">
                                                ↑ Credit
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center gap-1 px-2 py-[3px] rounded-md
                                                 text-[11px] font-semibold bg-red-50 text-red-600">
                                                ↓ Debit
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-3 text-[12px] text-gray-500 uppercase font-mono">
                                        {{ $txn->source }}
                                    </td>
                                    <td
                                        class="px-5 py-3 text-[13px] font-bold font-mono whitespace-nowrap
                                       {{ $txn->type === 'credit' ? 'text-green-600' : 'text-red-500' }}">
                                        {{ $txn->type === 'credit' ? '+' : '-' }}₹{{ number_format($txn->amount, 2) }}
                                    </td>
                                    <td class="px-5 py-3 text-[12px] text-gray-400 whitespace-nowrap">
                                        {{ $txn->transaction_date->format('d M Y') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-10 text-sm text-gray-400">
                                        No transactions yet
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Monthly Overview --}}
            <div class="bg-white border border-[#E5EAF2] rounded-2xl overflow-hidden">
                <div class="px-5 py-4 border-b border-[#E5EAF2]">
                    <h3 class="text-sm font-semibold text-navy">📊 Monthly Overview — {{ now()->format('Y') }}</h3>
                </div>
                <div class="p-5">
                    <div class="flex flex-wrap gap-4 mb-4">
                        <div>
                            <div class="text-[11px] text-gray-400 mb-1">Net Balance</div>
                            <div
                                class="text-xl font-bold font-mono
                                    {{ $stats['total_credit'] - $stats['total_debit'] >= 0 ? 'text-green-600' : 'text-red-500' }}">
                                ₹{{ number_format(abs($stats['total_credit'] - $stats['total_debit']), 2) }}
                            </div>
                        </div>
                        <div class="ml-auto flex items-end gap-3 text-xs text-gray-400">
                            <span class="flex items-center gap-1">
                                <span class="w-3 h-3 bg-green-600 rounded-sm inline-block"></span> Credit
                            </span>
                            <span class="flex items-center gap-1">
                                <span class="w-3 h-3 bg-red-500 rounded-sm inline-block"></span> Debit
                            </span>
                        </div>
                    </div>
                    @php
                        $months = $monthlyData ?? [];
                        $maxVal = collect($months)->max(fn($m) => max($m['credit'] ?? 1, $m['debit'] ?? 1)) ?: 1;
                        $moNames = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                    @endphp
                    <div class="flex items-end gap-1 h-20">
                        @foreach (range(1, 12) as $m)
                            @php
                                $d = $months[$m] ?? ['credit' => 0, 'debit' => 0];
                                $ch = max(4, ($d['credit'] / $maxVal) * 76);
                                $dh = max(4, ($d['debit'] / $maxVal) * 76);
                            @endphp
                            <div class="flex-1 flex flex-col items-center gap-[2px] justify-end">
                                <div class="w-full rounded-t-sm bg-green-600 opacity-80 hover:opacity-100 transition-opacity"
                                    style="height:{{ $ch }}px"></div>
                                <div class="w-full rounded-t-sm bg-red-500 opacity-70 hover:opacity-100 transition-opacity"
                                    style="height:{{ $dh }}px"></div>
                            </div>
                        @endforeach
                    </div>
                    <div class="flex justify-between mt-2">
                        @foreach ($moNames as $mo)
                            <span class="flex-1 text-center text-[9px] text-gray-400">{{ $mo }}</span>
                        @endforeach
                    </div>
                </div>
            </div>

        </div>

        {{-- RIGHT --}}
        <div class="flex flex-col gap-4">

            {{-- Quick Actions --}}
            <div class="bg-white border border-[#E5EAF2] rounded-2xl overflow-hidden">
                <div class="px-5 py-4 border-b border-[#E5EAF2]">
                    <h3 class="text-sm font-semibold text-navy">⚡ Quick Actions</h3>
                </div>
                <div class="p-4">
                    <div class="grid grid-cols-2 gap-2 mb-3">
                        @foreach ([['href' => route('admin.users.create'), 'icon' => '➕', 'label' => 'New User'], ['href' => route('admin.reports.monthly'), 'icon' => '📊', 'label' => 'Reports'], ['href' => route('admin.transactions.uncategorized'), 'icon' => '🏷️', 'label' => 'Categorize'], ['href' => route('admin.reports.export'), 'icon' => '📤', 'label' => 'Export']] as $btn)
                            <a href="{{ $btn['href'] }}"
                                class="flex items-center gap-2 px-3 py-[10px] bg-[#F0F4F8] border border-[#E5EAF2]
                              rounded-xl text-[13px] font-medium text-navy no-underline
                              hover:border-navy hover:bg-white transition-all">
                                {{ $btn['icon'] }} {{ $btn['label'] }}
                            </a>
                        @endforeach
                    </div>

                    @if (($stats['uncategorized_transactions'] ?? 0) > 0)
                        <div class="bg-amber-50 border border-amber-200 rounded-xl px-4 py-3 text-[12.5px] text-amber-700">
                            ⚡ <strong>{{ $stats['uncategorized_transactions'] }}</strong> need categorization.
                            <a href="{{ route('admin.transactions.uncategorized') }}"
                                class="text-amber-700 font-semibold no-underline hover:underline">Fix now →</a>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Recent Users --}}
            <div class="bg-white border border-[#E5EAF2] rounded-2xl overflow-hidden flex-1">
                <div class="flex items-center justify-between px-5 py-4 border-b border-[#E5EAF2]">
                    <h3 class="text-sm font-semibold text-navy">👤 New Users</h3>
                    <a href="{{ route('admin.users.index') }}"
                        class="text-xs text-navy-light font-medium no-underline hover:underline">View all →</a>
                </div>
                <div class="divide-y divide-[#F4F6FB]">
                    @forelse($recentUsers as $user)
                        <div class="flex items-center gap-3 px-5 py-3">
                            <div
                                class="w-9 h-9 rounded-full bg-blue-50 text-blue-600 flex items-center
                                justify-center text-xs font-bold shrink-0">
                                {{ strtoupper(substr($user->name ?: 'U', 0, 2)) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="text-[13px] font-medium text-navy truncate">
                                    {{ $user->name ?: 'No name' }}
                                </div>
                                <div class="text-[11px] text-gray-400 font-mono">{{ $user->mobile }}</div>
                            </div>
                            <div class="text-[11px] text-gray-400 whitespace-nowrap shrink-0">
                                {{ $user->created_at->diffForHumans() }}
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8 text-sm text-gray-400">No users yet</div>
                    @endforelse
                </div>
            </div>

            {{-- System Status --}}
            <div class="bg-white border border-[#E5EAF2] rounded-2xl overflow-hidden">
                <div class="px-5 py-4 border-b border-[#E5EAF2]">
                    <h3 class="text-sm font-semibold text-navy">🖥 System Status</h3>
                </div>
                <div class="p-5 flex flex-col gap-3 text-[13px]">
                    <div class="flex items-center justify-between">
                        <span class="text-gray-500">Application</span>
                        <span class="px-2 py-[2px] rounded-md text-[11px] font-semibold bg-green-50 text-green-700">✓
                            Online</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-500">Database</span>
                        <span class="px-2 py-[2px] rounded-md text-[11px] font-semibold bg-green-50 text-green-700">✓
                            Connected</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-500">Queue Worker</span>
                        <span class="px-2 py-[2px] rounded-md text-[11px] font-semibold bg-amber-50 text-amber-700">⚡
                            Check</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-500">Laravel Version</span>
                        <span class="text-xs text-gray-400 font-mono">v{{ app()->version() }}</span>
                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection
