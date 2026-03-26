@extends('admin.layouts.app')

@section('title', 'Dashboard')
@section('breadcrumb', 'Dashboard')

@push('styles')
    <style>
        :root {
            --primary: #1B3A6B;
            --primary-light: #2D5499;
            --bg: #F0F4F8;
            --surface: #FFFFFF;
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
        }

        /* Stat Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .stat-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 1.2rem 1.4rem;
            display: flex;
            align-items: flex-start;
            gap: 14px;
            transition: box-shadow 0.2s;
        }

        .stat-card:hover {
            box-shadow: 0 4px 16px rgba(27, 58, 107, 0.08);
        }

        .stat-icon {
            width: 46px;
            height: 46px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            flex-shrink: 0;
        }

        .stat-icon.blue {
            background: #EFF6FF;
        }

        .stat-icon.green {
            background: var(--success-bg);
        }

        .stat-icon.red {
            background: var(--danger-bg);
        }

        .stat-icon.orange {
            background: var(--warning-bg);
        }

        .stat-icon.purple {
            background: #F5F3FF;
        }

        .stat-info {
            flex: 1;
        }

        .stat-label {
            font-size: 12px;
            color: var(--text-secondary);
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            margin-bottom: 4px;
        }

        .stat-value {
            font-size: 1.65rem;
            font-weight: 700;
            color: var(--text-primary);
            letter-spacing: -0.5px;
            font-family: 'JetBrains Mono', monospace;
            line-height: 1;
        }

        .stat-change {
            font-size: 11px;
            margin-top: 5px;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .stat-change.up {
            color: var(--success);
        }

        .stat-change.down {
            color: var(--danger);
        }

        /* Grid Layout */
        .dashboard-grid {
            display: grid;
            grid-template-columns: 1fr 340px;
            gap: 1rem;
        }

        @media (max-width: 1100px) {
            .dashboard-grid {
                grid-template-columns: 1fr;
            }
        }

        /* Cards */
        .card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 14px;
            overflow: hidden;
        }

        .card-header {
            padding: 1rem 1.4rem;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .card-header h3 {
            font-size: 14px;
            font-weight: 600;
            color: var(--text-primary);
        }

        .card-header .view-all {
            font-size: 12px;
            color: var(--primary-light);
            text-decoration: none;
            font-weight: 500;
        }

        .card-header .view-all:hover {
            text-decoration: underline;
        }

        .card-body {
            padding: 1.2rem 1.4rem;
        }

        /* Recent Transactions Table */
        .table-mini {
            width: 100%;
            border-collapse: collapse;
        }

        .table-mini th {
            font-size: 11px;
            font-weight: 600;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.6px;
            padding: 0 0 0.7rem;
            text-align: left;
            border-bottom: 1px solid var(--border);
        }

        .table-mini td {
            padding: 0.7rem 0;
            font-size: 13px;
            color: var(--text-primary);
            border-bottom: 1px solid var(--border);
            vertical-align: middle;
        }

        .table-mini tr:last-child td {
            border-bottom: none;
        }

        .txn-type {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 2px 8px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 600;
        }

        .txn-type.credit {
            background: var(--success-bg);
            color: var(--success);
        }

        .txn-type.debit {
            background: var(--danger-bg);
            color: var(--danger);
        }

        .txn-amount {
            font-family: 'JetBrains Mono', monospace;
            font-weight: 600;
        }

        .txn-amount.credit {
            color: var(--success);
        }

        .txn-amount.debit {
            color: var(--danger);
        }

        /* User List */
        .user-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 0.65rem 0;
            border-bottom: 1px solid var(--border);
        }

        .user-item:last-child {
            border-bottom: none;
        }

        .user-avatar {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: var(--info-bg);
            color: var(--info);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: 600;
            flex-shrink: 0;
        }

        .user-name {
            font-size: 13px;
            font-weight: 500;
        }

        .user-mobile {
            font-size: 11px;
            color: var(--text-muted);
            font-family: 'JetBrains Mono', monospace;
        }

        .user-joined {
            font-size: 11px;
            color: var(--text-muted);
            margin-left: auto;
        }

        /* Status Badges */
        .badge {
            padding: 2px 8px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 600;
        }

        .badge-success {
            background: var(--success-bg);
            color: var(--success);
        }

        .badge-danger {
            background: var(--danger-bg);
            color: var(--danger);
        }

        .badge-warning {
            background: var(--warning-bg);
            color: var(--warning);
        }

        /* Quick Actions */
        .quick-actions {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.8rem;
            margin-bottom: 1rem;
        }

        .action-btn {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 0.7rem 1rem;
            background: var(--bg);
            border: 1.5px solid var(--border);
            border-radius: 10px;
            font-family: 'Sora', sans-serif;
            font-size: 13px;
            font-weight: 500;
            color: var(--text-primary);
            cursor: pointer;
            transition: all 0.15s;
            text-decoration: none;
        }

        .action-btn:hover {
            border-color: var(--primary);
            background: var(--surface);
            color: var(--primary);
        }

        /* Mini bar chart */
        .mini-chart {
            display: flex;
            align-items: flex-end;
            gap: 4px;
            height: 60px;
            margin-top: 0.5rem;
        }

        .bar {
            flex: 1;
            border-radius: 4px 4px 0 0;
            transition: opacity 0.2s;
        }

        .bar:hover {
            opacity: 0.7;
        }

        .bar.credit {
            background: #16A34A;
        }

        .bar.debit {
            background: #DC2626;
        }
    </style>
