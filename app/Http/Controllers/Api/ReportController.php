<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Business;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TransactionsExport;

class ReportController extends Controller
{
    use ApiResponseTrait;

    /**
     * GET /api/v1/reports/monthly
     * Monthly credit/debit report with category breakdown
     */
    public function monthly(Request $request)
    {
        $month = $request->month ?? now()->month;
        $year  = $request->year  ?? now()->year;

        $base = Transaction::where('user_id', $request->user()->id)
            ->whereMonth('transaction_date', $month)
            ->whereYear('transaction_date',  $year);

        // Total credit/debit
        $totalCredit = (clone $base)->where('type', 'credit')->sum('amount');
        $totalDebit  = (clone $base)->where('type', 'debit')->sum('amount');

        // Category-wise breakdown
        $categoryBreakdown = (clone $base)
            ->select('category_id', 'type', DB::raw('SUM(amount) as total'), DB::raw('COUNT(*) as count'))
            ->groupBy('category_id', 'type')
            ->with('category')
            ->get()
            ->map(fn($item) => [
                'category'     => $item->category?->name ?? 'Uncategorized',
                'type'         => $item->type,
                'total'        => (float) $item->total,
                'transactions' => $item->count,
            ]);

        // Source-wise breakdown (bank, upi, cash)
        $sourceBreakdown = (clone $base)
            ->select('source', 'type', DB::raw('SUM(amount) as total'))
            ->groupBy('source', 'type')
            ->get()
            ->map(fn($item) => [
                'source' => strtoupper($item->source),
                'type'   => $item->type,
                'total'  => (float) $item->total,
            ]);

        // Business-wise breakdown
        $businessBreakdown = (clone $base)
            ->whereNotNull('business_id')
            ->select('business_id', 'type', DB::raw('SUM(amount) as total'))
            ->groupBy('business_id', 'type')
            ->with('business')
            ->get()
            ->map(fn($item) => [
                'business' => $item->business?->name ?? '—',
                'type'     => $item->type,
                'total'    => (float) $item->total,
            ]);

        // Day-wise trend (for graph)
        $dailyTrend = (clone $base)
            ->select(
                DB::raw('DAY(transaction_date) as day'),
                'type',
                DB::raw('SUM(amount) as total')
            )
            ->groupBy('day', 'type')
            ->orderBy('day')
            ->get()
            ->groupBy('day')
            ->map(fn($group) => [
                'day'    => $group->first()->day,
                'credit' => (float) ($group->firstWhere('type', 'credit')?->total ?? 0),
                'debit'  => (float) ($group->firstWhere('type', 'debit')?->total  ?? 0),
            ])
            ->values();

        return $this->successResponse([
            'month'              => $month,
            'year'               => $year,
            'summary'            => [
                'total_credit'  => (float) $totalCredit,
                'total_debit'   => (float) $totalDebit,
                'net_balance'   => (float) ($totalCredit - $totalDebit),
                'total_txns'    => (clone $base)->count(),
                'uncategorized' => (clone $base)->whereNull('category_id')->count(),
            ],
            'category_breakdown' => $categoryBreakdown,
            'source_breakdown'   => $sourceBreakdown,
            'business_breakdown' => $businessBreakdown,
            'daily_trend'        => $dailyTrend,
        ], 'Monthly report ready.');
    }

