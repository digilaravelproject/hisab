@extends('admin.layouts.app')

@section('content')
    <div class="card" style="padding: 1.5rem; max-width: 800px; margin: auto;">
        <h1 style="font-size: 1.75rem; font-weight: 700; margin-bottom: 1rem;">Create Transaction</h1>
        
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.transactions.store') }}">
            @csrf
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">User</label>
                    <select name="user_id" class="form-control" required>
                        <option value="">Select user</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->mobile }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Transaction Type</label>
                    <select name="type" class="form-control" required>
                        <option value="">Select type</option>
                        <option value="credit">Credit (Income)</option>
                        <option value="debit">Debit (Expense)</option>
                    </select>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Source</label>
                    <select name="source" class="form-control" required>
                        <option value="">Select source</option>
                        <option value="cash">Cash</option>
                        <option value="bank">Bank Transfer</option>
                        <option value="upi">UPI</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Amount</label>
                    <input type="number" step="0.01" name="amount" class="form-control" required min="0.01">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Transaction Date</label>
                    <input type="date" name="transaction_date" class="form-control" required value="{{ date('Y-m-d') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Category</label>
                    <select name="category_id" class="form-control">
                        <option value="">Optional</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Business</label>
                    <select name="business_id" class="form-control">
                        <option value="">Optional</option>
                        @foreach($businesses as $business)
                            <option value="{{ $business->id }}">{{ $business->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Reference No (UPI/Bank)</label>
                    <input type="text" name="reference_no" class="form-control" placeholder="e.g. UPI-123456">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="3" placeholder="Optional notes"></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Create Transaction</button>
            <a href="{{ route('admin.transactions.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
@endsection