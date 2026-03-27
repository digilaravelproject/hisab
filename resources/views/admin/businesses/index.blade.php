@extends('admin.layouts.app')

@section('content')
    <div class="card" style="padding: 1.5rem;">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 style="font-size: 1.75rem; font-weight: 700; margin-bottom: 0.3rem;">Manage Businesses</h1>
                <p style="color: #6b7280; margin: 0;">Create, edit and delete user businesses.</p>
            </div>
            <a href="{{ route('admin.businesses.create') }}" class="btn btn-primary" style="background: #1B3A6B; border-color: #1B3A6B;">+ New Business</a>
        </div>

        <div class="table-responsive" style="background: #fff; border:1px solid #e5e7eb; border-radius: 0.75rem;">
            <table class="table table-hover mb-0" style="width:100%;">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Owner</th>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Income</th>
                        <th>Expense</th>
                        <th>Auto Tag</th>
                        <th>Active</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($businesses as $business)
                        <tr>
                            <td>{{ $business->id }}</td>
                            <td>{{ $business->user->name ?? '-' }}</td>
                            <td>{{ $business->name }}</td>
                            <td>{{ ucfirst($business->type) }}</td>
                            <td>{{ number_format((float)$business->standard_income, 2) }}</td>
                            <td>{{ number_format((float)$business->standard_expense, 2) }}</td>
                            <td>{{ $business->auto_tag_transactions ? 'Yes' : 'No' }}</td>
                            <td>{{ $business->active ? 'Yes' : 'No' }}</td>
                            <td>
                                <a href="{{ route('admin.businesses.edit', $business->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                <form method="POST" action="{{ route('admin.businesses.destroy', $business->id) }}" style="display:inline; margin-left:2px;">@csrf @method('DELETE')<button class="btn btn-sm btn-danger">Delete</button></form>
                                <form method="POST" action="{{ route('admin.businesses.toggle', $business->id) }}" style="display:inline; margin-left:2px;">@csrf<button class="btn btn-sm btn-secondary">{{ $business->active ? 'Deactivate' : 'Activate' }}</button></form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="9" class="text-center">No businesses yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">{{ $businesses->links() }}</div>
    </div>
@endsection