<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Services\TransactionService;
use App\Traits\ApiResponseTrait;
use App\Http\Resources\TransactionResource;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    use ApiResponseTrait;

    public function __construct(private TransactionService $transactionService) {}

    /**
     * GET /api/v1/transactions
     * Filters: type, source, business_id, category_id, from_date, to_date, uncategorized, per_page
     */
    public function index(Request $request)
    {
        $transactions = Transaction::query()
            ->where('user_id', $request->user()->id)
            ->when($request->type, fn($q) => $q->where('type', $request->type))
            ->when($request->source, fn($q) => $q->where('source', $request->source))
            ->when($request->business_id, fn($q) => $q->where('business_id', $request->business_id))
            ->when($request->category_id, fn($q) => $q->where('category_id', $request->category_id))
            ->when($request->from_date, fn($q) => $q->whereDate('transaction_date', '>=', $request->from_date))
            ->when($request->to_date, fn($q) => $q->whereDate('transaction_date', '<=', $request->to_date))
            ->when($request->uncategorized, fn($q) => $q->uncategorized())
            ->with(['category', 'business', 'bankAccount'])
            ->latest('transaction_date')
            ->paginate($request->per_page ?? 20);

        return $this->paginatedResponse(
            TransactionResource::collection($transactions)->response()->getData(),
        );
    }

    /**
     * GET /api/v1/transactions/{id}
     * Get single transaction by ID
     */
    public function show(Request $request, $id)
    {
        $transaction = Transaction::where('user_id', $request->user()->id)
            ->with(['category', 'business', 'bankAccount'])
            ->findOrFail($id);

        return $this->successResponse(
            new TransactionResource($transaction),
            'Transaction fetched successfully'
        );
    }

    /**
     * GET /api/v1/transactions/search
     * Search transactions by description, reference_no, amount
     */
    public function search(Request $request)
    {
        $query = $request->get('q', '');
        $amount = $request->get('amount');
        $type = $request->get('type');
        $source = $request->get('source');
        $from_date = $request->get('from_date');
        $to_date = $request->get('to_date');

        $transactions = Transaction::query()
            ->where('user_id', $request->user()->id)
            ->when($query, function ($q) use ($query) {
                return $q->where(function ($subQuery) use ($query) {
                    $subQuery->where('description', 'like', "%{$query}%")
                        ->orWhere('reference_no', 'like', "%{$query}%")
                        ->orWhereHas('category', fn($c) => $c->where('name', 'like', "%{$query}%"));
                });
            })
            ->when($amount, fn($q) => $q->where('amount', $amount))
            ->when($type, fn($q) => $q->where('type', $type))
            ->when($source, fn($q) => $q->where('source', $source))
            ->when($from_date, fn($q) => $q->whereDate('transaction_date', '>=', $from_date))
            ->when($to_date, fn($q) => $q->whereDate('transaction_date', '<=', $to_date))
            ->with(['category', 'business', 'bankAccount'])
            ->latest('transaction_date')
            ->paginate($request->per_page ?? 20);

        return $this->paginatedResponse(
            TransactionResource::collection($transactions)->response()->getData(),
            'Search results'
        );
    }

    /**
     * POST /api/v1/transactions
     * Create transaction with different sources: cash, bank, upi
     */
    public function store(Request $request)
    {
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
        ], [
            'source.required' => 'Transaction source (bank/upi/cash) required hai.',
            'source.in'       => 'Source: bank, upi, ya cash mein se hona chahiye.',
        ]);

        $transaction = Transaction::create([
            'user_id'          => $request->user()->id,
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

        return $this->successResponse(
            new TransactionResource($transaction),
            'Transaction added successfully',
            201
        );
    }

    /**
     * PATCH /api/transactions/{id}/categorize
     * Purpose/category assign karna
     */
    public function categorize(Request $request, Transaction $transaction)
    {
        $this->authorize('update', $transaction);

        $request->validate([
            'category_id'  => 'required|exists:categories,id',
            'business_id'  => 'nullable|exists:businesses,id',
        ]);

        $transaction->update([
            'category_id'    => $request->category_id,
            'business_id'    => $request->business_id,
            'is_categorized' => true,
        ]);

        return $this->successResponse(
            new TransactionResource($transaction->fresh(['category', 'business'])),
            'Transaction categorized successfully'
        );
    }

    /**
     * GET /api/transactions/summary
     * Monthly credit/debit summary
     */
    public function summary(Request $request)
    {
        $summary = $this->transactionService->getMonthlySummary(
            $request->user(),
            $request->year ?? now()->year,
            $request->month ?? now()->month
        );

        return $this->successResponse($summary);
    }
}
