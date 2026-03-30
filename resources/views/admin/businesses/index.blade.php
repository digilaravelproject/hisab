@extends('admin.layouts.app')

@section('title', 'Businesses')
@section('breadcrumb', 'Businesses')

@section('content')

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-navy tracking-tight">Businesses</h1>
            <p class="text-sm text-gray-500 mt-1">Create, edit and manage user businesses</p>
        </div>
        <a href="{{ route('admin.businesses.create') }}"
            class="inline-flex items-center gap-2 px-4 py-2 bg-navy text-white rounded-lg
               text-sm font-semibold no-underline hover:bg-navy-dark transition-all self-start sm:self-auto">
            ➕ New Business
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

        {{-- Scroll Wrapper --}}
        <div class="overflow-x-auto overflow-y-visible" style="-webkit-overflow-scrolling: touch;">
            <table class="w-full" style="min-width: 920px;">
                <thead>
                    <tr class="bg-[#EEF2FA]">
                        <th
                            class="text-left px-5 py-3 text-[11px] font-semibold text-gray-500 uppercase tracking-wider whitespace-nowrap">
                            ID</th>
                        <th
                            class="text-left px-5 py-3 text-[11px] font-semibold text-gray-500 uppercase tracking-wider whitespace-nowrap">
                            Owner</th>
                        <th
                            class="text-left px-5 py-3 text-[11px] font-semibold text-gray-500 uppercase tracking-wider whitespace-nowrap">
                            Business Name</th>
                        <th
                            class="text-left px-5 py-3 text-[11px] font-semibold text-gray-500 uppercase tracking-wider whitespace-nowrap">
                            Type</th>
                        <th
                            class="text-left px-5 py-3 text-[11px] font-semibold text-gray-500 uppercase tracking-wider whitespace-nowrap">
                            Std. Income</th>
                        <th
                            class="text-left px-5 py-3 text-[11px] font-semibold text-gray-500 uppercase tracking-wider whitespace-nowrap">
                            Std. Expense</th>
                        <th
                            class="text-left px-5 py-3 text-[11px] font-semibold text-gray-500 uppercase tracking-wider whitespace-nowrap">
                            Auto Tag</th>
                        <th
                            class="text-left px-5 py-3 text-[11px] font-semibold text-gray-500 uppercase tracking-wider whitespace-nowrap">
                            Status</th>
                        <th
                            class="text-left px-5 py-3 text-[11px] font-semibold text-gray-500 uppercase tracking-wider whitespace-nowrap">
                            Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#F4F6FB]">
                    @forelse($businesses as $business)
                        <tr class="hover:bg-[#F8FAFC] transition-colors">

                            {{-- ID --}}
                            <td class="px-5 py-3 text-[13px] text-gray-400 font-mono whitespace-nowrap">
                                #{{ $business->id }}
                            </td>

                            {{-- Owner --}}
                            <td class="px-5 py-3">
                                <div class="flex items-center gap-2">
                                    <div
                                        class="w-7 h-7 rounded-full bg-[#EEF2FA] text-navy flex items-center
                                                justify-center text-[10px] font-bold shrink-0">
                                        {{ strtoupper(substr($business->user->name ?? 'U', 0, 2)) }}
                                    </div>
                                    <span class="text-[13px] font-medium text-navy whitespace-nowrap">
                                        {{ $business->user->name ?? '—' }}
                                    </span>
                                </div>
                            </td>

                            {{-- Business Name --}}
                            <td class="px-5 py-3 whitespace-nowrap">
                                <span class="text-[13px] font-semibold text-navy">{{ $business->name }}</span>
                            </td>

                            {{-- Type --}}
                            <td class="px-5 py-3 whitespace-nowrap">
                                @php
                                    $typeIcons = [
                                        'farm' => '🌾',
                                        'shop' => '🏪',
                                        'transport' => '🚛',
                                        'store' => '🏬',
                                        'other' => '💼',
                                    ];
                                    $icon = $typeIcons[$business->type] ?? '💼';
                                @endphp
                                <span
                                    class="px-2 py-[2px] bg-[#EEF2FA] text-navy text-[11px] font-medium rounded-md whitespace-nowrap">
                                    {{ $icon }} {{ ucfirst($business->type) }}
                                </span>
                            </td>

                            {{-- Standard Income --}}
                            <td class="px-5 py-3 whitespace-nowrap">
                                <span class="text-[13px] font-mono font-semibold text-green-600">
                                    ₹{{ number_format((float) $business->standard_income, 2) }}
                                </span>
                            </td>

                            {{-- Standard Expense --}}
                            <td class="px-5 py-3 whitespace-nowrap">
                                <span class="text-[13px] font-mono font-semibold text-red-500">
                                    ₹{{ number_format((float) $business->standard_expense, 2) }}
                                </span>
                            </td>

                            {{-- Auto Tag --}}
                            <td class="px-5 py-3 whitespace-nowrap">
                                @if ($business->auto_tag_transactions)
                                    <span
                                        class="px-2 py-[3px] bg-blue-50 text-blue-600 text-[11px] font-semibold rounded-md">
                                        ✓ On
                                    </span>
                                @else
                                    <span
                                        class="px-2 py-[3px] bg-gray-50 text-gray-400 text-[11px] font-semibold rounded-md">
                                        ✗ Off
                                    </span>
                                @endif
                            </td>

                            {{-- Status --}}
                            <td class="px-5 py-3 whitespace-nowrap">
                                @if ($business->active)
                                    <span
                                        class="px-2 py-[3px] bg-green-50 text-green-700 text-[11px] font-semibold rounded-md">
                                        ✓ Active
                                    </span>
                                @else
                                    <span class="px-2 py-[3px] bg-red-50 text-red-600 text-[11px] font-semibold rounded-md">
                                        ✗ Inactive
                                    </span>
                                @endif
                            </td>

                            {{-- Actions --}}
                            <td class="px-5 py-3">
                                <div class="flex items-center gap-2 whitespace-nowrap">

                                    {{-- Edit --}}
                                    <a href="{{ route('admin.businesses.edit', $business->id) }}"
                                        class="px-3 py-1 bg-amber-50 text-amber-700 text-[12px] font-medium
                                               rounded-lg no-underline hover:bg-amber-500 hover:text-white transition-all">
                                        Edit
                                    </a>

                                    {{-- Toggle Active --}}
                                    <form method="POST" action="{{ route('admin.businesses.toggle', $business->id) }}">
                                        @csrf
                                        <button type="submit"
                                            class="px-3 py-1 text-[12px] font-medium rounded-lg cursor-pointer
                                                   border-none transition-all font-[Sora]
                                                   {{ $business->active
                                                       ? 'bg-gray-100 text-gray-600 hover:bg-gray-400 hover:text-white'
                                                       : 'bg-blue-50 text-blue-600 hover:bg-blue-500 hover:text-white' }}">
                                            {{ $business->active ? 'Deactivate' : 'Activate' }}
                                        </button>
                                    </form>

                                    {{-- Delete --}}
                                    <form method="POST" action="{{ route('admin.businesses.destroy', $business->id) }}"
                                        onsubmit="return confirm('Delete this business?')">
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
                            <td colspan="9" class="px-5 py-12 text-center text-[13px] text-gray-400">
                                <div class="flex flex-col items-center gap-2">
                                    <span class="text-3xl">🏢</span>
                                    <span>No businesses found.</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if ($businesses->hasPages())
            <div class="px-5 py-4 border-t border-[#F4F6FB]">
                {{ $businesses->links() }}
            </div>
        @endif

    </div>

@endsection
