@extends('admin.layouts.app')

@section('content')
    <div class="card" style="padding: 1.5rem;">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 style="font-size: 1.75rem; font-weight: 700; margin-bottom: 0.3rem;">Contact Enquiries</h1>
                <p style="color: #6b7280; margin: 0;">View, resolve and manage user contact messages from app users.</p>
            </div>
        </div>

        <div class="table-responsive" style="background: #fff; border-radius: 0.75rem; border: 1px solid #e5e7eb;">
            <table class="table mb-0" style="width:100%;">            <thead>
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
                @foreach($queries as $q)
                    <tr>
                        <td>{{ $q->id }}</td>
                        <td>{{ $q->name }}</td>
                        <td>{{ $q->email }}</td>
                        <td>{{ $q->subject }}</td>
                        <td>{{ $q->is_resolved ? 'Resolved' : 'Open' }}</td>
                        <td>{{ $q->created_at->format('d M Y H:i') }}</td>
                        <td>
                            <a href="{{ route('admin.contact-queries.show', $q->id) }}" class="btn btn-sm btn-info">View</a>
                            <form method="POST" action="{{ route('admin.contact-queries.destroy', $q->id) }}" style="display:inline">@csrf @method('DELETE')<button class="btn btn-sm btn-danger">Delete</button></form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection