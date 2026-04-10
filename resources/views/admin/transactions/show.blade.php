@extends('admin.layouts.app')

@section('content')
    <div class="min-h-screen bg-gray-50 py-8 px-4">
        <div class="max-w-3xl mx-auto">

            {{-- Header --}}
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Transaction #{{ $transaction->id }}</h1>
                    <p class="text-sm text-gray-500 mt-1">Transaction details and summary</p>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('admin.transactions.edit', $transaction->id) }}"
                        class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg text-sm font-medium text-amber-700 bg-amber-100 hover:bg-amber-200 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z" />
                        </svg>
                        Edit
                    </a>
                    <a href="{{ route('admin.transactions.index') }}"
                        class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg text-sm font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                        Back
                    </a>
                </div>
            </div>

            {{-- Detail Card --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 mb-5">

                {{-- Section: User Info --}}
                <div class="px-6 pt-6 pb-4 border-b border-gray-100">
                    <h2 class="text-xs font-semibold uppercase tracking-widest text-gray-400 mb-4">User Info</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <p class="text-xs text-gray-400 mb-1">User</p>
                            <p class="text-sm font-semibold text-gray-900">{{ $transaction->user->name ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 mb-1">Mobile</p>
                            <p class="text-sm font-semibold text-gray-900">{{ $transaction->user->mobile ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                {{-- Section: Transaction Info --}}
                <div class="px-6 py-4 border-b border-gray-100">
                    <h2 class="text-xs font-semibold uppercase tracking-widest text-gray-400 mb-4">Transaction Info</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                        <div>
                            <p class="text-xs text-gray-400 mb-1">Type</p>
                            @if ($transaction->type === 'credit')
                                <span
                                    class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.707a1 1 0 00-1.414-1.414L9 11.172 7.707 9.879a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    Credit (Income)
                                </span>
                            @else
                                <span
                                    class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v3.586L7.707 9.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 10.586V7z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    Debit (Expense)
                                </span>
                            @endif
                        </div>

                        <div>
                            <p class="text-xs text-gray-400 mb-1">Source</p>
                            <p class="text-sm font-semibold text-gray-900">{{ ucfirst($transaction->source) }}</p>
                        </div>

                        <div>
                            <p class="text-xs text-gray-400 mb-1">Amount</p>
                            <p
                                class="text-xl font-bold {{ $transaction->type === 'credit' ? 'text-green-600' : 'text-red-600' }}">
                                ₹{{ number_format((float) $transaction->amount, 2) }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs text-gray-400 mb-1">Transaction Date</p>
                            <p class="text-sm font-semibold text-gray-900">
                                {{ $transaction->transaction_date->format('d M Y') }}</p>
                        </div>

                    </div>
                </div>

                {{-- Section: Optional Info --}}
                <div class="px-6 py-4 border-b border-gray-100">
                    <h2 class="text-xs font-semibold uppercase tracking-widest text-gray-400 mb-4">Optional Info</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                        <div>
                            <p class="text-xs text-gray-400 mb-1">Category</p>
                            <p class="text-sm font-semibold text-gray-900">
                                {{ $transaction->category->name ?? 'Uncategorized' }}</p>
                        </div>

                        <div>
                            <p class="text-xs text-gray-400 mb-1">Business</p>
                            <p class="text-sm font-semibold text-gray-900">{{ $transaction->business->name ?? '-' }}</p>
                        </div>

                        <div>
                            <p class="text-xs text-gray-400 mb-1">Reference No</p>
                            <p class="text-sm font-semibold text-gray-900">{{ $transaction->reference_no ?? '-' }}</p>
                        </div>

                        <div>
                            <p class="text-xs text-gray-400 mb-1">Categorized</p>
                            @if ($transaction->is_categorized)
                                <span
                                    class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">Yes</span>
                            @else
                                <span
                                    class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-500">No</span>
                            @endif
                        </div>

                    </div>

                    @if ($transaction->description)
                        <div class="mt-5">
                            <p class="text-xs text-gray-400 mb-1">Description</p>
                            <p class="text-sm font-semibold text-gray-900">{{ $transaction->description }}</p>
                        </div>
                    @endif
                </div>

                {{-- Footer Actions --}}
                <div class="px-6 py-4 flex items-center gap-3">
                    <a href="{{ route('admin.transactions.edit', $transaction->id) }}"
                        class="inline-flex items-center gap-1.5 px-5 py-2.5 rounded-lg text-sm font-semibold text-amber-700 bg-amber-100 hover:bg-amber-200 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z" />
                        </svg>
                        Edit
                    </a>

                    <form method="POST" action="{{ route('admin.transactions.destroy', $transaction->id) }}"
                        class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" onclick="return confirm('Are you sure you want to delete this transaction?')"
                            class="inline-flex items-center gap-1.5 px-5 py-2.5 rounded-lg text-sm font-semibold text-red-700 bg-red-100 hover:bg-red-200 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m2 0a1 1 0 00-1-1h-4a1 1 0 00-1 1H5" />
                            </svg>
                            Delete
                        </button>
                    </form>
                </div>

            </div>

        </div>
    </div>
@endsection
