@extends('admin.layouts.app')

@section('content')
    <div class="card" style="padding: 1.5rem;">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 style="font-size: 1.75rem; font-weight: 700; margin-bottom: 0.3rem;">Manage Categories</h1>
                <p style="color: #6b7280; margin: 0;">Create, edit and delete income/expense categories.</p>
            </div>
            <a href="{{ route('admin.categories.create') }}" class="btn btn-primary" style="background: #1B3A6B; border-color: #1B3A6B;">+ New Category</a>
        </div>

        <div class="table-responsive" style="background: #fff; border:1px solid #e5e7eb; border-radius: 0.75rem;">
            <table class="table table-hover mb-0" style="width:100%;">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Icon</th>
                        <th>Active</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $category)
                        <tr>
                            <td>{{ $category->id }}</td>
                            <td>{{ $category->name }}</td>
                            <td>{{ ucfirst($category->type) }}</td>
                            <td>{{ $category->icon ?? '-' }}</td>
                            <td>{{ $category->is_active ? 'Yes' : 'No' }}</td>
                            <td>
                                <a href="{{ route('admin.categories.edit', $category->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                <form method="POST" action="{{ route('admin.categories.destroy', $category->id) }}" style="display:inline">@csrf @method('DELETE')<button class="btn btn-sm btn-danger">Delete</button></form>
                                <form method="POST" action="{{ route('admin.categories.toggle', $category->id) }}" style="display:inline">@csrf <button class="btn btn-sm btn-secondary">{{ $category->is_active ? 'Deactivate' : 'Activate' }}</button></form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center">No categories yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">{{ $categories->links() }}</div>
    </div>
@endsection