    /**
     * GET /api/v1/reports/yearly
     * Year-wise month-by-month summary
     */
    public function yearly(Request $request)
    {
        $year = $request->year ?? now()->year;

        $monthlyData = Transaction::where('user_id', $request->user()->id)
            ->whereYear('transaction_date', $year)
            ->select(
                DB::raw('MONTH(transaction_date) as month'),
                'type',
                DB::raw('SUM(amount) as total'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('month', 'type')
            ->orderBy('month')
            ->get()
            ->groupBy('month')
            ->map(fn($group, $month) => [
                'month'  => $month,
                'credit' => (float) ($group->firstWhere('type', 'credit')?->total ?? 0),
                'debit'  => (float) ($group->firstWhere('type', 'debit')?->total  ?? 0),
                'count'  => $group->sum('count'),
            ])
            ->values();

        $totalCredit = $monthlyData->sum('credit');
        $totalDebit  = $monthlyData->sum('debit');

        return $this->successResponse([
            'year'         => $year,
            'summary'      => [
                'total_credit' => $totalCredit,
                'total_debit'  => $totalDebit,
                'net_balance'  => $totalCredit - $totalDebit,
            ],
            'monthly_data' => $monthlyData,
        ], 'Yearly report ready.');
    }

    /**
     * GET /api/v1/reports/comparison
     * Month vs last month, Year vs last year
     */
    public function comparison(Request $request)
    {
        $user = $request->user();

        // This month vs last month
        $thisMonth = $this->getMonthSummary($user->id, now()->month, now()->year);
        $lastMonth = $this->getMonthSummary(
            $user->id,
            now()->subMonth()->month,
            now()->subMonth()->year
        );

        // This year vs last year
        $thisYear = Transaction::where('user_id', $user->id)
            ->whereYear('transaction_date', now()->year)
            ->selectRaw("
                SUM(CASE WHEN type='credit' THEN amount ELSE 0 END) as credit,
                SUM(CASE WHEN type='debit'  THEN amount ELSE 0 END) as debit
            ")->first();

        $lastYear = Transaction::where('user_id', $user->id)
            ->whereYear('transaction_date', now()->year - 1)
            ->selectRaw("
                SUM(CASE WHEN type='credit' THEN amount ELSE 0 END) as credit,
                SUM(CASE WHEN type='debit'  THEN amount ELSE 0 END) as debit
            ")->first();

        return $this->successResponse([
            'monthly_comparison' => [
                'this_month' => $thisMonth,
                'last_month' => $lastMonth,
                'credit_change_pct' => $this->calcPct($lastMonth['credit'], $thisMonth['credit']),
                'debit_change_pct'  => $this->calcPct($lastMonth['debit'],  $thisMonth['debit']),
            ],
            'yearly_comparison' => [
                'this_year' => [
                    'year'   => now()->year,
                    'credit' => (float) $thisYear->credit,
                    'debit'  => (float) $thisYear->debit,
                ],
                'last_year' => [
                    'year'   => now()->year - 1,
                    'credit' => (float) $lastYear->credit,
                    'debit'  => (float) $lastYear->debit,
                ],
                'credit_change_pct' => $this->calcPct($lastYear->credit, $thisYear->credit),
                'debit_change_pct'  => $this->calcPct($lastYear->debit,  $thisYear->debit),
            ],
        ], 'Comparison report ready.');
    }

    /**
     * GET /api/v1/reports/export/pdf
     * PDF export
     */
    public function exportPdf(Request $request)
    {
        $request->validate([
            'type'  => 'required|in:credit,debit,both',
            'month' => 'required|integer|between:1,12',
            'year'  => 'required|integer|min:2024',
        ]);

        $query = Transaction::where('user_id', $request->user()->id)
            ->whereMonth('transaction_date', $request->month)
            ->whereYear('transaction_date',  $request->year)
            ->with(['category', 'business']);

        if ($request->type !== 'both') {
            $query->where('type', $request->type);
        }

        $transactions = $query->orderBy('transaction_date')->get();

        $pdf = Pdf::loadView('reports.transactions-pdf', [
            'transactions' => $transactions,
            'month'        => $request->month,
            'year'         => $request->year,
            'type'         => $request->type,
            'user'         => $request->user(),
            'total_credit' => $transactions->where('type', 'credit')->sum('amount'),
            'total_debit'  => $transactions->where('type', 'debit')->sum('amount'),
        ])->setPaper('a4', 'portrait');

        $filename = "vitai_report_{$request->year}_{$request->month}.pdf";

        return $pdf->download($filename);
    }

    /**
     * GET /api/v1/reports/export/excel
     * Excel/CSV export
     */
    public function exportExcel(Request $request)
    {
        $request->validate([
            'type'  => 'required|in:credit,debit,both',
            'month' => 'required|integer|between:1,12',
            'year'  => 'required|integer|min:2024',
        ]);

        $filename = "vitai_report_{$request->year}_{$request->month}.xlsx";

        return Excel::download(
            new TransactionsExport($request->user()->id, $request->month, $request->year, $request->type),
            $filename
        );
    }

    // ── Private Helpers ──

    private function getMonthSummary(int $userId, int $month, int $year): array
    {
        $result = Transaction::where('user_id', $userId)
            ->whereMonth('transaction_date', $month)
            ->whereYear('transaction_date',  $year)
            ->selectRaw("
                SUM(CASE WHEN type='credit' THEN amount ELSE 0 END) as credit,
                SUM(CASE WHEN type='debit'  THEN amount ELSE 0 END) as debit,
                COUNT(*) as total_txns
            ")->first();

        return [
            'month'      => $month,
            'year'       => $year,
            'credit'     => (float) ($result->credit ?? 0),
            'debit'      => (float) ($result->debit  ?? 0),
            'net'        => (float) (($result->credit ?? 0) - ($result->debit ?? 0)),
            'total_txns' => (int)   ($result->total_txns ?? 0),
        ];
    }

    private function calcPct(float $old, float $new): float
    {
        if ($old == 0) return $new > 0 ? 100.0 : 0.0;
        return round((($new - $old) / $old) * 100, 1);
    }
}
