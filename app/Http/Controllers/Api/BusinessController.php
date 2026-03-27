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
     * Fetch all businesses for the authenticated user
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
     * Create a new business
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
            'name.required' => 'Business name is required.',
            'type.required' => 'Business type is required.',
            'type.in'       => 'Type must be one of: farm, shop, transport, store, or other.',
        ]);

        // One user can have only one business
        if (Business::where('user_id', $request->user()->id)->exists()) {
            return $this->errorResponse(
                'Your business already exists. Only one business is allowed per user.',
                null,
                422
            );
        }

        $business = Business::create([
            'user_id'               => $request->user()->id,
            'name'                  => $validated['name'],
            'type'                  => $validated['type'],
            'standard_income'       => $validated['standard_income']  ?? 0,
            'standard_expense'      => $validated['standard_expense'] ?? 0,
            'auto_tag_transactions' => $validated['auto_tag'] ?? false,
        ]);

        return $this->successResponse(
            $business,
            'Business created successfully.',
            201
        );
    }

    /**
     * GET /api/v1/businesses/{id}
     * Get single business details
     */
    public function show(Request $request, $id)
    {
        $business = Business::where('user_id', $request->user()->id)
            ->findOrFail($id);

        // Current month summary
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
                'income_variance'  => (float) ($monthlyCredit - $business->standard_income),
                'expense_variance' => (float) ($monthlyDebit  - $business->standard_expense),
            ],
        ], 'Business details fetched successfully.');
    }

    /**
     * PUT /api/v1/businesses/{id}
     * Update business details
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

        return $this->successResponse(
            $business->fresh(),
            'Business updated successfully.'
        );
    }

    /**
     * DELETE /api/v1/businesses/{id}
     * Delete a business
     */
    public function destroy(Request $request, $id)
    {
        $business = Business::where('user_id', $request->user()->id)
            ->findOrFail($id);

        $business->delete();

        return $this->successResponse(
            null,
            'Business deleted successfully.'
        );
    }
}