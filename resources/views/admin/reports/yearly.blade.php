@extends('admin.layouts.app')

@section('title', 'Yearly Report')
@section('breadcrumb', 'Reports / Yearly')

@section('content')

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-navy tracking-tight">Yearly Report</h1>
            <p class="text-sm text-gray-500 mt-1">Annual report with monthly totals and category performance.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.reports.export', request()->query()) }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-white text-gray-700 border border-[#E5EAF2] rounded-lg text-sm font-semibold no-underline hover:bg-[#F7F9FC] transition-all">
                📤 Export Data
            </a>
        </div>
    </div>

    <form method="GET" action="{{ route('admin.reports.yearly') }}" class="space-y-5 mb-6">
        <div class="grid grid-cols-1 xl:grid-cols-6 gap-4">
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
            <div class="text-sm text-gray-500">Showing annual data for {{ $year }}.</div>
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

    <div class="bg-white border border-[#E5EAF2] rounded-2xl overflow-hidden mb-6">
        <div class="px-5 py-4 border-b border-[#E5EAF2]">
            <h3 class="text-sm font-semibold text-navy">Monthly Totals</h3>
        </div>
        <div class="overflow-x-auto overflow-y-visible" style="-webkit-overflow-scrolling: touch;">
            <table class="w-full" style="min-width: 740px;">
                <thead>
                    <tr class="bg-[#EEF2FA]">
                        <th class="text-left px-5 py-3 text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Month</th>
                        <th class="text-left px-5 py-3 text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Credit</th>
                        <th class="text-left px-5 py-3 text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Debit</th>
                        <th class="text-left px-5 py-3 text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Net</th>
                        <th class="text-left px-5 py-3 text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Transactions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#F4F6FB]">
                    @foreach(range(1, 12) as $monthIndex)
                        @php $item = $monthlySummary->get($monthIndex); @endphp
                        <tr class="hover:bg-[#F8FAFC] transition-colors">
                            <td class="px-5 py-3 text-[13px] text-gray-700">{{ \Carbon\Carbon::createFromDate($year, $monthIndex, 1)->format('F') }}</td>
                            <td class="px-5 py-3 text-[13px] text-emerald-600">₹{{ number_format($item->credit ?? 0, 2) }}</td>
                            <td class="px-5 py-3 text-[13px] text-red-600">₹{{ number_format($item->debit ?? 0, 2) }}</td>
                            <td class="px-5 py-3 text-[13px] text-navy">₹{{ number_format(($item->credit ?? 0) - ($item->debit ?? 0), 2) }}</td>
                            <td class="px-5 py-3 text-[13px] text-gray-700">{{ $item->transactions ?? 0 }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
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

@endsection