@endpush

@section('content')

    {{-- Page Header --}}
    <div class="page-header">
        <div>
            <h1>Dashboard</h1>
            <p>Welcome back, {{ auth('admin')->user()->name }}! Here's what's happening.</p>
        </div>
        <div style="display:flex; gap:0.6rem;">
            <a href="{{ route('admin.reports.export') }}"
                style="display:inline-flex; align-items:center; gap:6px; padding:0.5rem 1rem; background:#EEF2FA; border:1.5px solid #C7D4EA; border-radius:9px; font-size:13px; font-weight:500; color:#1B3A6B; text-decoration:none;">
                📤 Export Report
            </a>
            <a href="{{ route('admin.users.create') }}"
                style="display:inline-flex; align-items:center; gap:6px; padding:0.5rem 1rem; background:#1B3A6B; border:1.5px solid #1B3A6B; border-radius:9px; font-size:13px; font-weight:600; color:#fff; text-decoration:none;">
                ➕ Add User
            </a>
        </div>
    </div>

    {{-- Stat Cards --}}
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon blue">👥</div>
            <div class="stat-info">
                <div class="stat-label">Total Users</div>
                <div class="stat-value">{{ number_format($stats['total_users']) }}</div>
                <div class="stat-change up">▲ +{{ $stats['new_users_this_month'] }} this month</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon green">💰</div>
            <div class="stat-info">
                <div class="stat-label">Total Credit</div>
                <div class="stat-value">₹{{ number_format($stats['total_credit'] / 100000, 1) }}L</div>
                <div class="stat-change up">▲ This month</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon red">💸</div>
            <div class="stat-info">
                <div class="stat-label">Total Debit</div>
                <div class="stat-value">₹{{ number_format($stats['total_debit'] / 100000, 1) }}L</div>
                <div class="stat-change down">▼ This month</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon orange">📋</div>
            <div class="stat-info">
                <div class="stat-label">Transactions</div>
                <div class="stat-value">{{ number_format($stats['total_transactions']) }}</div>
                <div class="stat-change up">▲ +{{ $stats['today_transactions'] }} today</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon purple">🏪</div>
            <div class="stat-info">
                <div class="stat-label">Businesses</div>
                <div class="stat-value">{{ number_format($stats['total_businesses']) }}</div>
                <div class="stat-change" style="color:var(--text-muted);">Registered</div>
            </div>
        </div>
    </div>

    {{-- Main Dashboard Grid --}}
    <div class="dashboard-grid">

        {{-- Left: Transactions + Chart --}}
        <div style="display:flex; flex-direction:column; gap:1rem;">

            {{-- Recent Transactions --}}
            <div class="card">
                <div class="card-header">
                    <h3>📋 Recent Transactions</h3>
                    <a href="{{ route('admin.transactions.index') }}" class="view-all">View all →</a>
                </div>
                <div class="card-body" style="padding:0 1.4rem;">
                    <table class="table-mini">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Type</th>
                                <th>Source</th>
                                <th>Amount</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentTransactions as $txn)
                                <tr>
                                    <td>{{ $txn->user->name ?? 'Unknown' }}</td>
                                    <td>
                                        <span class="txn-type {{ $txn->type }}">
                                            {{ $txn->type === 'credit' ? '↑' : '↓' }}
                                            {{ ucfirst($txn->type) }}
                                        </span>
                                    </td>
                                    <td style="color:var(--text-secondary);">{{ strtoupper($txn->source) }}</td>
                                    <td>
                                        <span class="txn-amount {{ $txn->type }}">
                                            {{ $txn->type === 'credit' ? '+' : '-' }}₹{{ number_format($txn->amount, 2) }}
                                        </span>
                                    </td>
                                    <td style="color:var(--text-muted); font-size:12px;">
                                        {{ $txn->transaction_date->format('d M Y') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5"
                                        style="text-align:center; color:var(--text-muted); padding:1.5rem 0;">
                                        No transactions yet
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Monthly Overview (visual bars) --}}
            <div class="card">
                <div class="card-header">
                    <h3>📊 Monthly Overview — {{ now()->format('Y') }}</h3>
                </div>
                <div class="card-body">
                    <div style="display:flex; gap:1.5rem; margin-bottom:0.8rem;">
                        <div>
                            <div style="font-size:11px; color:var(--text-muted); margin-bottom:2px;">Net Balance</div>
                            <div
                                style="font-size:1.3rem; font-weight:700; font-family:'JetBrains Mono',monospace; color:{{ $stats['total_credit'] - $stats['total_debit'] >= 0 ? 'var(--success)' : 'var(--danger)' }};">
                                ₹{{ number_format(abs($stats['total_credit'] - $stats['total_debit']), 2) }}
                            </div>
                        </div>
                        <div
                            style="display:flex; gap:12px; align-items:flex-end; margin-left:auto; font-size:12px; color:var(--text-muted);">
                            <span style="display:flex; align-items:center; gap:5px;"><span
                                    style="width:10px;height:10px;background:var(--success);border-radius:3px;display:inline-block;"></span>
                                Credit</span>
                            <span style="display:flex; align-items:center; gap:5px;"><span
                                    style="width:10px;height:10px;background:var(--danger);border-radius:3px;display:inline-block;"></span>
                                Debit</span>
                        </div>
                    </div>

                    {{-- Simple 12-month bar chart using inline styles --}}
                    <div class="mini-chart" style="height:80px;">
                        @php
                            $months = $monthlyData ?? [];
                            $maxVal = collect($months)->max(fn($m) => max($m['credit'] ?? 1, $m['debit'] ?? 1)) ?: 1;
                        @endphp
                        @foreach (range(1, 12) as $m)
                            @php
                                $d = $months[$m] ?? ['credit' => 0, 'debit' => 0];
                                $ch = max(4, ($d['credit'] / $maxVal) * 76);
                                $dh = max(4, ($d['debit'] / $maxVal) * 76);
                            @endphp
                            <div
                                style="flex:1; display:flex; flex-direction:column; align-items:center; gap:2px; justify-content:flex-end;">
                                <div class="bar credit" style="height:{{ $ch }}px; width:100%;"></div>
                                <div class="bar debit" style="height:{{ $dh }}px; width:100%;"></div>
                            </div>
                        @endforeach
                    </div>
                    <div style="display:flex; justify-content:space-between; margin-top:5px;">
                        @foreach (['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'] as $mo)
                            <span
                                style="font-size:9px; color:var(--text-muted); flex:1; text-align:center;">{{ $mo }}</span>
                        @endforeach
                    </div>
                </div>
            </div>

        </div>

        {{-- Right: Recent Users + Quick Actions --}}
        <div style="display:flex; flex-direction:column; gap:1rem;">

            {{-- Quick Actions --}}
            <div class="card">
                <div class="card-header">
                    <h3>⚡ Quick Actions</h3>
                </div>
                <div class="card-body">
                    <div class="quick-actions">
                        <a href="{{ route('admin.users.create') }}" class="action-btn">➕ New User</a>
                        <a href="{{ route('admin.reports.monthly') }}" class="action-btn">📊 Reports</a>
                        <a href="{{ route('admin.transactions.uncategorized') }}" class="action-btn">🏷️ Categorize</a>
                        <a href="{{ route('admin.reports.export') }}" class="action-btn">📤 Export</a>
                    </div>

                    {{-- Uncategorized Alert --}}
                    @if (($stats['uncategorized_transactions'] ?? 0) > 0)
                        <div
                            style="background:var(--warning-bg); border:1px solid #FDE68A; border-radius:9px; padding:0.7rem 0.9rem; font-size:12.5px; color:var(--warning);">
                            ⚡ <strong>{{ $stats['uncategorized_transactions'] }}</strong> transactions need categorization.
                            <a href="{{ route('admin.transactions.uncategorized') }}"
                                style="color:var(--warning); font-weight:600;">Fix now →</a>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Recent Users --}}
            <div class="card" style="flex:1;">
                <div class="card-header">
                    <h3>👤 New Users</h3>
                    <a href="{{ route('admin.users.index') }}" class="view-all">View all →</a>
                </div>
                <div class="card-body" style="padding: 0.4rem 1.4rem;">
                    @forelse($recentUsers as $user)
                        <div class="user-item">
                            <div class="user-avatar">{{ strtoupper(substr($user->name, 0, 2)) }}</div>
                            <div>
                                <div class="user-name">{{ $user->name }}</div>
                                <div class="user-mobile">{{ $user->mobile }}</div>
                            </div>
                            <div class="user-joined">
                                {{ $user->created_at->diffForHumans() }}
                            </div>
                        </div>
                    @empty
                        <div style="text-align:center; color:var(--text-muted); padding:1.5rem 0; font-size:13px;">No users
                            yet</div>
                    @endforelse
                </div>
            </div>

            {{-- System Status --}}
            <div class="card">
                <div class="card-header">
                    <h3>🖥 System Status</h3>
                </div>
                <div class="card-body">
                    <div style="display:flex; flex-direction:column; gap:0.6rem; font-size:13px;">
                        <div style="display:flex; justify-content:space-between; align-items:center;">
                            <span style="color:var(--text-secondary);">Application</span>
                            <span class="badge badge-success">✓ Online</span>
                        </div>
                        <div style="display:flex; justify-content:space-between; align-items:center;">
                            <span style="color:var(--text-secondary);">Database</span>
                            <span class="badge badge-success">✓ Connected</span>
                        </div>
                        <div style="display:flex; justify-content:space-between; align-items:center;">
                            <span style="color:var(--text-secondary);">Queue Worker</span>
                            <span class="badge badge-warning">⚡ Check</span>
                        </div>
                        <div style="display:flex; justify-content:space-between; align-items:center;">
                            <span style="color:var(--text-secondary);">Laravel Version</span>
                            <span
                                style="font-family:'JetBrains Mono',monospace; font-size:12px; color:var(--text-muted);">v{{ app()->version() }}</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection
