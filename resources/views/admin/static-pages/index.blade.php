@extends('admin.layouts.app')

@section('title', 'Static Pages')
@section('breadcrumb', 'Static Pages')

@section('content')
    <div class="bg-white rounded-2xl border border-[#E5EAF2] p-6">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-[22px] font-bold text-navy mb-1">Static Pages</h1>
                <p class="text-[13px] text-gray-500 m-0">Create, edit and publish your public site policies and FAQ content.
                </p>
            </div>
            <a href="{{ route('admin.static-pages.create') }}"
                class="inline-flex items-center px-4 py-2 rounded-lg text-[13px] font-semibold bg-navy text-white hover:bg-navy-light transition-all no-underline">
                + Static Page
            </a>
        </div>

        <div class="overflow-x-auto bg-white rounded-xl border border-[#E5EAF2]">
            <table class="data-table w-full mb-0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Slug</th>
                        <th>Title</th>
                        <th>Active</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pages as $page)
                        <tr>
                            <td>{{ $page->id }}</td>
                            <td>{{ $page->slug }}</td>
                            <td>{{ $page->title }}</td>
                            <td>{{ $page->is_active ? 'Yes' : 'No' }}</td>
                            <td>
                                <a href="{{ route('admin.static-pages.edit', $page->id) }}"
                                    class="inline-flex items-center px-3 py-1.5 rounded-lg text-[12px] font-semibold bg-amber-50 text-amber-600 border border-amber-200 hover:bg-amber-500 hover:text-white transition-all no-underline">
                                    Edit
                                </a>
                                <form method="POST" action="{{ route('admin.static-pages.destroy', $page->id) }}"
                                    style="display:inline">
                                    @csrf @method('DELETE')
                                    <button
                                        class="inline-flex items-center px-3 py-1.5 rounded-lg text-[12px] font-semibold bg-red-50 text-red-600 border border-red-200 hover:bg-red-500 hover:text-white transition-all cursor-pointer">
                                        Delete
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('admin.static-pages.toggle', $page->id) }}"
                                    style="display:inline">
                                    @csrf
                                    <button type="submit"
                                        class="inline-flex items-center px-3 py-1.5 rounded-lg text-[12px] font-semibold bg-navy-xlight text-navy border border-[#D0DAF0] hover:bg-navy hover:text-white transition-all cursor-pointer">
                                        {{ $page->is_active ? 'Deactivate' : 'Activate' }}
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
