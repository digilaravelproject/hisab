@extends('admin.layouts.app')

@section('title', 'User Detail')
@section('breadcrumb', 'Users / Detail')

@section('content')

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.users.index') }}"
                class="w-9 h-9 bg-white border border-[#E5EAF2] rounded-lg flex items-center justify-center
                  text-gray-500 no-underline hover:bg-[#F0F4F8] transition-all shrink-0">←</a>
            <div>
                <h1 class="text-xl sm:text-2xl font-bold text-navy tracking-tight">User Detail</h1>
                <p class="text-sm text-gray-500 mt-1">ID #{{ $user->id }}</p>
            </div>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.users.edit', $user->id) }}"
                class="inline-flex items-center gap-2 px-4 py-2 bg-amber-50 border border-amber-200
                  text-amber-700 rounded-lg text-sm font-medium no-underline hover:bg-amber-500
                  hover:text-white transition-all">
                ✏️ Edit User
            </a>
            <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}"
                onsubmit="return confirm('Delete this user permanently?')">
                @csrf @method('DELETE')
                <button type="submit"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-red-50 border border-red-200
                           text-red-600 rounded-lg text-sm font-medium cursor-pointer hover:bg-red-500
                           hover:text-white transition-all font-[Sora]">
                    🗑 Delete
                </button>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

        {{-- LEFT: Profile Card --}}
        <div class="flex flex-col gap-5">

            {{-- Profile --}}
            <div class="bg-white border border-[#E5EAF2] rounded-2xl overflow-hidden">
                <div class="p-6 flex flex-col items-center text-center">
                    @if ($user->profile_photo)
                        <img src="{{ asset('storage/' . $user->profile_photo) }}"
                            class="w-20 h-20 rounded-full object-cover border-4 border-[#EEF2FA] mb-3" alt="">
                    @else
                        <div
                            class="w-20 h-20 rounded-full bg-[#EEF2FA] text-navy flex items-center
                                justify-center text-2xl font-bold border-4 border-white shadow-sm mb-3">
                            {{ strtoupper(substr($user->name ?: 'U', 0, 2)) }}
                        </div>
                    @endif

                    <h2 class="text-[16px] font-bold text-navy">{{ $user->name ?: 'No Name' }}</h2>
                    <p class="text-[13px] text-gray-500 font-mono mt-1">{{ $user->mobile }}</p>

                    <div class="mt-3">
                        @if ($user->is_active)
                            <span class="px-3 py-1 bg-green-50 text-green-700 text-[11px] font-semibold rounded-full">
                                ✓ Active
                            </span>
                        @else
                            <span class="px-3 py-1 bg-red-50 text-red-600 text-[11px] font-semibold rounded-full">
                                ✗ Inactive
                            </span>
                        @endif
                    </div>

                    {{-- Toggle Status --}}
                    <form method="POST" action="{{ route('admin.users.toggle-status', $user->id) }}" class="mt-3 w-full">
                        @csrf @method('PATCH')
                        <button type="submit"
                            class="w-full py-2 text-[12px] font-medium rounded-lg border cursor-pointer
                                   font-[Sora] transition-all
                                   {{ $user->is_active
                                       ? 'bg-red-50 border-red-200 text-red-600 hover:bg-red-500 hover:text-white'
                                       : 'bg-green-50 border-green-200 text-green-700 hover:bg-green-500 hover:text-white' }}">
                            {{ $user->is_active ? 'Deactivate User' : 'Activate User' }}
                        </button>
                    </form>
                </div>

                <div class="border-t border-[#F4F6FB] divide-y divide-[#F4F6FB]">
                    <div class="flex items-center justify-between px-5 py-3">
                        <span class="text-[12px] text-gray-400">Gender</span>
                        <span class="text-[13px] font-medium text-navy capitalize">
                            {{ $user->gender ?: '—' }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between px-5 py-3">
                        <span class="text-[12px] text-gray-400">Reminder Time</span>
                        <span class="text-[13px] font-mono text-navy">
                            {{ $user->reminder_time ?: '—' }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between px-5 py-3">
                        <span class="text-[12px] text-gray-400">Joined</span>
                        <span class="text-[13px] text-navy">
                            {{ $user->created_at->format('d M Y') }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- Roles --}}
            <div class="bg-white border border-[#E5EAF2] rounded-2xl overflow-hidden">
                <div class="px-5 py-4 border-b border-[#E5EAF2]">
                    <h3 class="text-sm font-semibold text-navy">User Roles</h3>
                </div>
                <div class="p-4 flex flex-wrap gap-2">
                    @forelse($user->user_types ?? [] as $type)
                        <span
                            class="px-3 py-1 bg-[#EEF2FA] text-navy text-[12px] font-semibold
                                 rounded-lg capitalize">
                            {{ str_replace('_', ' ', $type) }}
                        </span>
                    @empty
                        <p class="text-[13px] text-gray-400">No roles assigned</p>
                    @endforelse
                </div>
            </div>

            {{-- Stats --}}
            <div class="bg-white border border-[#E5EAF2] rounded-2xl overflow-hidden">
                <div class="px-5 py-4 border-b border-[#E5EAF2]">
                    <h3 class="text-sm font-semibold text-navy">Summary</h3>
                </div>
                <div class="divide-y divide-[#F4F6FB]">
                    <div class="flex items-center justify-between px-5 py-3">
                        <span class="text-[12px] text-gray-500">Total Credit</span>
                        <span class="text-[13px] font-bold font-mono text-green-600">
                            +₹{{ number_format($totalCredit, 2) }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between px-5 py-3">
                        <span class="text-[12px] text-gray-500">Total Debit</span>
                        <span class="text-[13px] font-bold font-mono text-red-500">
                            -₹{{ number_format($totalDebit, 2) }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between px-5 py-3">
                        <span class="text-[12px] text-gray-500">Net Balance</span>
                        <span
                            class="text-[13px] font-bold font-mono
                                 {{ $totalCredit - $totalDebit >= 0 ? 'text-green-600' : 'text-red-500' }}">
                            ₹{{ number_format(abs($totalCredit - $totalDebit), 2) }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between px-5 py-3">
                        <span class="text-[12px] text-gray-500">Transactions</span>
                        <span class="text-[13px] font-mono text-navy font-semibold">
                            {{ $user->transactions_count }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between px-5 py-3">
                        <span class="text-[12px] text-gray-500">Businesses</span>
                        <span class="text-[13px] font-mono text-navy font-semibold">
                            {{ $user->businesses_count }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between px-5 py-3">
                        <span class="text-[12px] text-gray-500">Bills</span>
                        <span class="text-[13px] font-mono text-navy font-semibold">
                            {{ $user->bills_count }}
                        </span>
                    </div>
                </div>
            </div>

        </div>

        {{-- RIGHT: Transactions --}}
        <div class="lg:col-span-2">
            <div class="bg-white border border-[#E5EAF2] rounded-2xl overflow-hidden">
                <div class="flex items-center justify-between px-5 py-4 border-b border-[#E5EAF2]">
                    <h3 class="text-sm font-semibold text-navy">Recent Transactions</h3>
                    <a href="{{ route('admin.transactions.index', ['user_id' => $user->id]) }}"
                        class="text-xs text-navy-light font-medium no-underline hover:underline">View all →</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[480px]">
                        <thead>
                            <tr class="bg-[#EEF2FA]">
                                <th
                                    class="text-left px-5 py-3 text-[11px] font-semibold text-gray-500 uppercase tracking-wider">
                                    Type</th>
                                <th
                                    class="text-left px-5 py-3 text-[11px] font-semibold text-gray-500 uppercase tracking-wider">
                                    Source</th>
                                <th
                                    class="text-left px-5 py-3 text-[11px] font-semibold text-gray-500 uppercase tracking-wider">
                                    Category</th>
                                <th
                                    class="text-left px-5 py-3 text-[11px] font-semibold text-gray-500 uppercase tracking-wider">
                                    Amount</th>
                                <th
                                    class="text-left px-5 py-3 text-[11px] font-semibold text-gray-500 uppercase tracking-wider">
                                    Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#F4F6FB]">
                            @forelse($recentTransactions as $txn)
                                <tr class="hover:bg-[#F8FAFC] transition-colors">
                                    <td class="px-5 py-3">
                                        @if ($txn->type === 'credit')
                                            <span
                                                class="px-2 py-[3px] bg-green-50 text-green-700 text-[11px]
                                                 font-semibold rounded-md">↑
                                                Credit</span>
                                        @else
                                            <span
                                                class="px-2 py-[3px] bg-red-50 text-red-600 text-[11px]
                                                 font-semibold rounded-md">↓
                                                Debit</span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-3 text-[12px] text-gray-500 uppercase font-mono">
                                        {{ $txn->source }}
                                    </td>
                                    <td class="px-5 py-3 text-[13px] text-gray-500">
                                        {{ $txn->category->name ?? '—' }}
                                    </td>
                                    <td
                                        class="px-5 py-3 text-[13px] font-bold font-mono
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
                                        No transactions found
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

@endsection
