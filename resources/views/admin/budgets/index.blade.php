@extends('admin.layouts.app')

@section('title', 'Budgets')
@section('breadcrumb', 'Budgets')

@section('content')

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-navy tracking-tight">Budgets</h1>
            <p class="text-sm text-gray-500 mt-1">View and manage budget targets for users and categories.</p>
        </div>
        <a href="{{ route('admin.budgets.create') }}"
            class="inline-flex items-center gap-2 px-4 py-2 bg-navy text-white rounded-lg
               text-sm font-semibold no-underline hover:bg-navy-dark transition-all self-start sm:self-auto">
            ➕ New Budget
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

    <div class="bg-white border border-[#E5EAF2] rounded-2xl overflow-hidden">
        <div class="overflow-x-auto overflow-y-visible" style="-webkit-overflow-scrolling: touch;">
            <table class="w-full" style="min-width: 860px;">
                <thead>
                    <tr class="bg-[#EEF2FA]">
                        <th class="text-left px-5 py-3 text-[11px] font-semibold text-gray-500 uppercase tracking-wider whitespace-nowrap">ID</th>
                        <th class="text-left px-5 py-3 text-[11px] font-semibold text-gray-500 uppercase tracking-wider whitespace-nowrap">User</th>
                        <th class="text-left px-5 py-3 text-[11px] font-semibold text-gray-500 uppercase tracking-wider whitespace-nowrap">Category</th>
                        <th class="text-left px-5 py-3 text-[11px] font-semibold text-gray-500 uppercase tracking-wider whitespace-nowrap">Budget Time</th>
                        <th class="text-left px-5 py-3 text-[11px] font-semibold text-gray-500 uppercase tracking-wider whitespace-nowrap">Target Amount</th>
                        <th class="text-left px-5 py-3 text-[11px] font-semibold text-gray-500 uppercase tracking-wider whitespace-nowrap">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#F4F6FB]">
                    @forelse($budgets as $budget)
                        <tr class="hover:bg-[#F8FAFC] transition-colors">
                            <td class="px-5 py-3 text-[13px] text-gray-400 font-mono whitespace-nowrap">#{{ $budget->id }}</td>
                            <td class="px-5 py-3 whitespace-nowrap">
                                <div class="text-[13px] font-semibold text-navy">{{ $budget->user?->name ?? 'Unknown User' }}</div>
                                <div class="text-[11px] text-gray-400">{{ $budget->user?->email ?? '—' }}</div>
                            </td>
                            <td class="px-5 py-3 whitespace-nowrap">
                                <div class="text-[13px] font-semibold text-navy">{{ $budget->category?->name ?? 'Unknown Category' }}</div>
                                <div class="text-[11px] text-gray-400">{{ $budget->category?->type ?? '—' }}</div>
                            </td>
                            <td class="px-5 py-3 whitespace-nowrap text-[13px] text-gray-600">{{ $budget->budget_time }}</td>
                            <td class="px-5 py-3 whitespace-nowrap text-[13px] text-gray-600">₹{{ number_format($budget->target_amount, 2) }}</td>
                            <td class="px-5 py-3">
                                <div class="flex items-center gap-2 whitespace-nowrap">
                                    <a href="{{ route('admin.budgets.edit', $budget->id) }}"
                                        class="px-3 py-1 bg-amber-50 text-amber-700 text-[12px] font-medium rounded-lg no-underline hover:bg-amber-500 hover:text-white transition-all">
                                        Edit
                                    </a>

                                    <form method="POST" action="{{ route('admin.budgets.destroy', $budget->id) }}" onsubmit="return confirm('Delete this budget?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="px-3 py-1 bg-red-50 text-red-600 text-[12px] font-medium rounded-lg cursor-pointer border-none hover:bg-red-500 hover:text-white transition-all">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-12 text-center text-[13px] text-gray-400">
                                <div class="flex flex-col items-center gap-2">
                                    <span class="text-3xl">📊</span>
                                    <span>No budgets found.</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($budgets->hasPages())
            <div class="px-5 py-4 border-t border-[#F4F6FB]">
                {{ $budgets->links() }}
            </div>
        @endif
    </div>

@endsection
