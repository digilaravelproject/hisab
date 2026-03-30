@extends('admin.layouts.app')

@section('title', 'Users')
@section('breadcrumb', 'Users')

@section('content')

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-navy tracking-tight">Users</h1>
            <p class="text-sm text-gray-500 mt-1">Total {{ $users->count() }} registered users</p>
        </div>
        <a href="{{ route('admin.users.create') }}"
            class="inline-flex items-center gap-2 px-4 py-2 bg-navy text-white rounded-lg
              text-sm font-semibold no-underline hover:bg-navy-dark transition-all self-start sm:self-auto">
            ➕ Add New User
        </a>
    </div>

    {{-- Table Card --}}
    <div class="bg-white border border-[#E5EAF2] rounded-2xl" style="min-width:0; max-width:100%; overflow:hidden;">

        {{-- ✅ ONLY CHANGE HERE --}}
        <div style="overflow:auto; max-height:60vh; -webkit-overflow-scrolling:touch;">

            <table class="data-table w-full" id="usersTable" style="min-width:760px;">
                <thead class="sticky top-0 z-10 bg-[#EEF2FA]">
                    <tr class="bg-[#EEF2FA]">
                        <th class="text-left px-5 py-3 text-[11px] font-semibold text-gray-500 uppercase tracking-wider">#
                        </th>
                        <th class="text-left px-5 py-3 text-[11px] font-semibold text-gray-500 uppercase tracking-wider">
                            User</th>
                        <th class="text-left px-5 py-3 text-[11px] font-semibold text-gray-500 uppercase tracking-wider">
                            Mobile</th>
                        <th class="text-left px-5 py-3 text-[11px] font-semibold text-gray-500 uppercase tracking-wider">
                            Gender</th>
                        <th class="text-left px-5 py-3 text-[11px] font-semibold text-gray-500 uppercase tracking-wider">
                            Roles</th>
                        <th class="text-left px-5 py-3 text-[11px] font-semibold text-gray-500 uppercase tracking-wider">
                            Transactions</th>
                        <th class="text-left px-5 py-3 text-[11px] font-semibold text-gray-500 uppercase tracking-wider">
                            Status</th>
                        <th class="text-left px-5 py-3 text-[11px] font-semibold text-gray-500 uppercase tracking-wider">
                            Joined</th>
                        <th class="text-left px-5 py-3 text-[11px] font-semibold text-gray-500 uppercase tracking-wider">
                            Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#F4F6FB]">
                    @foreach ($users as $user)
                        <tr class="hover:bg-[#F8FAFC] transition-colors">
                            <td class="px-5 py-3 text-[13px] text-gray-400 font-mono">{{ $user->id }}</td>

                            <td class="px-5 py-3">
                                <div class="flex items-center gap-3">
                                    @if ($user->profile_photo)
                                        <img src="{{ asset('storage/' . $user->profile_photo) }}"
                                            class="w-9 h-9 rounded-full object-cover shrink-0" alt="">
                                    @else
                                        <div
                                            class="w-9 h-9 rounded-full bg-[#EEF2FA] text-navy flex items-center
                                            justify-center text-xs font-bold shrink-0">
                                            {{ strtoupper(substr($user->name ?: 'U', 0, 2)) }}
                                        </div>
                                    @endif
                                    <div class="min-w-0">
                                        <div class="text-[13px] font-semibold text-navy truncate max-w-[140px]">
                                            {{ $user->name ?: '—' }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <td class="px-5 py-3 text-[13px] font-mono text-gray-600">{{ $user->mobile }}</td>

                            <td class="px-5 py-3">
                                @if ($user->gender)
                                    <span class="capitalize text-[12px] text-gray-600">{{ $user->gender }}</span>
                                @else
                                    <span class="text-gray-300 text-[12px]">—</span>
                                @endif
                            </td>

                            <td class="px-5 py-3">
                                <div class="flex flex-wrap gap-1">
                                    @forelse($user->user_types ?? [] as $type)
                                        <span
                                            class="px-2 py-[2px] bg-[#EEF2FA] text-navy text-[10px]
                                             font-semibold rounded-md capitalize">
                                            {{ str_replace('_', ' ', $type) }}
                                        </span>
                                    @empty
                                        <span class="text-gray-300 text-[12px]">—</span>
                                    @endforelse
                                </div>
                            </td>

                            <td class="px-5 py-3 text-[13px] font-mono text-gray-600">
                                {{ number_format($user->transactions_count) }}
                            </td>

                            <td class="px-5 py-3">
                                @if ($user->is_active)
                                    <span
                                        class="px-2 py-[3px] bg-green-50 text-green-700 text-[11px]
                                         font-semibold rounded-md">✓
                                        Active</span>
                                @else
                                    <span
                                        class="px-2 py-[3px] bg-red-50 text-red-600 text-[11px]
                                         font-semibold rounded-md">✗
                                        Inactive</span>
                                @endif
                            </td>

                            <td class="px-5 py-3 text-[12px] text-gray-400 whitespace-nowrap">
                                {{ $user->created_at->format('d M Y') }}
                            </td>

                            <td class="px-5 py-3">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('admin.users.show', $user->id) }}"
                                        class="px-3 py-1 bg-[#EEF2FA] text-navy text-[12px] font-medium
                                      rounded-lg no-underline hover:bg-navy hover:text-white transition-all">
                                        View
                                    </a>
                                    <a href="{{ route('admin.users.edit', $user->id) }}"
                                        class="px-3 py-1 bg-amber-50 text-amber-700 text-[12px] font-medium
                                      rounded-lg no-underline hover:bg-amber-500 hover:text-white transition-all">
                                        Edit
                                    </a>
                                    <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}"
                                        onsubmit="return confirm('Are you sure you want to delete this user?')">
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
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

@endsection
