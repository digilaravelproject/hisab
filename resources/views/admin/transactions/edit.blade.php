@extends('admin.layouts.app')

@section('content')
    <div class="card" style="padding: 1.5rem; max-width: 800px; margin: auto;">
        <h1 style="font-size: 1.75rem; font-weight: 700; margin-bottom: 1rem;">Edit Transaction</h1>
        
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.transactions.update', $transaction->id) }}">
            @csrf
            @method('PUT')
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Transaction Type</label>
                    <select name="type" class="form-control" required>
                        <option value="credit" {{ $transaction->type === 'credit' ? 'selected' : '' }}>Credit (Income)</option>
                        <option value="debit" {{ $transaction->type === 'debit' ? 'selected' : '' }}>Debit (Expense)</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Source</label>
                    <select name="source" class="form-control" required>
                        <option value="cash" {{ $transaction->source === 'cash' ? 'selected' : '' }}>Cash</option>
                        <option value="bank" {{ $transaction->source === 'bank' ? 'selected' : '' }}>Bank Transfer</option>
                        <option value="upi" {{ $transaction->source === 'upi' ? 'selected' : '' }}>UPI</option>
                    </select>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Amount</label>
                    <input type="number" step="0.01" name="amount" class="form-control" required min="0.01" value="{{ $transaction->amount }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Transaction Date</label>
                    <input type="date" name="transaction_date" class="form-control" required value="{{ $transaction->transaction_date->format('Y-m-d') }}">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Category</label>
                    <select name="category_id" class="form-control">
                        <option value="">Optional</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ $transaction->category_id === $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Business</label>
                    <select name="business_id" class="form-control">
                        <option value="">Optional</option>
                        @foreach($businesses as $business)
                            <option value="{{ $business->id }}" {{ $transaction->business_id === $business->id ? 'selected' : '' }}>
                                {{ $business->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Reference No</label>
                    <input type="text" name="reference_no" class="form-control" value="{{ $transaction->reference_no ?? '' }}" placeholder="e.g. UPI-123456">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Bank Account</label>
                    <select name="bank_account_id" class="form-control">
                        <option value="">Optional</option>
                        {{-- Will need to load bank accounts here --}}
                    </select>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="3" placeholder="Optional notes">{{ $transaction->description ?? '' }}</textarea>
            </div>

            <button type="submit" class="btn btn-primary">Update Transaction</button>
            <a href="{{ route('admin.transactions.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
@endsection