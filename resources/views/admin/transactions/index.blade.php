@extends('admin.layouts.app')

@section('title', 'Transactions')
@section('breadcrumb', 'Transactions')

@section('content')

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-navy tracking-tight">Transactions</h1>
            <p class="text-sm text-gray-500 mt-1">View, edit, and manage all transactions</p>
        </div>
        <a href="{{ route('admin.transactions.create') }}"
            class="inline-flex items-center gap-2 px-4 py-2 bg-navy text-white rounded-lg
               text-sm font-semibold no-underline hover:bg-navy-dark transition-all self-start sm:self-auto">
            ➕ New Transaction
        </a>
    </div>

    {{-- Success Alert --}}
    @if ($message = Session::get('success'))
        <div
            class="flex items-center gap-3 px-4 py-3 mb-5 bg-green-50 border border-green-200
                    text-green-700 text-[13px] font-medium rounded-xl">
            <span>✓</span> {{ $message }}
        </div>
    @endif

    {{-- Table Card --}}
    <div class="bg-white border border-[#E5EAF2] rounded-2xl overflow-hidden">

        {{-- Table Scroll Wrapper --}}
        <div class="overflow-x-auto overflow-y-visible" style="-webkit-overflow-scrolling: touch;">
            <table class="w-full" style="min-width: 980px;">
                <thead>
                    <tr class="bg-[#EEF2FA]">
                        <th
                            class="text-left px-5 py-3 text-[11px] font-semibold text-gray-500 uppercase tracking-wider whitespace-nowrap">
                            ID</th>
                        <th
                            class="text-left px-5 py-3 text-[11px] font-semibold text-gray-500 uppercase tracking-wider whitespace-nowrap">
                            User</th>
                        <th
                            class="text-left px-5 py-3 text-[11px] font-semibold text-gray-500 uppercase tracking-wider whitespace-nowrap">
                            Type</th>
                        <th
                            class="text-left px-5 py-3 text-[11px] font-semibold text-gray-500 uppercase tracking-wider whitespace-nowrap">
                            Source</th>
                        <th
                            class="text-left px-5 py-3 text-[11px] font-semibold text-gray-500 uppercase tracking-wider whitespace-nowrap">
                            Amount</th>
                        <th
                            class="text-left px-5 py-3 text-[11px] font-semibold text-gray-500 uppercase tracking-wider whitespace-nowrap">
                            Category</th>
                        <th
                            class="text-left px-5 py-3 text-[11px] font-semibold text-gray-500 uppercase tracking-wider whitespace-nowrap">
                            Business</th>
                        <th
                            class="text-left px-5 py-3 text-[11px] font-semibold text-gray-500 uppercase tracking-wider whitespace-nowrap">
                            Date</th>
                        <th
                            class="text-left px-5 py-3 text-[11px] font-semibold text-gray-500 uppercase tracking-wider whitespace-nowrap">
                            Categorized</th>
                        <th
                            class="text-left px-5 py-3 text-[11px] font-semibold text-gray-500 uppercase tracking-wider whitespace-nowrap">
                            Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#F4F6FB]">
                    @forelse($transactions as $transaction)
                        <tr class="hover:bg-[#F8FAFC] transition-colors">

                            {{-- ID --}}
                            <td class="px-5 py-3 text-[13px] text-gray-400 font-mono whitespace-nowrap">
                                #{{ $transaction->id }}
                            </td>

                            {{-- User --}}
                            <td class="px-5 py-3">
                                <div class="flex items-center gap-2">
                                    <div
                                        class="w-7 h-7 rounded-full bg-[#EEF2FA] text-navy flex items-center
                                                justify-center text-[10px] font-bold shrink-0">
                                        {{ strtoupper(substr($transaction->user->name ?? 'U', 0, 2)) }}
                                    </div>
                                    <span class="text-[13px] font-medium text-navy whitespace-nowrap">
                                        {{ $transaction->user->name ?? '—' }}
                                    </span>
                                </div>
                            </td>

                            {{-- Type --}}
                            <td class="px-5 py-3 whitespace-nowrap">
                                @if ($transaction->type === 'credit')
                                    <span
                                        class="inline-flex items-center gap-1 px-2 py-[3px] bg-green-50
                                                 text-green-700 text-[11px] font-semibold rounded-md">
                                        ↑ Credit
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center gap-1 px-2 py-[3px] bg-red-50
                                                 text-red-600 text-[11px] font-semibold rounded-md">
                                        ↓ Debit
                                    </span>
                                @endif
                            </td>

                            {{-- Source --}}
                            <td class="px-5 py-3 whitespace-nowrap">
                                @php
                                    $sourceIcons = ['bank' => '🏦', 'upi' => '📱', 'cash' => '💵'];
                                    $icon = $sourceIcons[$transaction->source] ?? '💳';
                                @endphp
                                <span class="text-[13px] text-gray-600">
                                    {{ $icon }} {{ ucfirst($transaction->source) }}
                                </span>
                            </td>

                            {{-- Amount --}}
                            <td class="px-5 py-3 whitespace-nowrap">
                                <span
                                    class="text-[13px] font-semibold font-mono
                                    {{ $transaction->type === 'credit' ? 'text-green-600' : 'text-red-500' }}">
                                    {{ $transaction->type === 'credit' ? '+' : '-' }}
                                    ₹{{ number_format((float) $transaction->amount, 2) }}
                                </span>
                            </td>

                            {{-- Category --}}
                            <td class="px-5 py-3 whitespace-nowrap">
                                @if ($transaction->category)
                                    <span
                                        class="px-2 py-[2px] bg-[#EEF2FA] text-navy text-[11px]
                                                 font-medium rounded-md">
                                        {{ $transaction->category->name }}
                                    </span>
                                @else
                                    <span class="text-gray-300 text-[12px]">—</span>
                                @endif
                            </td>

                            {{-- Business --}}
                            <td class="px-5 py-3 text-[13px] text-gray-600 whitespace-nowrap">
                                {{ $transaction->business->name ?? '—' }}
                            </td>

                            {{-- Date --}}
                            <td class="px-5 py-3 text-[12px] text-gray-400 whitespace-nowrap">
                                {{ $transaction->transaction_date->format('d M Y') }}
                            </td>

                            {{-- Categorized --}}
                            <td class="px-5 py-3 whitespace-nowrap">
                                @if ($transaction->is_categorized)
                                    <span
                                        class="px-2 py-[3px] bg-green-50 text-green-700 text-[11px] font-semibold rounded-md">
                                        ✓ Yes
                                    </span>
                                @else
                                    <span
                                        class="px-2 py-[3px] bg-amber-50 text-amber-600 text-[11px] font-semibold rounded-md">
                                        ✗ No
                                    </span>
                                @endif
                            </td>

                            {{-- Actions --}}
                            <td class="px-5 py-3">
                                <div class="flex items-center gap-2 whitespace-nowrap">
                                    <a href="{{ route('admin.transactions.show', $transaction->id) }}"
                                        class="px-3 py-1 bg-[#EEF2FA] text-navy text-[12px] font-medium
                                               rounded-lg no-underline hover:bg-navy hover:text-white transition-all">
                                        View
                                    </a>
                                    <a href="{{ route('admin.transactions.edit', $transaction->id) }}"
                                        class="px-3 py-1 bg-amber-50 text-amber-700 text-[12px] font-medium
                                               rounded-lg no-underline hover:bg-amber-500 hover:text-white transition-all">
                                        Edit
                                    </a>
                                    <form method="POST"
                                        action="{{ route('admin.transactions.destroy', $transaction->id) }}"
                                        onsubmit="return confirm('Delete this transaction?')">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                            class="px-3 py-1 bg-red-50 text-red-600 text-[12px] font-medium
                                                   rounded-lg cursor-pointer border-none hover:bg-red-500
                                                   hover:text-white transition-all font-[Sora]">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="px-5 py-12 text-center text-[13px] text-gray-400">
                                <div class="flex flex-col items-center gap-2">
                                    <span class="text-3xl">📭</span>
                                    <span>No transactions found.</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if ($transactions->hasPages())
            <div class="px-5 py-4 border-t border-[#F4F6FB]">
                {{ $transactions->links() }}
            </div>
        @endif

    </div>

@endsection
