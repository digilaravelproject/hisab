@extends('admin.layouts.app')

@section('title', 'Contact Enquiries')
@section('breadcrumb', 'Contact Enquiries')

@section('content')
    <div class="bg-white rounded-2xl border border-[#E5EAF2] p-6">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-[22px] font-bold text-navy mb-1">Contact Enquiries</h1>
                <p class="text-[13px] text-gray-500 m-0">View, resolve and manage user contact messages from app users.</p>
            </div>
        </div>

        <div class="overflow-x-auto bg-white rounded-xl border border-[#E5EAF2]">
            <table class="data-table w-full mb-0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Subject</th>
                        <th>Status</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($queries as $q)
                        <tr>
                            <td>{{ $q->id }}</td>
                            <td>{{ $q->name }}</td>
                            <td>{{ $q->email }}</td>
                            <td>{{ $q->subject }}</td>
                            <td>{{ $q->is_resolved ? 'Resolved' : 'Open' }}</td>
                            <td>{{ $q->created_at->format('d M Y H:i') }}</td>
                            <td>
                                <a href="{{ route('admin.contact-queries.show', $q->id) }}"
                                    class="inline-flex items-center px-3 py-1.5 rounded-lg text-[12px] font-semibold bg-navy-xlight text-navy border border-[#D0DAF0] hover:bg-navy hover:text-white transition-all no-underline">
                                    View
                                </a>
                                <form method="POST" action="{{ route('admin.contact-queries.destroy', $q->id) }}"
                                    style="display:inline">
                                    @csrf
                                    @method('DELETE')
                                    <button
                                        class="inline-flex items-center px-3 py-1.5 rounded-lg text-[12px] font-semibold bg-red-50 text-red-600 border border-red-200 hover:bg-red-500 hover:text-white transition-all cursor-pointer">
                                        Delete
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
