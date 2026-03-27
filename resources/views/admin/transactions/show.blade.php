@extends('admin.layouts.app')

@section('content')
    <div class="card" style="padding: 1.5rem; max-width: 700px; margin: auto;">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 style="font-size: 1.75rem; font-weight: 700;">Transaction #{{ $transaction->id }}</h1>
            <div>
                <a href="{{ route('admin.transactions.edit', $transaction->id) }}" class="btn btn-warning btn-sm">Edit</a>
                <a href="{{ route('admin.transactions.index') }}" class="btn btn-secondary btn-sm">Back</a>
            </div>
        </div>

        <div style="background: #f8fafc; padding: 1.5rem; border-radius: 0.75rem; margin-bottom: 1.5rem;">
            <div class="row mb-3">
                <div class="col-md-6">
                    <p style="color: #6b7280; margin: 0; font-size: 12px;">User</p>
                    <p style="font-weight: 600; margin: 0;">{{ $transaction->user->name ?? '-' }}</p>
                </div>
                <div class="col-md-6">
                    <p style="color: #6b7280; margin: 0; font-size: 12px;">Mobile</p>
                    <p style="font-weight: 600; margin: 0;">{{ $transaction->user->mobile ?? '-' }}</p>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <p style="color: #6b7280; margin: 0; font-size: 12px;">Type</p>
                    <p style="font-weight: 600; margin: 0;">
                        <span class="badge" style="background: {{ $transaction->type === 'credit' ? '#16A34A' : '#DC2626' }}; color: white;">
                            {{ ucfirst($transaction->type) }}
                        </span>
                    </p>
                </div>
                <div class="col-md-6">
                    <p style="color: #6b7280; margin: 0; font-size: 12px;">Source</p>
                    <p style="font-weight: 600; margin: 0;">{{ ucfirst($transaction->source) }}</p>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <p style="color: #6b7280; margin: 0; font-size: 12px;">Amount</p>
                    <p style="font-weight: 600; margin: 0; font-size: 1.25rem;">{{ number_format((float)$transaction->amount, 2) }}</p>
                </div>
                <div class="col-md-6">
                    <p style="color: #6b7280; margin: 0; font-size: 12px;">Transaction Date</p>
                    <p style="font-weight: 600; margin: 0;">{{ $transaction->transaction_date->format('d M Y') }}</p>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <p style="color: #6b7280; margin: 0; font-size: 12px;">Category</p>
                    <p style="font-weight: 600; margin: 0;">{{ $transaction->category->name ?? 'Uncategorized' }}</p>
                </div>
                <div class="col-md-6">
                    <p style="color: #6b7280; margin: 0; font-size: 12px;">Business</p>
                    <p style="font-weight: 600; margin: 0;">{{ $transaction->business->name ?? '-' }}</p>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <p style="color: #6b7280; margin: 0; font-size: 12px;">Reference No</p>
                    <p style="font-weight: 600; margin: 0;">{{ $transaction->reference_no ?? '-' }}</p>
                </div>
                <div class="col-md-6">
                    <p style="color: #6b7280; margin: 0; font-size: 12px;">Categorized</p>
                    <p style="font-weight: 600; margin: 0;">{{ $transaction->is_categorized ? 'Yes' : 'No' }}</p>
                </div>
            </div>

            @if($transaction->description)
                <div class="row">
                    <div class="col-12">
                        <p style="color: #6b7280; margin: 0; font-size: 12px;">Description</p>
                        <p style="font-weight: 600; margin: 0;">{{ $transaction->description }}</p>
                    </div>
                </div>
            @endif
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('admin.transactions.edit', $transaction->id) }}" class="btn btn-warning">Edit</a>
            <form method="POST" action="{{ route('admin.transactions.destroy', $transaction->id) }}" style="display:inline;">
                @csrf
                @method('DELETE')
                <button class="btn btn-danger" onclick="return confirm('Sure?');">Delete</button>
            </form>
        </div>
    </div>
@endsection