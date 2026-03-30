@extends('admin.layouts.app')

@section('title', 'Edit Budget')
@section('breadcrumb', 'Budgets / Edit')

@section('content')

    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('admin.budgets.index') }}"
            class="w-9 h-9 bg-white border border-[#E5EAF2] rounded-lg flex items-center justify-center
                   text-gray-500 no-underline hover:bg-[#F0F4F8] transition-all shrink-0">←</a>
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-navy tracking-tight">Edit Budget</h1>
            <p class="text-sm text-gray-500 mt-1">Update the budget target details.</p>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.budgets.update', $budget->id) }}">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
            <div class="lg:col-span-2 flex flex-col gap-5">

                <div class="bg-white border border-[#E5EAF2] rounded-2xl overflow-hidden">
                    <div class="px-5 py-4 border-b border-[#E5EAF2]">
                        <h3 class="text-sm font-semibold text-navy">Budget Details</h3>
                    </div>
                    <div class="p-5 grid grid-cols-1 sm:grid-cols-2 gap-4">

                        <div class="sm:col-span-2">
                            <label class="block text-[13px] font-semibold text-navy mb-2">User <span class="text-red-500">*</span></label>
                            <select name="user_id" required
                                class="w-full px-4 py-2.5 border border-[#E5EAF2] rounded-xl text-[13.5px] text-navy outline-none transition-all bg-[#F8FAFC] focus:border-navy focus:bg-white @error('user_id') border-red-400 @enderror">
                                <option value="">Select a user</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ old('user_id', $budget->user_id) == $user->id ? 'selected' : '' }}>{{ $user->name }} ({{ $user->email }})</option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <p class="text-red-500 text-[11px] mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="sm:col-span-2">
                            <label class="block text-[13px] font-semibold text-navy mb-2">Category <span class="text-red-500">*</span></label>
                            <select name="category_id" required
                                class="w-full px-4 py-2.5 border border-[#E5EAF2] rounded-xl text-[13.5px] text-navy outline-none transition-all bg-[#F8FAFC] focus:border-navy focus:bg-white @error('category_id') border-red-400 @enderror">
                                <option value="">Select a category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id', $budget->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }} @if($category->type) ({{ ucfirst($category->type) }}) @endif</option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <p class="text-red-500 text-[11px] mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-[13px] font-semibold text-navy mb-2">Target Amount <span class="text-red-500">*</span></label>
                            <input type="number" name="target_amount" value="{{ old('target_amount', $budget->target_amount) }}" step="0.01" min="0"
                                placeholder="e.g. 5000"
                                class="w-full px-4 py-2.5 border border-[#E5EAF2] rounded-xl text-[13.5px] text-navy outline-none transition-all bg-[#F8FAFC] focus:border-navy focus:bg-white @error('target_amount') border-red-400 @enderror">
                            @error('target_amount')
                                <p class="text-red-500 text-[11px] mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-[13px] font-semibold text-navy mb-2">Budget Month <span class="text-red-500">*</span></label>
                            <select name="month" required
                                class="w-full px-4 py-2.5 border border-[#E5EAF2] rounded-xl text-[13.5px] text-navy outline-none transition-all bg-[#F8FAFC] focus:border-navy focus:bg-white @error('month') border-red-400 @enderror">
                                @foreach(range(1, 12) as $month)
                                    <option value="{{ $month }}" {{ old('month', $budget->month) == $month ? 'selected' : '' }}>{{ \Carbon\Carbon::createFromDate(2000, $month, 1)->format('F') }}</option>
                                @endforeach
                            </select>
                            @error('month')
                                <p class="text-red-500 text-[11px] mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-[13px] font-semibold text-navy mb-2">Budget Year <span class="text-red-500">*</span></label>
                            <select name="year" required
                                class="w-full px-4 py-2.5 border border-[#E5EAF2] rounded-xl text-[13.5px] text-navy outline-none transition-all bg-[#F8FAFC] focus:border-navy focus:bg-white @error('year') border-red-400 @enderror">
                                @php $currentYear = date('Y'); @endphp
                                @foreach(range($currentYear - 2, $currentYear + 2) as $year)
                                    <option value="{{ $year }}" {{ old('year', $budget->year) == $year ? 'selected' : '' }}>{{ $year }}</option>
                                @endforeach
                            </select>
                            @error('year')
                                <p class="text-red-500 text-[11px] mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>
                </div>

            </div>

            <div class="flex flex-col gap-5">
                <div class="bg-white border border-[#E5EAF2] rounded-2xl overflow-hidden">
                    <div class="px-5 py-4 border-b border-[#E5EAF2]">
                        <h3 class="text-sm font-semibold text-navy">Summary</h3>
                    </div>
                    <div class="p-5 text-[13px] text-gray-500 space-y-3">
                        <p>Budget ID: #{{ $budget->id }}</p>
                        <p>Current period: {{ $budget->budget_time }}</p>
                    </div>
                </div>

                <div class="flex flex-col gap-2">
                    <button type="submit"
                        class="w-full py-3 bg-navy text-white rounded-xl font-semibold text-[14px]
                               cursor-pointer border-none hover:bg-navy-dark transition-all font-[Sora]">Update Budget</button>
                    <a href="{{ route('admin.budgets.index') }}"
                        class="w-full py-3 bg-white border border-[#E5EAF2] text-gray-500 rounded-xl
                               font-medium text-[14px] text-center no-underline hover:bg-[#F0F4F8] transition-all">Cancel</a>
                </div>
            </div>
        </div>
    </form>

@endsection
