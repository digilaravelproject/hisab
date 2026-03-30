@extends('admin.layouts.app')

@section('title', 'Monthly Report')
@section('breadcrumb', 'Reports / Monthly')

@section('content')

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-navy tracking-tight">Monthly Report</h1>
            <p class="text-sm text-gray-500 mt-1">Current month performance and category breakdown.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.reports.export', request()->query()) }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-white text-gray-700 border border-[#E5EAF2] rounded-lg text-sm font-semibold no-underline hover:bg-[#F7F9FC] transition-all">
                📤 Export Data
            </a>
        </div>
    </div>

    <form method="GET" action="{{ route('admin.reports.monthly') }}" class="space-y-5 mb-6">
        <div class="grid grid-cols-1 xl:grid-cols-6 gap-4">
            <div>
                <label class="block text-[13px] font-semibold text-navy mb-2">Month</label>
                <select name="month" class="w-full px-4 py-2.5 border border-[#E5EAF2] rounded-xl text-[13.5px] bg-[#F8FAFC] focus:border-navy focus:bg-white">
                    @foreach(range(1, 12) as $m)
                        <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>{{ \Carbon\Carbon::createFromDate(2000, $m, 1)->format('F') }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-[13px] font-semibold text-navy mb-2">Year</label>
                <select name="year" class="w-full px-4 py-2.5 border border-[#E5EAF2] rounded-xl text-[13.5px] bg-[#F8FAFC] focus:border-navy focus:bg-white">
                    @foreach(range(now()->year - 2, now()->year + 2) as $y)
                        <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-[13px] font-semibold text-navy mb-2">User</label>
                <select name="user_id" class="w-full px-4 py-2.5 border border-[#E5EAF2] rounded-xl text-[13.5px] bg-[#F8FAFC] focus:border-navy focus:bg-white">
                    <option value="">All users</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-[13px] font-semibold text-navy mb-2">Category</label>
                <select name="category_id" class="w-full px-4 py-2.5 border border-[#E5EAF2] rounded-xl text-[13.5px] bg-[#F8FAFC] focus:border-navy focus:bg-white">
                    <option value="">All categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-[13px] font-semibold text-navy mb-2">Business</label>
                <select name="business_id" class="w-full px-4 py-2.5 border border-[#E5EAF2] rounded-xl text-[13.5px] bg-[#F8FAFC] focus:border-navy focus:bg-white">
                    <option value="">All businesses</option>
                    @foreach($businesses as $business)
                        <option value="{{ $business->id }}" {{ request('business_id') == $business->id ? 'selected' : '' }}>{{ $business->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-[13px] font-semibold text-navy mb-2">Type</label>
                <select name="type" class="w-full px-4 py-2.5 border border-[#E5EAF2] rounded-xl text-[13.5px] bg-[#F8FAFC] focus:border-navy focus:bg-white">
                    <option value="both" {{ request('type', 'both') === 'both' ? 'selected' : '' }}>All transactions</option>
                    <option value="credit" {{ request('type') === 'credit' ? 'selected' : '' }}>Credit</option>
                    <option value="debit" {{ request('type') === 'debit' ? 'selected' : '' }}>Debit</option>
                </select>
            </div>
        </div>
        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-3">
            <div class="text-sm text-gray-500">Showing month data for {{ \Carbon\Carbon::createFromDate($year, $month, 1)->format('F Y') }}.</div>
            <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 bg-navy text-white rounded-lg text-sm font-semibold hover:bg-navy-dark transition-all">Apply Filters</button>
        </div>
    </form>

    <div class="grid grid-cols-1 xl:grid-cols-4 gap-4 mb-6">
        <div class="bg-white border border-[#E5EAF2] rounded-2xl p-5">
            <div class="text-[12px] text-gray-500 uppercase tracking-[0.2em] mb-2">Total Credit</div>
            <div class="text-2xl font-semibold text-emerald-600">₹{{ number_format($totalCredit, 2) }}</div>
        </div>
        <div class="bg-white border border-[#E5EAF2] rounded-2xl p-5">
            <div class="text-[12px] text-gray-500 uppercase tracking-[0.2em] mb-2">Total Debit</div>
            <div class="text-2xl font-semibold text-red-600">₹{{ number_format($totalDebit, 2) }}</div>
        </div>
        <div class="bg-white border border-[#E5EAF2] rounded-2xl p-5">
            <div class="text-[12px] text-gray-500 uppercase tracking-[0.2em] mb-2">Net</div>
            <div class="text-2xl font-semibold text-navy">₹{{ number_format($net, 2) }}</div>
        </div>
        <div class="bg-white border border-[#E5EAF2] rounded-2xl p-5">
            <div class="text-[12px] text-gray-500 uppercase tracking-[0.2em] mb-2">Transactions</div>
            <div class="text-2xl font-semibold text-gray-800">{{ $totalCount }}</div>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-4 mb-6">
        <div class="col-span-2 bg-white border border-[#E5EAF2] rounded-2xl overflow-hidden">
            <div class="px-5 py-4 border-b border-[#E5EAF2]">
                <h3 class="text-sm font-semibold text-navy">Transactions</h3>
            </div>
            <div class="overflow-x-auto overflow-y-visible" style="-webkit-overflow-scrolling: touch;">
                <table class="w-full" style="min-width: 740px;">
                    <thead>
                        <tr class="bg-[#EEF2FA]">
                            <th class="text-left px-5 py-3 text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="text-left px-5 py-3 text-[11px] font-semibold text-gray-500 uppercase tracking-wider">User</th>
                            <th class="text-left px-5 py-3 text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Category</th>
                            <th class="text-left px-5 py-3 text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Type</th>
                            <th class="text-left px-5 py-3 text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Amount</th>
                            <th class="text-left px-5 py-3 text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Business</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#F4F6FB]">
                        @forelse($transactions as $txn)
                            <tr class="hover:bg-[#F8FAFC] transition-colors">
                                <td class="px-5 py-3 text-[13px] text-gray-600">{{ $txn->transaction_date?->format('d M Y') }}</td>
                                <td class="px-5 py-3 text-[13px] text-gray-700">{{ $txn->user?->name ?? 'Unknown' }}</td>
                                <td class="px-5 py-3 text-[13px] text-gray-700">{{ $txn->category?->name ?? 'Uncategorized' }}</td>
                                <td class="px-5 py-3 text-[13px] font-semibold {{ $txn->type === 'credit' ? 'text-emerald-600' : 'text-red-600' }}">{{ ucfirst($txn->type) }}</td>
                                <td class="px-5 py-3 text-[13px] text-gray-700">₹{{ number_format($txn->amount, 2) }}</td>
                                <td class="px-5 py-3 text-[13px] text-gray-700">{{ $txn->business?->name ?? '—' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-12 text-center text-[13px] text-gray-400">
                                    No transactions found for this month.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($transactions->hasPages())
                <div class="px-5 py-4 border-t border-[#F4F6FB]">
                    {{ $transactions->links() }}
                </div>
            @endif
        </div>

        <div class="bg-white border border-[#E5EAF2] rounded-2xl overflow-hidden">
            <div class="px-5 py-4 border-b border-[#E5EAF2]">
                <h3 class="text-sm font-semibold text-navy">Category Breakdown</h3>
            </div>
            <div class="p-5 space-y-3">
                @forelse($categorySummary as $item)
                    <div class="flex items-center justify-between gap-3 text-[13px] text-gray-700">
                        <div>
                            <div class="font-semibold text-navy">{{ $item->category?->name ?? 'Uncategorized' }}</div>
                            <div class="text-gray-400 text-[12px]">{{ $item->transactions }} transactions</div>
                        </div>
                        <div class="text-right">
                            <div class="text-[13px] text-emerald-600">₹{{ number_format($item->credit, 2) }}</div>
                            <div class="text-[13px] text-red-600">₹{{ number_format($item->debit, 2) }}</div>
                        </div>
                    </div>
                @empty
                    <div class="text-[13px] text-gray-400">No category activity found.</div>
                @endforelse
            </div>
        </div>
    </div>

@endsection
