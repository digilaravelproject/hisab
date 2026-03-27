@extends('admin.layouts.app')

@section('content')
    <div class="card" style="padding: 1.5rem; max-width: 700px; margin: auto;">
        <h1 style="font-size: 1.75rem; font-weight: 700; margin-bottom: 1rem;">Edit Business</h1>
        <form method="POST" action="{{ route('admin.businesses.update', $business->id) }}">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label class="form-label">Business Name</label>
                <input type="text" name="name" class="form-control" value="{{ $business->name }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Type</label>
                <select name="type" class="form-control" required>
                    <option value="farm" {{ $business->type === 'farm' ? 'selected' : '' }}>Farm</option>
                    <option value="shop" {{ $business->type === 'shop' ? 'selected' : '' }}>Shop</option>
                    <option value="transport" {{ $business->type === 'transport' ? 'selected' : '' }}>Transport</option>
                    <option value="store" {{ $business->type === 'store' ? 'selected' : '' }}>Store</option>
                    <option value="other" {{ $business->type === 'other' ? 'selected' : '' }}>Other</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Standard Income</label>
                <input type="number" step="0.01" name="standard_income" class="form-control" min="0" value="{{ $business->standard_income }}">
            </div>
            <div class="mb-3">
                <label class="form-label">Standard Expense</label>
                <input type="number" step="0.01" name="standard_expense" class="form-control" min="0" value="{{ $business->standard_expense }}">
            </div>
            <div class="mb-3">
                <label class="form-label">Auto Tag Transactions</label>
                <select name="auto_tag_transactions" class="form-control" required>
                    <option value="1" {{ $business->auto_tag_transactions ? 'selected' : '' }}>Yes</option>
                    <option value="0" {{ ! $business->auto_tag_transactions ? 'selected' : '' }}>No</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Active</label>
                <select name="active" class="form-control" required>
                    <option value="1" {{ $business->active ? 'selected' : '' }}>Yes</option>
                    <option value="0" {{ ! $business->active ? 'selected' : '' }}>No</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Update Business</button>
            <a href="{{ route('admin.businesses.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
@endsection