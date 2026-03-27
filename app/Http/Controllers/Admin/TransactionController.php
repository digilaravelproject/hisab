<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Category;
use App\Models\Business;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::with(['user', 'category', 'business', 'bankAccount'])
            ->latest('transaction_date')
            ->paginate(20);

        return view('admin.transactions.index', compact('transactions'));
    }

    public function create()
    {
        $users = User::select('id', 'name', 'mobile')->get();
        $categories = Category::where('is_active', true)->get();
        $businesses = Business::where('active', true)->get();
        $sources = ['bank', 'upi', 'cash'];
        $types = ['credit', 'debit'];

        return view('admin.transactions.create', compact('users', 'categories', 'businesses', 'sources', 'types'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id'          => 'required|exists:users,id',
            'type'             => 'required|in:credit,debit',
            'source'           => 'required|in:bank,upi,cash',
            'amount'           => 'required|numeric|min:0.01',
            'transaction_date' => 'required|date',
            'category_id'      => 'nullable|exists:categories,id',
            'business_id'      => 'nullable|exists:businesses,id',
            'bank_account_id'  => 'nullable|exists:bank_accounts,id',
            'reference_no'     => 'nullable|string|max:100',
            'description'      => 'nullable|string|max:500',
        ]);

        Transaction::create([
            'user_id'          => $validated['user_id'],
            'type'             => $validated['type'],
            'source'           => $validated['source'],
            'amount'           => $validated['amount'],
            'transaction_date' => $validated['transaction_date'],
            'category_id'      => $validated['category_id'],
            'business_id'      => $validated['business_id'],
            'bank_account_id'  => $validated['bank_account_id'],
            'reference_no'     => $validated['reference_no'],
            'description'      => $validated['description'],
            'is_categorized'   => ! is_null($validated['category_id']),
        ]);

        return redirect()->route('admin.transactions.index')->with('success', 'Transaction created successfully.');
    }

    public function show($id)
    {
        $transaction = Transaction::with(['user', 'category', 'business', 'bankAccount'])->findOrFail($id);
        return view('admin.transactions.show', compact('transaction'));
    }

    public function edit($id)
    {
        $transaction = Transaction::findOrFail($id);
        $users = User::select('id', 'name', 'mobile')->get();
        $categories = Category::where('is_active', true)->get();
        $businesses = Business::where('active', true)->get();
        $sources = ['bank', 'upi', 'cash'];
        $types = ['credit', 'debit'];

        return view('admin.transactions.edit', compact('transaction', 'users', 'categories', 'businesses', 'sources', 'types'));
    }

    public function update(Request $request, $id)
    {
        $transaction = Transaction::findOrFail($id);

        $validated = $request->validate([
            'type'             => 'required|in:credit,debit',
            'source'           => 'required|in:bank,upi,cash',
            'amount'           => 'required|numeric|min:0.01',
            'transaction_date' => 'required|date',
            'category_id'      => 'nullable|exists:categories,id',
            'business_id'      => 'nullable|exists:businesses,id',
            'bank_account_id'  => 'nullable|exists:bank_accounts,id',
            'reference_no'     => 'nullable|string|max:100',
            'description'      => 'nullable|string|max:500',
        ]);

        $transaction->update([
            'type'             => $validated['type'],
            'source'           => $validated['source'],
            'amount'           => $validated['amount'],
            'transaction_date' => $validated['transaction_date'],
            'category_id'      => $validated['category_id'],
            'business_id'      => $validated['business_id'],
            'bank_account_id'  => $validated['bank_account_id'],
            'reference_no'     => $validated['reference_no'],
            'description'      => $validated['description'],
            'is_categorized'   => ! is_null($validated['category_id']),
        ]);

        return redirect()->route('admin.transactions.index')->with('success', 'Transaction updated successfully.');
    }

    public function destroy($id)
    {
        $transaction = Transaction::findOrFail($id);
        $transaction->delete();

        return redirect()->route('admin.transactions.index')->with('success', 'Transaction deleted successfully.');
    }

    public function uncategorized()
    {
        $transactions = Transaction::uncategorized()
            ->with(['user', 'category', 'business'])
            ->latest('transaction_date')
            ->paginate(20);

        return view('admin.transactions.uncategorized', compact('transactions'));
    }
}
