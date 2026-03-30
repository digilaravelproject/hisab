<?php

namespace App\Http\Controllers\Admin;

use App\Exports\AdminTransactionsExport;
use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\Category;
use App\Models\Transaction;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function monthly(Request $request)
    {
        $users = User::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();
        $businesses = Business::orderBy('name')->get();

        $month = (int) $request->query('month', now()->month);
        $year = (int) $request->query('year', now()->year);

        $baseQuery = Transaction::with(['user', 'category', 'business'])
            ->whereYear('transaction_date', $year)
            ->whereMonth('transaction_date', $month);

        $baseQuery = $this->applyFilters($request, $baseQuery);

        $transactions = (clone $baseQuery)
            ->orderByDesc('transaction_date')
            ->paginate(15)
            ->withQueryString();

        $totalCredit = (clone $baseQuery)->where('type', 'credit')->sum('amount');
        $totalDebit = (clone $baseQuery)->where('type', 'debit')->sum('amount');
        $totalCount = (clone $baseQuery)->count();
        $net = $totalCredit - $totalDebit;

        $categorySummary = (clone $baseQuery)
            ->selectRaw('category_id, SUM(CASE WHEN type = "credit" THEN amount ELSE 0 END) as credit, SUM(CASE WHEN type = "debit" THEN amount ELSE 0 END) as debit, COUNT(*) as transactions')
            ->groupBy('category_id')
            ->with('category')
            ->orderByDesc('transactions')
            ->get();

        return view('admin.reports.monthly', compact(
            'users',
            'categories',
            'businesses',
            'transactions',
            'month',
            'year',
            'totalCredit',
            'totalDebit',
            'net',
            'totalCount',
            'categorySummary'
        ));
    }

    public function yearly(Request $request)
    {
        $users = User::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();
        $businesses = Business::orderBy('name')->get();

        $year = (int) $request->query('year', now()->year);

        $baseQuery = Transaction::with(['user', 'category', 'business'])
            ->whereYear('transaction_date', $year);

        $baseQuery = $this->applyFilters($request, $baseQuery);

        $totalCredit = (clone $baseQuery)->where('type', 'credit')->sum('amount');
        $totalDebit = (clone $baseQuery)->where('type', 'debit')->sum('amount');
        $totalCount = (clone $baseQuery)->count();
        $net = $totalCredit - $totalDebit;

        $monthlySummary = (clone $baseQuery)
            ->selectRaw('MONTH(transaction_date) as month, SUM(CASE WHEN type = "credit" THEN amount ELSE 0 END) as credit, SUM(CASE WHEN type = "debit" THEN amount ELSE 0 END) as debit, COUNT(*) as transactions')
            ->groupByRaw('MONTH(transaction_date)')
            ->orderByRaw('MONTH(transaction_date)')
            ->get()
            ->keyBy('month');

        $categorySummary = (clone $baseQuery)
            ->selectRaw('category_id, SUM(CASE WHEN type = "credit" THEN amount ELSE 0 END) as credit, SUM(CASE WHEN type = "debit" THEN amount ELSE 0 END) as debit, COUNT(*) as transactions')
            ->groupBy('category_id')
            ->with('category')
            ->orderByDesc('transactions')
            ->get();

        return view('admin.reports.yearly', compact(
            'users',
            'categories',
            'businesses',
            'year',
            'totalCredit',
            'totalDebit',
            'net',
            'totalCount',
            'monthlySummary',
            'categorySummary'
        ));
    }

    public function export(Request $request)
    {
        $users = User::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();
        $businesses = Business::orderBy('name')->get();

        $fromDate = $request->query('from_date', now()->startOfMonth()->format('Y-m-d'));
        $toDate = $request->query('to_date', now()->endOfMonth()->format('Y-m-d'));

        $baseQuery = Transaction::with(['user', 'category', 'business'])
            ->whereDate('transaction_date', '>=', $fromDate)
            ->whereDate('transaction_date', '<=', $toDate);

        $baseQuery = $this->applyFilters($request, $baseQuery, true);

        $transactions = (clone $baseQuery)
            ->orderByDesc('transaction_date')
            ->paginate(15)
            ->withQueryString();

        $totalCredit = (clone $baseQuery)->where('type', 'credit')->sum('amount');
        $totalDebit = (clone $baseQuery)->where('type', 'debit')->sum('amount');
        $totalCount = (clone $baseQuery)->count();

        return view('admin.reports.export', compact(
            'users',
            'categories',
            'businesses',
            'transactions',
            'fromDate',
            'toDate',
            'totalCredit',
            'totalDebit',
            'totalCount'
        ));
    }

    public function exportPdf(Request $request)
    {
        $validated = $request->validate([
            'from_date'   => 'nullable|date_format:Y-m-d',
            'to_date'     => 'nullable|date_format:Y-m-d',
            'user_id'     => 'nullable|integer|exists:users,id',
            'category_id' => 'nullable|integer|exists:categories,id',
            'business_id' => 'nullable|integer|exists:businesses,id',
            'type'        => 'nullable|in:credit,debit,both',
            'source'      => 'nullable|in:bank,upi,cash',
        ]);

        $fromDate = $validated['from_date'] ?? now()->startOfMonth()->format('Y-m-d');
        $toDate = $validated['to_date'] ?? now()->endOfMonth()->format('Y-m-d');

        $query = Transaction::with(['user', 'category', 'business'])
            ->whereDate('transaction_date', '>=', $fromDate)
            ->whereDate('transaction_date', '<=', $toDate);

        $query = $this->applyFilters($request, $query, true);

        $transactions = $query->orderByDesc('transaction_date')->get();
        $totalCredit = $transactions->where('type', 'credit')->sum('amount');
        $totalDebit = $transactions->where('type', 'debit')->sum('amount');

        $user = null;
        if ($request->filled('user_id')) {
            $user = User::find($request->user_id);
        }
        if (! $user) {
            $user = (object) ['name' => 'All Users', 'mobile' => '—'];
        }

        $pdf = Pdf::loadView('reports.transactions-pdf', [
            'transactions' => $transactions,
            'from_date'    => Carbon::createFromFormat('Y-m-d', $fromDate)->format('d-m-Y'),
            'to_date'      => Carbon::createFromFormat('Y-m-d', $toDate)->format('d-m-Y'),
            'type'         => $request->query('type', 'both'),
            'user'         => $user,
            'total_credit' => $totalCredit,
            'total_debit'  => $totalDebit,
        ])->setPaper('a4', 'landscape');

        $filename = 'admin_transactions_' . now()->format('Y-m-d_His') . '.pdf';

        return $pdf->download($filename);
    }

    public function exportExcel(Request $request)
    {
        $validated = $request->validate([
            'from_date'   => 'nullable|date_format:Y-m-d',
            'to_date'     => 'nullable|date_format:Y-m-d',
            'user_id'     => 'nullable|integer|exists:users,id',
            'category_id' => 'nullable|integer|exists:categories,id',
            'business_id' => 'nullable|integer|exists:businesses,id',
            'type'        => 'nullable|in:credit,debit,both',
            'source'      => 'nullable|in:bank,upi,cash',
        ]);

        $fromDate = $validated['from_date'] ?? now()->startOfMonth()->format('Y-m-d');
        $toDate = $validated['to_date'] ?? now()->endOfMonth()->format('Y-m-d');

        $query = Transaction::with(['user', 'category', 'business'])
            ->whereDate('transaction_date', '>=', $fromDate)
            ->whereDate('transaction_date', '<=', $toDate);

        $query = $this->applyFilters($request, $query, true);

        $transactions = $query->orderByDesc('transaction_date')->get();

        $filename = 'admin_transactions_' . now()->format('Y-m-d_His') . '.xlsx';

        return Excel::download(new AdminTransactionsExport($transactions), $filename);
    }

    private function applyFilters(Request $request, $query, bool $allowDateRange = false)
    {
        return $query
            ->when($request->filled('user_id'), fn($q) => $q->where('user_id', $request->user_id))
            ->when($request->filled('category_id'), fn($q) => $q->where('category_id', $request->category_id))
            ->when($request->filled('business_id'), fn($q) => $q->where('business_id', $request->business_id))
            ->when($request->filled('type') && in_array($request->type, ['credit', 'debit']), fn($q) => $q->where('type', $request->type))
            ->when($request->filled('source'), fn($q) => $q->where('source', $request->source))
            ->when($allowDateRange && $request->filled('from_date'), fn($q) => $q->whereDate('transaction_date', '>=', $request->from_date))
            ->when($allowDateRange && $request->filled('to_date'), fn($q) => $q->whereDate('transaction_date', '<=', $request->to_date));
    }
}
