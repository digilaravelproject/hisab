@extends('admin.layouts.app')

@section('content')
    <div class="card" style="padding: 1.5rem;">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 style="font-size: 1.75rem; font-weight: 700; margin-bottom: 0.3rem;">Uncategorized Transactions</h1>
                <p style="color: #6b7280; margin: 0;">Transactions without category.</p>
            </div>
            <a href="{{ route('admin.transactions.index') }}" class="btn btn-secondary">← Back</a>
        </div>

        <div class="table-responsive" style="background: #fff; border:1px solid #e5e7eb; border-radius: 0.75rem;">
            <table class="table table-hover mb-0" style="width:100%;">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>User</th>
                        <th>Type</th>
                        <th>Source</th>
                        <th>Amount</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $transaction)
                        <tr>
                            <td>#{{ $transaction->id }}</td>
                            <td>{{ $transaction->user->name ?? '-' }}</td>
                            <td>
                                <span class="badge" style="background: {{ $transaction->type === 'credit' ? '#16A34A' : '#DC2626' }}; color: white;">
                                    {{ ucfirst($transaction->type) }}
                                </span>
                            </td>
                            <td>{{ ucfirst($transaction->source) }}</td>
                            <td>{{ number_format((float)$transaction->amount, 2) }}</td>
                            <td>{{ $transaction->transaction_date->format('d M Y') }}</td>
                            <td>
                                <a href="{{ route('admin.transactions.edit', $transaction->id) }}" class="btn btn-sm btn-warning">Categorize</a>
                                <a href="{{ route('admin.transactions.show', $transaction->id) }}" class="btn btn-sm btn-info">View</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center">No uncategorized transactions.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">{{ $transactions->links() }}</div>
    </div>
@endsection