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
     * GET /api/transactions
     * Filters: type, source, business_id, category_id, from_date, to_date
     */
    public function index(Request $request)
    {
        $transactions = Transaction::query()
            ->where('user_id', $request->user()->id)
            ->when($request->type, fn($q) => $q->where('type', $request->type))
            ->when($request->source, fn($q) => $q->where('source', $request->source))
            ->when($request->business_id, fn($q) => $q->where('business_id', $request->business_id))
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
     * POST /api/transactions
     * Cash manual entry
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'type'             => 'required|in:credit,debit',
            'amount'           => 'required|numeric|min:0.01',
            'transaction_date' => 'required|date',
            'category_id'      => 'nullable|exists:categories,id',
            'business_id'      => 'nullable|exists:businesses,id',
            'description'      => 'nullable|string|max:500',
        ]);

        $transaction = $this->transactionService->createCashTransaction(
            $request->user(),
            $validated
        );

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
