@extends('admin.layouts.app')

@section('title', 'Edit User')
@section('breadcrumb', 'Users / Edit')

@section('content')

    {{-- Header --}}
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('admin.users.show', $user->id) }}"
            class="w-9 h-9 bg-white border border-[#E5EAF2] rounded-lg flex items-center justify-center
              text-gray-500 no-underline hover:bg-[#F0F4F8] transition-all shrink-0">←</a>
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-navy tracking-tight">Edit User</h1>
            <p class="text-sm text-gray-500 mt-1">{{ $user->name }} · #{{ $user->id }}</p>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.users.update', $user->id) }}" enctype="multipart/form-data">
        @csrf @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

            {{-- LEFT: Main Form --}}
            <div class="lg:col-span-2 flex flex-col gap-5">

                {{-- Basic Info --}}
                <div class="bg-white border border-[#E5EAF2] rounded-2xl overflow-hidden">
                    <div class="px-5 py-4 border-b border-[#E5EAF2]">
                        <h3 class="text-sm font-semibold text-navy">Basic Information</h3>
                    </div>
                    <div class="p-5 grid grid-cols-1 sm:grid-cols-2 gap-4">

                        {{-- Name --}}
                        <div>
                            <label class="block text-[13px] font-semibold text-navy mb-2">
                                Full Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}"
                                placeholder="Enter full name"
                                class="w-full px-4 py-2.5 border border-[#E5EAF2] rounded-xl text-[13.5px]
                                      font-[Sora] text-navy outline-none transition-all bg-[#F8FAFC]
                                      focus:border-navy focus:bg-white
                                      @error('name') border-red-400 @enderror">
                            @error('name')
                                <p class="text-red-500 text-[11px] mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Mobile --}}
                        <div>
                            <label class="block text-[13px] font-semibold text-navy mb-2">
                                Mobile Number <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="mobile" value="{{ old('mobile', $user->mobile) }}"
                                placeholder="10 digit mobile number" maxlength="10"
                                class="w-full px-4 py-2.5 border border-[#E5EAF2] rounded-xl text-[13.5px]
                                      font-mono text-navy outline-none transition-all bg-[#F8FAFC]
                                      focus:border-navy focus:bg-white
                                      @error('mobile') border-red-400 @enderror">
                            @error('mobile')
                                <p class="text-red-500 text-[11px] mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Gender --}}
                        <div>
                            <label class="block text-[13px] font-semibold text-navy mb-2">Gender</label>
                            <select name="gender"
                                class="w-full px-4 py-2.5 border border-[#E5EAF2] rounded-xl text-[13.5px]
                                       font-[Sora] text-navy outline-none transition-all bg-[#F8FAFC]
                                       focus:border-navy focus:bg-white">
                                <option value="">— Select Gender —</option>
                                <option value="male" {{ old('gender', $user->gender) === 'male' ? 'selected' : '' }}>
                                    Male</option>
                                <option value="female" {{ old('gender', $user->gender) === 'female' ? 'selected' : '' }}>
                                    Female</option>
                                <option value="other" {{ old('gender', $user->gender) === 'other' ? 'selected' : '' }}>
                                    Other</option>
                            </select>
                        </div>

                        {{-- Reminder Time --}}
                        <input type="time" name="reminder_time"
                            value="{{ old('reminder_time', substr($user->reminder_time ?? '', 0, 5)) }}"
                            class="w-full px-4 py-2.5 border border-[#E5EAF2] rounded-xl text-[13.5px]
          font-mono text-navy outline-none transition-all bg-[#F8FAFC]
          focus:border-navy focus:bg-white">

                    </div>
                </div>

                {{-- User Roles --}}
                <div class="bg-white border border-[#E5EAF2] rounded-2xl overflow-hidden">
                    <div class="px-5 py-4 border-b border-[#E5EAF2]">
                        <h3 class="text-sm font-semibold text-navy">User Roles</h3>
                        <p class="text-[11px] text-gray-400 mt-1">Select one or more roles</p>
                    </div>
                    <div class="p-5 grid grid-cols-2 sm:grid-cols-3 gap-3">
                        @foreach ([['value' => 'employee', 'label' => '👔 Employee'], ['value' => 'farmer', 'label' => '🌾 Farmer'], ['value' => 'shopkeeper', 'label' => '🏪 Shopkeeper'], ['value' => 'proprietor', 'label' => '💼 Proprietor'], ['value' => 'business_owner', 'label' => '🏢 Business Owner'], ['value' => 'transporter', 'label' => '🚛 Transporter']] as $role)
                            @php
                                $checked = in_array($role['value'], old('user_types', $user->user_types ?? []));
                            @endphp
                            <label
                                class="flex items-center gap-3 p-3 border rounded-xl cursor-pointer transition-all
                                  hover:border-navy hover:bg-[#EEF2FA]
                                  {{ $checked ? 'border-navy bg-[#EEF2FA]' : 'border-[#E5EAF2]' }}">
                                <input type="checkbox" name="user_types[]" value="{{ $role['value'] }}"
                                    {{ $checked ? 'checked' : '' }} class="accent-navy w-4 h-4 shrink-0">
                                <span class="text-[13px] font-medium text-navy">{{ $role['label'] }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

            </div>

            {{-- RIGHT --}}
            <div class="flex flex-col gap-5">

                {{-- Profile Photo --}}
                <div class="bg-white border border-[#E5EAF2] rounded-2xl overflow-hidden">
                    <div class="px-5 py-4 border-b border-[#E5EAF2]">
                        <h3 class="text-sm font-semibold text-navy">Profile Photo</h3>
                    </div>
                    <div class="p-5 flex flex-col items-center gap-4">
                        <div id="photo-preview"
                            class="w-24 h-24 rounded-full overflow-hidden border-4
                                                    border-[#EEF2FA] bg-[#EEF2FA] flex items-center
                                                    justify-center text-3xl">
                            @if ($user->profile_photo)
                                <img src="{{ asset('storage/' . $user->profile_photo) }}"
                                    class="w-full h-full object-cover" alt="">
                            @else
                                👤
                            @endif
                        </div>
                        <label class="cursor-pointer">
                            <span
                                class="px-4 py-2 bg-[#EEF2FA] border border-[#C7D4EA] rounded-lg
                                     text-[13px] font-medium text-navy hover:bg-[#dde6f5] transition-all">
                                Change Photo
                            </span>
                            <input type="file" name="profile_photo" accept="image/*" class="hidden"
                                onchange="previewPhoto(this)">
                        </label>
                        <p class="text-[11px] text-gray-400 text-center">JPG, PNG max 2MB</p>
                    </div>
                </div>

                {{-- Status --}}
                <div class="bg-white border border-[#E5EAF2] rounded-2xl overflow-hidden">
                    <div class="px-5 py-4 border-b border-[#E5EAF2]">
                        <h3 class="text-sm font-semibold text-navy">Account Status</h3>
                    </div>
                    <div class="p-5">
                        <label class="flex items-center justify-between cursor-pointer">
                            <div>
                                <div class="text-[13px] font-semibold text-navy">Active Account</div>
                                <div class="text-[11px] text-gray-400 mt-1">User can login to the app</div>
                            </div>
                            <div class="relative">
                                <input type="checkbox" name="is_active" value="1" id="is_active"
                                    {{ old('is_active', $user->is_active) ? 'checked' : '' }} class="peer sr-only">
                                <div
                                    class="w-11 h-6 bg-gray-200 rounded-full peer
                                        peer-checked:bg-navy transition-colors">
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
                        Save Changes
                    </button>
                    <a href="{{ route('admin.users.show', $user->id) }}"
                        class="w-full py-3 bg-white border border-[#E5EAF2] text-gray-500 rounded-xl
                          font-medium text-[14px] text-center no-underline hover:bg-[#F0F4F8] transition-all">
                        Cancel
                    </a>
                </div>

            </div>
        </div>
    </form>

@endsection

@push('scripts')
    <script>
        function previewPhoto(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = e => {
                    const preview = document.getElementById('photo-preview');
                    preview.innerHTML = `<img src="${e.target.result}"
                class="w-full h-full object-cover">`;
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endpush
