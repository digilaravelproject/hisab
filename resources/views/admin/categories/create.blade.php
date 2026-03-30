@extends('admin.layouts.app')

@section('title', 'Create Category')
@section('breadcrumb', 'Categories / Create')

@section('content')

    {{-- Header --}}
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('admin.categories.index') }}"
            class="w-9 h-9 bg-white border border-[#E5EAF2] rounded-lg flex items-center justify-center
                   text-gray-500 no-underline hover:bg-[#F0F4F8] transition-all shrink-0">←</a>
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-navy tracking-tight">Create Category</h1>
            <p class="text-sm text-gray-500 mt-1">Add a new income or expense category</p>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.categories.store') }}">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

            {{-- LEFT: Main Fields --}}
            <div class="lg:col-span-2 flex flex-col gap-5">

                {{-- Basic Info --}}
                <div class="bg-white border border-[#E5EAF2] rounded-2xl overflow-hidden">
                    <div class="px-5 py-4 border-b border-[#E5EAF2]">
                        <h3 class="text-sm font-semibold text-navy">Category Details</h3>
                    </div>
                    <div class="p-5 grid grid-cols-1 sm:grid-cols-2 gap-4">

                        {{-- Name --}}
                        <div class="sm:col-span-2">
                            <label class="block text-[13px] font-semibold text-navy mb-2">
                                Category Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" value="{{ old('name') }}"
                                placeholder="e.g. Salary, Groceries, Rent..." required
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
                                Type <span class="text-red-500">*</span>
                            </label>
                            <select name="type" required
                                class="w-full px-4 py-2.5 border border-[#E5EAF2] rounded-xl text-[13.5px]
                                       font-[Sora] text-navy outline-none transition-all bg-[#F8FAFC]
                                       focus:border-navy focus:bg-white
                                       @error('type') border-red-400 @enderror">
                                <option value="credit" {{ old('type') === 'credit' ? 'selected' : '' }}>↑ Credit (Income)
                                </option>
                                <option value="debit" {{ old('type', 'debit') === 'debit' ? 'selected' : '' }}>↓ Debit
                                    (Expense)</option>
                            </select>
                            @error('type')
                                <p class="text-red-500 text-[11px] mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Icon --}}
                        <div>
                            <label class="block text-[13px] font-semibold text-navy mb-2">
                                Icon <span class="text-gray-400 font-normal">(emoji)</span>
                            </label>
                            <div class="relative">
                                <input type="text" name="icon" value="{{ old('icon') }}" placeholder="e.g. 💰 🏠 🚗"
                                    maxlength="10"
                                    class="w-full px-4 py-2.5 border border-[#E5EAF2] rounded-xl text-[18px]
                                           text-navy outline-none transition-all bg-[#F8FAFC]
                                           focus:border-navy focus:bg-white
                                           @error('icon') border-red-400 @enderror"
                                    id="iconInput"
                                    oninput="document.getElementById('iconPreview').textContent = this.value || '?'">
                            </div>
                            @error('icon')
                                <p class="text-red-500 text-[11px] mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-[11px] text-gray-400 mt-1">Type or paste any emoji</p>
                        </div>

                    </div>
                </div>

            </div>

            {{-- RIGHT: Preview + Settings + Submit --}}
            <div class="flex flex-col gap-5">

                {{-- Icon Preview --}}
                <div class="bg-white border border-[#E5EAF2] rounded-2xl overflow-hidden">
                    <div class="px-5 py-4 border-b border-[#E5EAF2]">
                        <h3 class="text-sm font-semibold text-navy">Preview</h3>
                    </div>
                    <div class="p-5 flex flex-col items-center gap-3">
                        <div class="w-16 h-16 rounded-2xl bg-[#EEF2FA] flex items-center justify-center text-3xl">
                            <span id="iconPreview">{{ old('icon') ?: '?' }}</span>
                        </div>
                        <p class="text-[12px] text-gray-400 text-center">Icon preview</p>
                    </div>
                </div>

                {{-- Status --}}
                <div class="bg-white border border-[#E5EAF2] rounded-2xl overflow-hidden">
                    <div class="px-5 py-4 border-b border-[#E5EAF2]">
                        <h3 class="text-sm font-semibold text-navy">Status</h3>
                    </div>
                    <div class="p-5">
                        <label class="flex items-center justify-between cursor-pointer">
                            <div>
                                <div class="text-[13px] font-semibold text-navy">Active</div>
                                <div class="text-[11px] text-gray-400 mt-1">Visible to users in the app</div>
                            </div>
                            <div class="relative shrink-0 ml-3">
                                <input type="hidden" name="is_active" value="0">
                                <input type="checkbox" name="is_active" value="1"
                                    {{ old('is_active', '1') == '1' ? 'checked' : '' }} class="peer sr-only">
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
                        Save Category
                    </button>
                    <a href="{{ route('admin.categories.index') }}"
                        class="w-full py-3 bg-white border border-[#E5EAF2] text-gray-500 rounded-xl
                               font-medium text-[14px] text-center no-underline hover:bg-[#F0F4F8] transition-all">
                        Cancel
                    </a>
                </div>

            </div>
        </div>
    </form>

@endsection
