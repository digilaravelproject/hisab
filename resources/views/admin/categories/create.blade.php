@extends('admin.layouts.app')

@section('content')
    <div class="card" style="padding: 1.5rem; max-width: 700px; margin: auto;">
        <h1 style="font-size: 1.75rem; font-weight: 700; margin-bottom: 1rem;">Create Category</h1>
        <form method="POST" action="{{ route('admin.categories.store') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label">Name</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Type</label>
                <select name="type" class="form-control" required>
                    <option value="income">Income</option>
                    <option value="expense" selected>Expense</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Icon</label>
                <input type="text" name="icon" class="form-control" placeholder="e.g. 💰">
            </div>
            <div class="mb-3">
                <label class="form-label">Active</label>
                <select name="is_active" class="form-control" required>
                    <option value="1">Yes</option>
                    <option value="0">No</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Save Category</button>
            <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
@endsection