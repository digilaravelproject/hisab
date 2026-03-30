@extends('admin.layouts.app')

@section('title', 'Create Business')
@section('breadcrumb', 'Businesses / Create')

@section('content')

    {{-- Header --}}
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('admin.businesses.index') }}"
            class="w-9 h-9 bg-white border border-[#E5EAF2] rounded-lg flex items-center justify-center
                   text-gray-500 no-underline hover:bg-[#F0F4F8] transition-all shrink-0">←</a>
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-navy tracking-tight">Create Business</h1>
            <p class="text-sm text-gray-500 mt-1">Add a new business for a user</p>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.businesses.store') }}">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

            {{-- LEFT: Main Fields --}}
            <div class="lg:col-span-2 flex flex-col gap-5">

                {{-- Basic Info --}}
                <div class="bg-white border border-[#E5EAF2] rounded-2xl overflow-hidden">
                    <div class="px-5 py-4 border-b border-[#E5EAF2]">
                        <h3 class="text-sm font-semibold text-navy">Basic Information</h3>
                    </div>
                    <div class="p-5 grid grid-cols-1 sm:grid-cols-2 gap-4">

                        {{-- Owner --}}
                        <div class="sm:col-span-2">
                            <label class="block text-[13px] font-semibold text-navy mb-2">
                                Owner <span class="text-red-500">*</span>
                            </label>
                            <select name="user_id" required
                                class="w-full px-4 py-2.5 border border-[#E5EAF2] rounded-xl text-[13.5px]
                                       font-[Sora] text-navy outline-none transition-all bg-[#F8FAFC]
                                       focus:border-navy focus:bg-white
                                       @error('user_id') border-red-400 @enderror">
                                <option value="">— Select Owner —</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }} ({{ $user->mobile }})
                                    </option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <p class="text-red-500 text-[11px] mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Business Name --}}
                        <div class="sm:col-span-2">
                            <label class="block text-[13px] font-semibold text-navy mb-2">
                                Business Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" value="{{ old('name') }}"
                                placeholder="Enter business name" required
                                class="w-full px-4 py-2.5 border border-[#E5EAF2] rounded-xl text-[13.5px]
                                       font-[Sora] text-navy outline-none transition-all bg-[#F8FAFC]
                                       focus:border-navy focus:bg-white
                                       @error('name') border-red-400 @enderror">
                            @error('name')
                                <p class="text-red-500 text-[11px] mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Type --}}
                        <div>
                            <label class="block text-[13px] font-semibold text-navy mb-2">
                                Business Type <span class="text-red-500">*</span>
                            </label>
                            <select name="type" required
                                class="w-full px-4 py-2.5 border border-[#E5EAF2] rounded-xl text-[13.5px]
                                       font-[Sora] text-navy outline-none transition-all bg-[#F8FAFC]
                                       focus:border-navy focus:bg-white">
                                @foreach ([
            'farm' => '🌾 Farm',
            'shop' => '🏪 Shop',
            'transport' => '🚛 Transport',
            'store' => '🏬 Store',
            'other' => '💼 Other',
        ] as $value => $label)
                                    <option value="{{ $value }}"
                                        {{ old('type', 'other') === $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                    </div>
                </div>

                {{-- Financial Defaults --}}
                <div class="bg-white border border-[#E5EAF2] rounded-2xl overflow-hidden">
                    <div class="px-5 py-4 border-b border-[#E5EAF2]">
                        <h3 class="text-sm font-semibold text-navy">Financial Defaults</h3>
                        <p class="text-[11px] text-gray-400 mt-1">Standard monthly estimates for this business</p>
                    </div>
                    <div class="p-5 grid grid-cols-1 sm:grid-cols-2 gap-4">

                        {{-- Standard Income --}}
                        <div>
                            <label class="block text-[13px] font-semibold text-navy mb-2">
                                Standard Income (₹)
                            </label>
                            <div class="relative">
                                <span
                                    class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-[13px] font-mono">₹</span>
                                <input type="number" step="0.01" name="standard_income" min="0"
                                    value="{{ old('standard_income', '0') }}"
                                    class="w-full pl-7 pr-4 py-2.5 border border-[#E5EAF2] rounded-xl text-[13.5px]
                                           font-mono text-green-600 outline-none transition-all bg-[#F8FAFC]
                                           focus:border-navy focus:bg-white
                                           @error('standard_income') border-red-400 @enderror">
                            </div>
                            @error('standard_income')
                                <p class="text-red-500 text-[11px] mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Standard Expense --}}
                        <div>
                            <label class="block text-[13px] font-semibold text-navy mb-2">
                                Standard Expense (₹)
                            </label>
                            <div class="relative">
                                <span
                                    class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-[13px] font-mono">₹</span>
                                <input type="number" step="0.01" name="standard_expense" min="0"
                                    value="{{ old('standard_expense', '0') }}"
                                    class="w-full pl-7 pr-4 py-2.5 border border-[#E5EAF2] rounded-xl text-[13.5px]
                                           font-mono text-red-500 outline-none transition-all bg-[#F8FAFC]
                                           focus:border-navy focus:bg-white
                                           @error('standard_expense') border-red-400 @enderror">
                            </div>
                            @error('standard_expense')
                                <p class="text-red-500 text-[11px] mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>
                </div>

            </div>

            {{-- RIGHT: Settings + Submit --}}
            <div class="flex flex-col gap-5">

                {{-- Settings --}}
                <div class="bg-white border border-[#E5EAF2] rounded-2xl overflow-hidden">
                    <div class="px-5 py-4 border-b border-[#E5EAF2]">
                        <h3 class="text-sm font-semibold text-navy">Settings</h3>
                    </div>
                    <div class="p-5 flex flex-col gap-4">

                        {{-- Auto Tag --}}
                        <label class="flex items-center justify-between cursor-pointer">
                            <div>
                                <div class="text-[13px] font-semibold text-navy">Auto Tag Transactions</div>
                                <div class="text-[11px] text-gray-400 mt-1">Automatically tag transactions to this business
                                </div>
                            </div>
                            <div class="relative shrink-0 ml-3">
                                <input type="hidden" name="auto_tag_transactions" value="0">
                                <input type="checkbox" name="auto_tag_transactions" value="1"
                                    {{ old('auto_tag_transactions', '0') == '1' ? 'checked' : '' }} class="peer sr-only">
                                <div class="w-11 h-6 bg-gray-200 rounded-full peer peer-checked:bg-navy transition-colors">
                                </div>
                                <div
                                    class="absolute top-0.5 left-0.5 w-5 h-5 bg-white rounded-full shadow
                                            transition-all peer-checked:translate-x-5">
                                </div>
                            </div>
                        </label>

                        <div class="border-t border-[#F4F6FB]"></div>

                        {{-- Active --}}
                        <label class="flex items-center justify-between cursor-pointer">
                            <div>
                                <div class="text-[13px] font-semibold text-navy">Active</div>
                                <div class="text-[11px] text-gray-400 mt-1">Business is visible to the user</div>
                            </div>
                            <div class="relative shrink-0 ml-3">
                                <input type="hidden" name="active" value="0">
                                <input type="checkbox" name="active" value="1"
                                    {{ old('active', '1') == '1' ? 'checked' : '' }} class="peer sr-only">
                                <div class="w-11 h-6 bg-gray-200 rounded-full peer peer-checked:bg-navy transition-colors">
                                </div>
                                <div
                                    class="absolute top-0.5 left-0.5 w-5 h-5 bg-white rounded-full shadow
                                            transition-all peer-checked:translate-x-5">
                                </div>
                            </div>
                        </label>

                    </div>
                </div>

                {{-- Submit --}}
                <div class="flex flex-col gap-2">
                    <button type="submit"
                        class="w-full py-3 bg-navy text-white rounded-xl font-semibold text-[14px]
                               cursor-pointer border-none hover:bg-navy-dark transition-all font-[Sora]">
                        Save Business
                    </button>
                    <a href="{{ route('admin.businesses.index') }}"
                        class="w-full py-3 bg-white border border-[#E5EAF2] text-gray-500 rounded-xl
                               font-medium text-[14px] text-center no-underline hover:bg-[#F0F4F8] transition-all">
                        Cancel
                    </a>
                </div>

            </div>
        </div>
    </form>

@endsection
