<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class BusinessController extends Controller
{
    use ApiResponseTrait;

    /**
     * GET /api/v1/businesses
     * User ke saare businesses
     */
    public function index(Request $request)
    {
        $businesses = Business::where('user_id', $request->user()->id)
            ->withCount('transactions')
            ->withSum(['transactions as total_credit' => function ($q) {
                $q->where('type', 'credit');
            }], 'amount')
            ->withSum(['transactions as total_debit' => function ($q) {
                $q->where('type', 'debit');
            }], 'amount')
            ->latest()
            ->get()
            ->map(fn($b) => [
                'id'                  => $b->id,
                'name'                => $b->name,
                'type'                => $b->type,
                'standard_income'     => (float) $b->standard_income,
                'standard_expense'    => (float) $b->standard_expense,
                'auto_tag'            => $b->auto_tag_transactions,
                'total_transactions'  => $b->transactions_count,
                'total_credit'        => (float) ($b->total_credit ?? 0),
                'total_debit'         => (float) ($b->total_debit ?? 0),
                'net_balance'         => (float) (($b->total_credit ?? 0) - ($b->total_debit ?? 0)),
                'created_at'          => $b->created_at?->format('Y-m-d'),
            ]);

        return $this->successResponse($businesses, 'Businesses fetched successfully.');
    }

    /**
     * POST /api/v1/businesses
     * Naya business create karo
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'              => 'required|string|max:100',
            'type'              => 'required|in:farm,shop,transport,store,other',
            'standard_income'   => 'nullable|numeric|min:0',
            'standard_expense'  => 'nullable|numeric|min:0',
            'auto_tag'          => 'nullable|boolean',
        ], [
            'name.required' => 'Business ka naam required hai.',
            'type.required' => 'Business type required hai.',
            'type.in'       => 'Type: farm, shop, transport, store, other mein se hona chahiye.',
        ]);

        $business = Business::create([
            'user_id'              => $request->user()->id,
            'name'                 => $validated['name'],
            'type'                 => $validated['type'],
            'standard_income'      => $validated['standard_income']  ?? 0,
            'standard_expense'     => $validated['standard_expense'] ?? 0,
            'auto_tag_transactions' => $validated['auto_tag'] ?? false,
        ]);

        return $this->successResponse($business, 'Business create ho gaya.', 201);
    }

    /**
     * GET /api/v1/businesses/{id}
     * Single business detail
     */
    public function show(Request $request, $id)
    {
        $business = Business::where('user_id', $request->user()->id)
            ->findOrFail($id);

        // Is month ki summary
        $monthlyCredit = $business->transactions()
            ->where('type', 'credit')
            ->whereMonth('transaction_date', now()->month)
            ->whereYear('transaction_date', now()->year)
            ->sum('amount');

        $monthlyDebit = $business->transactions()
            ->where('type', 'debit')
            ->whereMonth('transaction_date', now()->month)
            ->whereYear('transaction_date', now()->year)
            ->sum('amount');

        return $this->successResponse([
            'id'               => $business->id,
            'name'             => $business->name,
            'type'             => $business->type,
            'standard_income'  => (float) $business->standard_income,
            'standard_expense' => (float) $business->standard_expense,
            'auto_tag'         => $business->auto_tag_transactions,
            'this_month'       => [
                'credit'   => (float) $monthlyCredit,
                'debit'    => (float) $monthlyDebit,
                'net'      => (float) ($monthlyCredit - $monthlyDebit),
                // Standard se compare
                'income_variance'  => (float) ($monthlyCredit - $business->standard_income),
                'expense_variance' => (float) ($monthlyDebit  - $business->standard_expense),
            ],
        ], 'Business detail fetched.');
    }

    /**
     * PUT /api/v1/businesses/{id}
     * Business update karo
     */
    public function update(Request $request, $id)
    {
        $business = Business::where('user_id', $request->user()->id)
            ->findOrFail($id);

        $validated = $request->validate([
            'name'             => 'sometimes|string|max:100',
            'type'             => 'sometimes|in:farm,shop,transport,store,other',
            'standard_income'  => 'sometimes|numeric|min:0',
            'standard_expense' => 'sometimes|numeric|min:0',
            'auto_tag'         => 'sometimes|boolean',
        ]);

        $business->update([
            'name'                  => $validated['name']             ?? $business->name,
            'type'                  => $validated['type']             ?? $business->type,
            'standard_income'       => $validated['standard_income']  ?? $business->standard_income,
            'standard_expense'      => $validated['standard_expense'] ?? $business->standard_expense,
            'auto_tag_transactions' => $validated['auto_tag']         ?? $business->auto_tag_transactions,
        ]);

        return $this->successResponse($business->fresh(), 'Business update ho gaya.');
    }

    /**
     * DELETE /api/v1/businesses/{id}
     */
    public function destroy(Request $request, $id)
    {
        $business = Business::where('user_id', $request->user()->id)
            ->findOrFail($id);

        $business->delete();

        return $this->successResponse(null, 'Business delete ho gaya.');
    }
}
