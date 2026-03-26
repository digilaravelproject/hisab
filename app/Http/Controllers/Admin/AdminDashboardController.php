<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Transaction;
use App\Models\Business;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    /**
     * Dashboard main page
     */
    public function index()
    {
        // Stats
        $stats = [
            'total_users'              => User::count(),
            'new_users_this_month'     => User::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
            'total_transactions'       => Transaction::count(),
            'today_transactions'       => Transaction::whereDate('created_at', today())->count(),
            'total_credit'             => Transaction::where('type', 'credit')->sum('amount'),
            'total_debit'              => Transaction::where('type', 'debit')->sum('amount'),
            'total_businesses'         => Business::count(),
            'uncategorized_transactions' => Transaction::where('is_categorized', false)->count(),
        ];

        // Recent 10 transactions (with user relationship)
        $recentTransactions = Transaction::with('user')
            ->latest('transaction_date')
            ->take(10)
            ->get();

        // Recent 6 users
        $recentUsers = User::latest()->take(6)->get();

        // Monthly data for chart (current year)
        $monthlyData = Transaction::selectRaw('
                MONTH(transaction_date) as month,
                type,
                SUM(amount) as total
            ')
            ->whereYear('transaction_date', now()->year)
            ->groupBy('month', 'type')
            ->get()
            ->groupBy('month')
            ->map(function ($items) {
                return [
                    'credit' => $items->where('type', 'credit')->first()?->total ?? 0,
                    'debit'  => $items->where('type', 'debit')->first()?->total  ?? 0,
                ];
            });

        return view('admin.dashboard.index', compact(
            'stats',
            'recentTransactions',
            'recentUsers',
            'monthlyData'
        ));
    }
}
