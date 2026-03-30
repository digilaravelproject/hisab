@extends('admin.layouts.app')

@section('title', 'Settings')
@section('breadcrumb', 'Settings')

@section('content')

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-navy tracking-tight">User Settings</h1>
            <p class="text-sm text-gray-500 mt-1">View and update settings for any user from the admin panel.</p>
        </div>
    </div>

    @if (session('success'))
        <div class="flex items-center gap-3 px-4 py-3 mb-5 bg-green-50 border border-green-200 text-green-700 text-[13px] font-medium rounded-xl">
            <span>✓</span> {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 xl:grid-cols-4 gap-5 mb-6">
        <div class="xl:col-span-1 bg-white border border-[#E5EAF2] rounded-2xl p-5">
            <h3 class="text-sm font-semibold text-navy mb-4">Filter by User</h3>
            <form action="{{ route('admin.settings') }}" method="GET" class="space-y-4">
                <div>
                    <label class="block text-[13px] font-semibold text-navy mb-2">Select User</label>
                    <select name="user_id" onchange="this.form.submit()"
                        class="w-full px-4 py-2.5 border border-[#E5EAF2] rounded-xl bg-[#F8FAFC] focus:border-navy focus:bg-white">
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ optional($selectedUser)->id == $user->id ? 'selected' : '' }}>{{ $user->name }} ({{ $user->email }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="text-[13px] text-gray-500">Choose a user to load and edit their settings.</div>
            </form>
        </div>

        <div class="xl:col-span-3 bg-white border border-[#E5EAF2] rounded-2xl p-6">
            <h3 class="text-sm font-semibold text-navy mb-4">{{ $selectedUser ? 'Settings for ' . $selectedUser->name : 'Select a user to manage settings' }}</h3>

            @if($selectedUser)
                <form method="POST" action="{{ route('admin.settings.update', $selectedUser->id) }}" class="space-y-6">
                    @csrf

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
                        <div class="bg-[#F8FAFC] border border-[#E5EAF2] rounded-2xl p-5">
                            <label class="flex items-center justify-between gap-3 cursor-pointer">
                                <div>
                                    <p class="text-[13px] font-semibold text-navy">Notifications</p>
                                    <p class="text-[12px] text-gray-500">Allow push notifications for this user.</p>
                                </div>
                                <div class="flex items-center gap-2">
                                    <input type="hidden" name="notifications_enabled" value="0">
                                    <input type="checkbox" name="notifications_enabled" value="1" {{ $settings->notifications_enabled ? 'checked' : '' }} class="h-5 w-5 rounded border-gray-300 text-navy focus:ring-navy">
                                </div>
                            </label>
                        </div>

                        <div class="bg-[#F8FAFC] border border-[#E5EAF2] rounded-2xl p-5">
                            <label class="flex items-center justify-between gap-3 cursor-pointer">
                                <div>
                                    <p class="text-[13px] font-semibold text-navy">Biometric Login</p>
                                    <p class="text-[12px] text-gray-500">Allow fingerprint / face login when available.</p>
                                </div>
                                <div class="flex items-center gap-2">
                                    <input type="hidden" name="biometric_enabled" value="0">
                                    <input type="checkbox" name="biometric_enabled" value="1" {{ $settings->biometric_enabled ? 'checked' : '' }} class="h-5 w-5 rounded border-gray-300 text-navy focus:ring-navy">
                                </div>
                            </label>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-[13px] font-semibold text-navy mb-2">PIN Code</label>
                            <input type="text" name="pin_code" value="{{ old('pin_code', $settings->pin_code) }}"
                                placeholder="Enter PIN code"
                                class="w-full px-4 py-3 border border-[#E5EAF2] rounded-xl bg-[#F8FAFC] focus:border-navy focus:bg-white @error('pin_code') border-red-400 @enderror">
                            @error('pin_code')
                                <p class="text-red-500 text-[11px] mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-[13px] font-semibold text-navy mb-2">Daily Reminder</label>
                            <input type="time" name="daily_reminder_time" value="{{ old('daily_reminder_time', $settings->daily_reminder_time) }}"
                                class="w-full px-4 py-3 border border-[#E5EAF2] rounded-xl bg-[#F8FAFC] focus:border-navy focus:bg-white @error('daily_reminder_time') border-red-400 @enderror">
                            @error('daily_reminder_time')
                                <p class="text-red-500 text-[11px] mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-[13px] font-semibold text-navy mb-2">Weekly Budget Limit</label>
                            <input type="number" name="weekly_budget_limit" step="0.01" min="0" value="{{ old('weekly_budget_limit', $settings->weekly_budget_limit) }}"
                                placeholder="e.g. 15000"
                                class="w-full px-4 py-3 border border-[#E5EAF2] rounded-xl bg-[#F8FAFC] focus:border-navy focus:bg-white @error('weekly_budget_limit') border-red-400 @enderror">
                            @error('weekly_budget_limit')
                                <p class="text-red-500 text-[11px] mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-[13px] font-semibold text-navy mb-2">Monthly Budget Limit</label>
                            <input type="number" name="monthly_budget_limit" step="0.01" min="0" value="{{ old('monthly_budget_limit', $settings->monthly_budget_limit) }}"
                                placeholder="e.g. 60000"
                                class="w-full px-4 py-3 border border-[#E5EAF2] rounded-xl bg-[#F8FAFC] focus:border-navy focus:bg-white @error('monthly_budget_limit') border-red-400 @enderror">
                            @error('monthly_budget_limit')
                                <p class="text-red-500 text-[11px] mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                        <button type="submit"
                            class="inline-flex items-center gap-2 px-5 py-3 bg-navy text-white rounded-xl font-semibold text-[14px] hover:bg-navy-dark transition-all">
                            Save Settings
                        </button>
                        <a href="{{ route('admin.settings', ['user_id' => $selectedUser->id]) }}"
                            class="inline-flex items-center gap-2 px-5 py-3 bg-white text-gray-600 border border-[#E5EAF2] rounded-xl font-medium text-[14px] hover:bg-[#F0F4F8] transition-all">
                            Reset
                        </a>
                    </div>
                </form>
            @else
                <div class="text-[13px] text-gray-500">No users available. Please add users first.</div>
            @endif
        </div>
    </div>

@endsection
