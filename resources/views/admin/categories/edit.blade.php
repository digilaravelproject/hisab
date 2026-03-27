@extends('admin.layouts.app')

@section('content')
    <div class="card" style="padding: 1.5rem; max-width: 700px; margin: auto;">
        <h1 style="font-size: 1.75rem; font-weight: 700; margin-bottom: 1rem;">Edit Category</h1>
        <form method="POST" action="{{ route('admin.categories.update', $category->id) }}">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label class="form-label">Name</label>
                <input type="text" name="name" class="form-control" value="{{ $category->name }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Type</label>
                <select name="type" class="form-control" required>
                    <option value="income" {{ $category->type === 'income' ? 'selected' : '' }}>Income</option>
                    <option value="expense" {{ $category->type === 'expense' ? 'selected' : '' }}>Expense</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Icon</label>
                <input type="text" name="icon" class="form-control" value="{{ $category->icon }}" placeholder="e.g. 💰">
            </div>
            <div class="mb-3">
                <label class="form-label">Active</label>
                <select name="is_active" class="form-control" required>
                    <option value="1" {{ $category->is_active ? 'selected' : '' }}>Yes</option>
                    <option value="0" {{ ! $category->is_active ? 'selected' : '' }}>No</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Update Category</button>
            <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
@endsection