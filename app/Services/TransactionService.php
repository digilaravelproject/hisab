<?php

namespace App\Services;

use App\Models\User;
use App\Models\Transaction;
use App\Models\Budget;
use App\Models\Category;
use Illuminate\Support\Facades\DB;

class TransactionService
{
    public function createCashTransaction(User $user, array $data): Transaction
    {
        return Transaction::create([
            ...$data,
            'user_id'        => $user->id,
            'source'         => 'cash',
            'is_categorized' => isset($data['category_id']),
        ]);
    }

    public function getMonthlySummary(User $user, int $year, int $month): array
    {
        $base = Transaction::where('user_id', $user->id)->forMonth($year, $month);

        $totalCredit = (clone $base)->credit()->sum('amount');
        $totalDebit  = (clone $base)->debit()->sum('amount');

        $categoryBreakdown = (clone $base)
            ->debit()
            ->with('category')
            ->select('category_id', DB::raw('SUM(amount) as total'))
            ->groupBy('category_id')
            ->get();

        $businessBreakdown = (clone $base)
            ->whereNotNull('business_id')
            ->select('business_id', 'type', DB::raw('SUM(amount) as total'))
            ->groupBy('business_id', 'type')
            ->with('business')
            ->get();

        return [
            'year'               => $year,
            'month'              => $month,
            'total_credit'       => $totalCredit,
            'total_debit'        => $totalDebit,
            'net_balance'        => $totalCredit - $totalDebit,
            'category_breakdown' => $categoryBreakdown,
            'business_breakdown' => $businessBreakdown,
            'uncategorized_count' => Transaction::where('user_id', $user->id)
                ->forMonth($year, $month)
                ->uncategorized()
                ->count(),
        ];
    }

    public function getDashboardData(User $user, ?int $businessId = null, bool $onlyWithBusiness = false): array
    {
        $query = Transaction::query();

        // Filter by user_id
        $query->where('user_id', $user->id);

        // Filter by specific business_id if provided
        if ($businessId) {
            $query->where('business_id', $businessId);
        }

        // If onlyWithBusiness is true, get only transactions with business_id != NULL
        if ($onlyWithBusiness) {
            $query->whereNotNull('business_id');
        }

        // Get total credit and debit
        $totalCredit = (clone $query)->credit()->sum('amount');
        $totalDebit  = (clone $query)->debit()->sum('amount');

        // Calculate net balance
        $netBalance = $totalCredit - $totalDebit;

        // Get today's activity
        $today = now()->toDateString();
        $todaysCredit = (clone $query)
            ->credit()
            ->whereDate('transaction_date', $today)
            ->sum('amount');

        $todaysDebit = (clone $query)
            ->debit()
            ->whereDate('transaction_date', $today)
            ->sum('amount');

        // Get weekly budget data
        $weeklyBudgetData = $this->getWeeklyBudgetData($user, $businessId);

        return [
            'total_balance'  => $totalCredit - $totalDebit,
            'income'         => (float) $totalCredit,
            'expense'        => (float) $totalDebit,
            'net'            => (float) $netBalance,
            'today_activity' => [
                'credit' => (float) $todaysCredit,
                'debit'  => (float) $todaysDebit,
            ],
            'weekly_budget' => $weeklyBudgetData,
        ];
    }

    private function getWeeklyBudgetData(User $user, ?int $businessId = null): array
    {
        $month = now()->month;
        $year = now()->year;
        $weekStart = now()->startOfWeek();
        $weekEnd = now()->endOfWeek();

        // Get all budgets for current month
        $budgetsQuery = Budget::where('user_id', $user->id)
            ->where('month', $month)
            ->where('year', $year)
            ->with('category');

        $budgets = $budgetsQuery->get();

        $weeklyBudgets = $budgets->map(function ($budget) use ($user, $weekStart, $weekEnd, $businessId) {
            // Get weekly limit (target_amount / 4 weeks)
            $weeklyLimit = (float) $budget->target_amount / 4;

            // Get weekly spending for this category
            $spendingQuery = Transaction::where('user_id', $user->id)
                ->where('type', 'debit')
                ->where('category_id', $budget->category_id)
                ->whereBetween('transaction_date', [$weekStart, $weekEnd]);

            // If businessId is provided, filter by business
            if ($businessId) {
                $spendingQuery->where('business_id', $businessId);
            }

            $weeklySpent = (float) $spendingQuery->sum('amount');

            // Calculate percentage
            $percentage = $weeklyLimit > 0
                ? round(($weeklySpent / $weeklyLimit) * 100, 1)
                : 0;

            return [
                'category_id'  => $budget->category_id,
                'category_name' => $budget->category?->name ?? 'Unknown',
                'weekly_budget' => $weeklyLimit,
                'weekly_spent' => $weeklySpent,
                'percentage'   => $percentage,
            ];
        })->toArray();

        return $weeklyBudgets;
    }
}
