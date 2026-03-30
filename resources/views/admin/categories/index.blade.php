@extends('admin.layouts.app')

@section('title', 'Categories')
@section('breadcrumb', 'Categories')

@section('content')

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-navy tracking-tight">Categories</h1>
            <p class="text-sm text-gray-500 mt-1">Create, edit and manage income/expense categories</p>
        </div>
        <a href="{{ route('admin.categories.create') }}"
            class="inline-flex items-center gap-2 px-4 py-2 bg-navy text-white rounded-lg
               text-sm font-semibold no-underline hover:bg-navy-dark transition-all self-start sm:self-auto">
            ➕ New Category
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
            <table class="w-full" style="min-width: 640px;">
                <thead>
                    <tr class="bg-[#EEF2FA]">
                        <th
                            class="text-left px-5 py-3 text-[11px] font-semibold text-gray-500 uppercase tracking-wider whitespace-nowrap">
                            ID</th>
                        <th
                            class="text-left px-5 py-3 text-[11px] font-semibold text-gray-500 uppercase tracking-wider whitespace-nowrap">
                            Name</th>
                        <th
                            class="text-left px-5 py-3 text-[11px] font-semibold text-gray-500 uppercase tracking-wider whitespace-nowrap">
                            Type</th>
                        <th
                            class="text-left px-5 py-3 text-[11px] font-semibold text-gray-500 uppercase tracking-wider whitespace-nowrap">
                            Icon</th>
                        <th
                            class="text-left px-5 py-3 text-[11px] font-semibold text-gray-500 uppercase tracking-wider whitespace-nowrap">
                            Status</th>
                        <th
                            class="text-left px-5 py-3 text-[11px] font-semibold text-gray-500 uppercase tracking-wider whitespace-nowrap">
                            Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#F4F6FB]">
                    @forelse($categories as $category)
                        <tr class="hover:bg-[#F8FAFC] transition-colors">

                            {{-- ID --}}
                            <td class="px-5 py-3 text-[13px] text-gray-400 font-mono whitespace-nowrap">
                                #{{ $category->id }}
                            </td>

                            {{-- Name --}}
                            <td class="px-5 py-3 whitespace-nowrap">
                                <div class="flex items-center gap-2">
                                    @if ($category->icon)
                                        <span class="text-lg leading-none">{{ $category->icon }}</span>
                                    @endif
                                    <span class="text-[13px] font-semibold text-navy">{{ $category->name }}</span>
                                </div>
                            </td>

                            {{-- Type --}}
                            <td class="px-5 py-3 whitespace-nowrap">
                                @if ($category->type === 'credit')
                                    <span
                                        class="px-2 py-[3px] bg-green-50 text-green-700 text-[11px] font-semibold rounded-md">
                                        ↑ Credit
                                    </span>
                                @elseif($category->type === 'debit')
                                    <span class="px-2 py-[3px] bg-red-50 text-red-600 text-[11px] font-semibold rounded-md">
                                        ↓ Debit
                                    </span>
                                @else
                                    <span class="px-2 py-[3px] bg-[#EEF2FA] text-navy text-[11px] font-semibold rounded-md">
                                        {{ ucfirst($category->type) }}
                                    </span>
                                @endif
                            </td>

                            {{-- Icon --}}
                            <td class="px-5 py-3 whitespace-nowrap">
                                @if ($category->icon)
                                    <span class="text-xl leading-none">{{ $category->icon }}</span>
                                @else
                                    <span class="text-gray-300 text-[12px]">—</span>
                                @endif
                            </td>

                            {{-- Status --}}
                            <td class="px-5 py-3 whitespace-nowrap">
                                @if ($category->is_active)
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
                                    <a href="{{ route('admin.categories.edit', $category->id) }}"
                                        class="px-3 py-1 bg-amber-50 text-amber-700 text-[12px] font-medium
                                               rounded-lg no-underline hover:bg-amber-500 hover:text-white transition-all">
                                        Edit
                                    </a>

                                    {{-- Toggle --}}
                                    <form method="POST" action="{{ route('admin.categories.toggle', $category->id) }}">
                                        @csrf
                                        <button type="submit"
                                            class="px-3 py-1 text-[12px] font-medium rounded-lg cursor-pointer
                                                   border-none transition-all font-[Sora]
                                                   {{ $category->is_active
                                                       ? 'bg-gray-100 text-gray-600 hover:bg-gray-400 hover:text-white'
                                                       : 'bg-blue-50 text-blue-600 hover:bg-blue-500 hover:text-white' }}">
                                            {{ $category->is_active ? 'Deactivate' : 'Activate' }}
                                        </button>
                                    </form>

                                    {{-- Delete --}}
                                    <form method="POST" action="{{ route('admin.categories.destroy', $category->id) }}"
                                        onsubmit="return confirm('Delete this category?')">
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
                            <td colspan="6" class="px-5 py-12 text-center text-[13px] text-gray-400">
                                <div class="flex flex-col items-center gap-2">
                                    <span class="text-3xl">🗂️</span>
                                    <span>No categories found.</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if ($categories->hasPages())
            <div class="px-5 py-4 border-t border-[#F4F6FB]">
                {{ $categories->links() }}
            </div>
        @endif

    </div>

@endsection
