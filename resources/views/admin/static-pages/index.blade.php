@extends('admin.layouts.app')

@section('content')
    <div class="card" style="padding: 1.5rem;">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 style="font-size: 1.75rem; font-weight: 700; margin-bottom: 0.3rem;">Static Pages</h1>
                <p style="color: #6b7280; margin: 0;">Create, edit and publish your public site policies and FAQ content.</p>
            </div>
            <a href="{{ route('admin.static-pages.create') }}" class="btn btn-primary" style="background: #1B3A6B; border-color: #1B3A6B;">+ New Static Page</a>
        </div>

        <div class="table-responsive" style="background: #fff; border-radius: 0.75rem; border: 1px solid #e5e7eb;">
            <table class="table mb-0" style="width:100%;">            <thead>
                <tr>
                    <th>ID</th>
                    <th>Slug</th>
                    <th>Title</th>
                    <th>Active</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pages as $page)
                    <tr>
                        <td>{{ $page->id }}</td>
                        <td>{{ $page->slug }}</td>
                        <td>{{ $page->title }}</td>
                        <td>{{ $page->is_active ? 'Yes' : 'No' }}</td>
                        <td>
                            <a href="{{ route('admin.static-pages.edit', $page->id) }}" class="btn btn-sm btn-warning">Edit</a>
                            <form method="POST" action="{{ route('admin.static-pages.destroy', $page->id) }}" style="display:inline">@csrf @method('DELETE')<button class="btn btn-sm btn-danger">Delete</button></form>
                            <form method="POST" action="{{ route('admin.static-pages.toggle', $page->id) }}" style="display:inline">@csrf<button type="submit" class="btn btn-sm btn-secondary">{{ $page->is_active ? 'Deactivate' : 'Activate' }}</button></form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection