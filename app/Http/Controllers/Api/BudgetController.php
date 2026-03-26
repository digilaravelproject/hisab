<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Budget;
use App\Models\BudgetTarget;
use App\Models\Transaction;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BudgetController extends Controller
{
    use ApiResponseTrait;

    /**
     * GET /api/v1/budgets
     * Is month ke saare budgets with actual spending
     */
    public function index(Request $request)
    {
        $month = $request->month ?? now()->month;
        $year  = $request->year  ?? now()->year;

        $budgets = Budget::where('user_id', $request->user()->id)
            ->where('month', $month)
            ->where('year',  $year)
            ->with('category')
            ->get()
            ->map(function ($budget) use ($request) {
                // Actual spending fetch karo
                $actualSpent = Transaction::where('user_id', $request->user()->id)
                    ->where('type', 'debit')
                    ->where('category_id', $budget->category_id)
                    ->whereMonth('transaction_date', $budget->month)
                    ->whereYear('transaction_date',  $budget->year)
                    ->sum('amount');

                $percentage  = $budget->target_amount > 0
                    ? round(($actualSpent / $budget->target_amount) * 100, 1)
                    : 0;

                return [
                    'id'             => $budget->id,
                    'category'       => [
                        'id'   => $budget->category?->id,
                        'name' => $budget->category?->name,
                    ],
                    'target_amount'  => (float) $budget->target_amount,
                    'actual_spent'   => (float) $actualSpent,
                    'remaining'      => (float) max(0, $budget->target_amount - $actualSpent),
                    'percentage'     => $percentage,
                    'status'         => $percentage >= 100 ? 'exceeded'
                        : ($percentage >= 80  ? 'warning' : 'safe'),
                    'month'          => $budget->month,
                    'year'           => $budget->year,
                ];
            });

        // Total summary
        $totalTarget = $budgets->sum('target_amount');
        $totalSpent  = $budgets->sum('actual_spent');

        return $this->successResponse([
            'budgets' => $budgets,
            'summary' => [
                'total_target' => $totalTarget,
                'total_spent'  => $totalSpent,
                'total_saved'  => max(0, $totalTarget - $totalSpent),
                'month'        => $month,
                'year'         => $year,
            ],
        ], 'Budgets fetched successfully.');
    }

    /**
     * POST /api/v1/budgets
     * Naya budget target set karo
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id'   => 'required|exists:categories,id',
            'target_amount' => 'required|numeric|min:1',
            'month'         => 'required|integer|between:1,12',
            'year'          => 'required|integer|min:2024|max:2030',
        ], [
            'category_id.required'   => 'Category required hai.',
            'target_amount.required' => 'Target amount required hai.',
            'target_amount.min'      => 'Target amount 1 se zyada hona chahiye.',
        ]);

        // Duplicate check
        $exists = Budget::where('user_id', $request->user()->id)
            ->where('category_id', $validated['category_id'])
            ->where('month', $validated['month'])
            ->where('year',  $validated['year'])
            ->exists();

        if ($exists) {
            return $this->errorResponse(
                'Is category ka budget is month ke liye pehle se set hai.',
                null,
                422
            );
        }

        $budget = Budget::create([
            'user_id'       => $request->user()->id,
            'category_id'   => $validated['category_id'],
            'target_amount' => $validated['target_amount'],
            'month'         => $validated['month'],
            'year'          => $validated['year'],
        ]);

        return $this->successResponse($budget->load('category'), 'Budget set ho gaya.', 201);
    }

    /**
     * PUT /api/v1/budgets/{id}
     * Budget update karo (reason ke saath)
     */
    public function update(Request $request, $id)
    {
        $budget = Budget::where('user_id', $request->user()->id)
            ->findOrFail($id);

        $validated = $request->validate([
            'target_amount'   => 'required|numeric|min:1',
            'change_reason'   => 'required|string|max:255',
        ], [
            'target_amount.required' => 'Naya target amount required hai.',
            'change_reason.required' => 'Budget change ka reason required hai.',
        ]);

        $oldAmount = $budget->target_amount;

        // Change history save karo (BudgetTarget model mein)
        BudgetTarget::create([
            'budget_id'    => $budget->id,
            'old_amount'   => $oldAmount,
            'new_amount'   => $validated['target_amount'],
            'reason'       => $validated['change_reason'],
            'changed_at'   => now(),
        ]);

        $budget->update(['target_amount' => $validated['target_amount']]);

        return $this->successResponse(
            $budget->fresh('category'),
            "Budget ₹{$oldAmount} se ₹{$validated['target_amount']} update ho gaya."
        );
    }

    /**
     * GET /api/v1/budgets/weekly
     * Weekly budget status & notifications
     */
    public function weeklyStatus(Request $request)
    {
        $month = now()->month;
        $year  = now()->year;

        // Current week start/end
        $weekStart = now()->startOfWeek();
        $weekEnd   = now()->endOfWeek();

        $budgets = Budget::where('user_id', $request->user()->id)
            ->where('month', $month)
            ->where('year',  $year)
            ->with('category')
            ->get();

        // Weekly limit = monthly target / 4 weeks
        $weeklyBudgets = $budgets->map(function ($budget) use ($request, $weekStart, $weekEnd) {
            $weeklyLimit = $budget->target_amount / 4;

            $weeklySpent = Transaction::where('user_id', $request->user()->id)
                ->where('type', 'debit')
                ->where('category_id', $budget->category_id)
                ->whereBetween('transaction_date', [$weekStart, $weekEnd])
                ->sum('amount');

            $saved    = max(0, $weeklyLimit - $weeklySpent);
            $exceeded = max(0, $weeklySpent - $weeklyLimit);

            // Notification message
            $message = $weeklySpent > $weeklyLimit
                ? "Aapne is hafte {$budget->category->name} par ₹" . number_format($exceeded, 2) . " zyada kharch kiya."
                : "Aapne is hafte {$budget->category->name} par ₹" . number_format($saved, 2) . " bachaya!";

            return [
                'category'     => $budget->category?->name,
                'weekly_limit' => round($weeklyLimit, 2),
                'weekly_spent' => (float) $weeklySpent,
                'saved'        => round($saved, 2),
                'exceeded'     => round($exceeded, 2),
                'status'       => $weeklySpent > $weeklyLimit ? 'exceeded' : 'saved',
                'message'      => $message,
            ];
        });

        return $this->successResponse([
            'week_start'     => $weekStart->format('Y-m-d'),
            'week_end'       => $weekEnd->format('Y-m-d'),
            'weekly_budgets' => $weeklyBudgets,
        ], 'Weekly budget status.');
    }

    /**
     * DELETE /api/v1/budgets/{id}
     */
    public function destroy(Request $request, $id)
    {
        $budget = Budget::where('user_id', $request->user()->id)->findOrFail($id);
        $budget->delete();
        return $this->successResponse(null, 'Budget delete ho gaya.');
    }
}
