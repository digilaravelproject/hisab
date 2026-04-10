@extends('admin.layouts.app')

@section('content')
    <div class="min-h-screen bg-gray-50 py-8 px-4">
        <div class="max-w-3xl mx-auto">

            {{-- Header --}}
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-gray-900">Create Transaction</h1>
                <p class="text-sm text-gray-500 mt-1">Fill in the details below to record a new transaction.</p>
            </div>

            {{-- Error Messages --}}
            @if ($errors->any())
                <div class="mb-5 bg-red-50 border border-red-200 text-red-700 rounded-xl px-5 py-4">
                    <div class="flex items-center gap-2 mb-1 font-semibold text-red-800">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm-.75-11.25a.75.75 0 011.5 0v4.5a.75.75 0 01-1.5 0v-4.5zm.75 7a.75.75 0 100-1.5.75.75 0 000 1.5z"
                                clip-rule="evenodd" />
                        </svg>
                        Please fix the following errors:
                    </div>
                    <ul class="list-disc list-inside text-sm space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Form Card --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200">
                <form method="POST" action="{{ route('admin.transactions.store') }}">
                    @csrf

                    {{-- Section: Basic Info --}}
                    <div class="px-6 pt-6 pb-4 border-b border-gray-100">
                        <h2 class="text-xs font-semibold uppercase tracking-widest text-gray-400 mb-4">Basic Info</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                            {{-- User --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                    User <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <select name="user_id" required
                                        class="w-full appearance-none bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-lg px-4 py-2.5 pr-9 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                                        <option value="">Select user</option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}"
                                                {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                                {{ $user->name }} ({{ $user->mobile }})
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-3 flex items-center">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            {{-- Transaction Type --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Transaction Type <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <select name="type" required
                                        class="w-full appearance-none bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-lg px-4 py-2.5 pr-9 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                                        <option value="">Select type</option>
                                        <option value="credit" {{ old('type') == 'credit' ? 'selected' : '' }}>Credit
                                            (Income)</option>
                                        <option value="debit" {{ old('type') == 'debit' ? 'selected' : '' }}>Debit
                                            (Expense)</option>
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-3 flex items-center">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    {{-- Section: Payment Details --}}
                    <div class="px-6 py-4 border-b border-gray-100">
                        <h2 class="text-xs font-semibold uppercase tracking-widest text-gray-400 mb-4">Payment Details</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                            {{-- Source --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Source <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <select name="source" required
                                        class="w-full appearance-none bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-lg px-4 py-2.5 pr-9 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                                        <option value="">Select source</option>
                                        <option value="cash" {{ old('source') == 'cash' ? 'selected' : '' }}>Cash</option>
                                        <option value="bank" {{ old('source') == 'bank' ? 'selected' : '' }}>Bank Transfer
                                        </option>
                                        <option value="upi" {{ old('source') == 'upi' ? 'selected' : '' }}>UPI</option>
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-3 flex items-center">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            {{-- Amount --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Amount <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <span
                                        class="absolute inset-y-0 left-3 flex items-center text-gray-400 text-sm font-medium">₹</span>
                                    <input type="number" step="0.01" name="amount" min="0.01" required
                                        value="{{ old('amount') }}" placeholder="0.00"
                                        class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-lg pl-7 pr-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                                </div>
                            </div>

                            {{-- Transaction Date --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Transaction Date <span class="text-red-500">*</span>
                                </label>
                                <input type="date" name="transaction_date" required
                                    value="{{ old('transaction_date', date('Y-m-d')) }}"
                                    class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                            </div>

                            {{-- Reference No --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Reference No
                                    <span class="text-gray-400 font-normal text-xs">(UPI / Bank)</span>
                                </label>
                                <input type="text" name="reference_no" value="{{ old('reference_no') }}"
                                    placeholder="e.g. UPI-123456"
                                    class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                            </div>

                        </div>
                    </div>

                    {{-- Section: Optional Info --}}
                    <div class="px-6 py-4 border-b border-gray-100">
                        <h2 class="text-xs font-semibold uppercase tracking-widest text-gray-400 mb-4">Optional Info</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                            {{-- Category --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Category</label>
                                <div class="relative">
                                    <select name="category_id"
                                        class="w-full appearance-none bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-lg px-4 py-2.5 pr-9 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                                        <option value="">Optional</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}"
                                                {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-3 flex items-center">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            {{-- Business --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Business</label>
                                <div class="relative">
                                    <select name="business_id"
                                        class="w-full appearance-none bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-lg px-4 py-2.5 pr-9 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                                        <option value="">Optional</option>
                                        @foreach ($businesses as $business)
                                            <option value="{{ $business->id }}"
                                                {{ old('business_id') == $business->id ? 'selected' : '' }}>
                                                {{ $business->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-3 flex items-center">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </div>
                                </div>
                            </div>

                        </div>

                        {{-- Description --}}
                        <div class="mt-5">
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Description</label>
                            <textarea name="description" rows="3" placeholder="Optional notes..."
                                class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition resize-none">{{ old('description') }}</textarea>
                        </div>
                    </div>

                    {{-- Footer Buttons --}}
                    <div class="px-6 py-4 flex items-center justify-end gap-3">
                        <a href="{{ route('admin.transactions.index') }}"
                            class="inline-flex items-center gap-1.5 px-5 py-2.5 rounded-lg text-sm font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 19l-7-7 7-7" />
                            </svg>
                            Cancel
                        </a>
                        <button type="submit"
                            class="inline-flex items-center gap-1.5 px-5 py-2.5 rounded-lg text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 shadow-sm transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            Create Transaction
                        </button>
                    </div>

                </form>
            </div>

        </div>
    </div>
@endsection
