@extends('admin.layouts.app')

@section('content')
    <div class="card" style="padding: 1.5rem;">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 style="font-size: 1.75rem; font-weight: 700; margin-bottom: 0.3rem;">Manage Transactions</h1>
                <p style="color: #6b7280; margin: 0;">View, edit, and delete all transactions.</p>
            </div>
            <a href="{{ route('admin.transactions.create') }}" class="btn btn-primary" style="background: #1B3A6B; border-color: #1B3A6B;">+ New Transaction</a>
        </div>

        @if ($message = Session::get('success'))
            <div class="alert alert-success" role="alert">{{ $message }}</div>
        @endif

        <div class="table-responsive" style="background: #fff; border:1px solid #e5e7eb; border-radius: 0.75rem;">
            <table class="table table-hover mb-0" style="width:100%;">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>User</th>
                        <th>Type</th>
                        <th>Source</th>
                        <th>Amount</th>
                        <th>Category</th>
                        <th>Business</th>
                        <th>Date</th>
                        <th>Categorized</th>
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
                            <td>{{ $transaction->category->name ?? '-' }}</td>
                            <td>{{ $transaction->business->name ?? '-' }}</td>
                            <td>{{ $transaction->transaction_date->format('d M Y') }}</td>
                            <td>{{ $transaction->is_categorized ? 'Yes' : 'No' }}</td>
                            <td>
                                <a href="{{ route('admin.transactions.show', $transaction->id) }}" class="btn btn-sm btn-info">View</a>
                                <a href="{{ route('admin.transactions.edit', $transaction->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                <form method="POST" action="{{ route('admin.transactions.destroy', $transaction->id) }}" style="display:inline; margin-left:2px;">
                                    @csrf 
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger" onclick="return confirm('Sure?');">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="10" class="text-center">No transactions found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">{{ $transactions->links() }}</div>
    </div>
@endsection