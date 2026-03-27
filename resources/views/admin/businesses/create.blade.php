@extends('admin.layouts.app')

@section('content')
    <div class="card" style="padding: 1.5rem; max-width: 700px; margin: auto;">
        <h1 style="font-size: 1.75rem; font-weight: 700; margin-bottom: 1rem;">Create Business</h1>
        <form method="POST" action="{{ route('admin.businesses.store') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label">Owner</label>
                <select name="user_id" class="form-control" required>
                    <option value="">Select user</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->mobile }})</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Business Name</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Type</label>
                <select name="type" class="form-control" required>
                    <option value="farm">Farm</option>
                    <option value="shop">Shop</option>
                    <option value="transport">Transport</option>
                    <option value="store">Store</option>
                    <option value="other" selected>Other</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Standard Income</label>
                <input type="number" step="0.01" name="standard_income" class="form-control" min="0" value="0">
            </div>
            <div class="mb-3">
                <label class="form-label">Standard Expense</label>
                <input type="number" step="0.01" name="standard_expense" class="form-control" min="0" value="0">
            </div>
            <div class="mb-3">
                <label class="form-label">Auto Tag Transactions</label>
                <select name="auto_tag_transactions" class="form-control" required>
                    <option value="1">Yes</option>
                    <option value="0">No</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Active</label>
                <select name="active" class="form-control" required>
                    <option value="1">Yes</option>
                    <option value="0">No</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Save Business</button>
            <a href="{{ route('admin.businesses.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
@endsection