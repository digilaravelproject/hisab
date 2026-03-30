@extends('admin.layouts.app')

@section('title', 'Create Static Page')
@section('breadcrumb', 'Create Static Page')

@section('content')

    <div class="bg-white rounded-2xl border border-[#E5EAF2] overflow-hidden">

        {{-- Card Header --}}
        <div class="flex items-center gap-3 px-6 py-4 border-b border-[#E5EAF2]">
            <div class="w-8 h-8 bg-navy-xlight rounded-lg flex items-center justify-center text-[15px]">📄</div>
            <div>
                <p class="text-[15px] font-semibold text-navy m-0">Create Static Page</p>
                <p class="text-[12px] text-gray-400 m-0">Add a new public page to your site</p>
            </div>
        </div>

        {{-- Form --}}
        <form method="POST" action="{{ route('admin.static-pages.store') }}">
            @csrf

            <div class="p-6 flex flex-col gap-5">

                {{-- Slug + Active + Sort Order (3 col) --}}
                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <label
                            class="block text-[11px] font-semibold text-gray-400 uppercase tracking-wider mb-1.5">Slug</label>
                        <select name="slug" required
                            class="w-full px-3 py-2 border border-[#E5EAF2] rounded-lg text-[13px] text-navy font-[Sora] outline-none focus:border-navy transition-all">
                            <option value="privacy-policy">Privacy Policy</option>
                            <option value="terms-and-conditions">Terms and Conditions</option>
                            <option value="faq">FAQ</option>
                        </select>
                    </div>
                    <div>
                        <label
                            class="block text-[11px] font-semibold text-gray-400 uppercase tracking-wider mb-1.5">Active</label>
                        <select name="is_active" required
                            class="w-full px-3 py-2 border border-[#E5EAF2] rounded-lg text-[13px] text-navy font-[Sora] outline-none focus:border-navy transition-all">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[11px] font-semibold text-gray-400 uppercase tracking-wider mb-1.5">Sort
                            Order</label>
                        <input type="number" name="sort_order" value="0"
                            class="w-full px-3 py-2 border border-[#E5EAF2] rounded-lg text-[13px] text-navy font-[Sora] outline-none focus:border-navy transition-all">
                    </div>
                </div>

                {{-- Title --}}
                <div>
                    <label
                        class="block text-[11px] font-semibold text-gray-400 uppercase tracking-wider mb-1.5">Title</label>
                    <input type="text" name="title" required placeholder="e.g. Privacy Policy"
                        class="w-full px-3 py-2 border border-[#E5EAF2] rounded-lg text-[13px] text-navy font-[Sora] outline-none focus:border-navy transition-all">
                </div>

                {{-- Content --}}
                <div>
                    <label
                        class="block text-[11px] font-semibold text-gray-400 uppercase tracking-wider mb-1.5">Content</label>
                    <textarea name="content" rows="10" required placeholder="Write your page content here..."
                        class="w-full px-3 py-2 border border-[#E5EAF2] rounded-lg text-[13px] text-navy font-[Sora] outline-none focus:border-navy transition-all resize-y"></textarea>
                </div>

                {{-- Divider --}}
                <div class="border-t border-[#E5EAF2]"></div>

                {{-- Actions --}}
                <div class="flex items-center gap-3">
                    <button type="submit"
                        class="inline-flex items-center px-5 py-2 rounded-lg text-[13px] font-semibold bg-navy text-white hover:bg-navy-light transition-all cursor-pointer">
                        Create Page
                    </button>
                    <a href="{{ route('admin.static-pages.index') }}"
                        class="inline-flex items-center px-4 py-2 rounded-lg text-[13px] font-semibold text-gray-500 border border-[#E5EAF2] hover:bg-[#F0F4F8] transition-all no-underline">
                        Cancel
                    </a>
                </div>

            </div>
        </form>
    </div>

@endsection
