<?php

namespace App\Exports;

use App\Models\Transaction;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TransactionsExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    WithStyles,
    ShouldAutoSize
{
    public function __construct(
        private int    $userId,
        private int    $month,
        private int    $year,
        private string $type = 'both'
    ) {}

    public function collection()
    {
        return Transaction::where('user_id', $this->userId)
            ->whereMonth('transaction_date', $this->month)
            ->whereYear('transaction_date',  $this->year)
            ->when($this->type !== 'both', fn($q) => $q->where('type', $this->type))
            ->with(['category', 'business'])
            ->orderBy('transaction_date')
            ->get();
    }

    public function headings(): array
    {
        return [
            '#',
            'Date',
            'Type',
            'Source',
            'Amount (₹)',
            'Category',
            'Business',
            'Description',
            'Reference No',
        ];
    }

    public function map($txn): array
    {
        static $i = 0;
        $i++;
        return [
            $i,
            $txn->transaction_date?->format('d-m-Y'),
            strtoupper($txn->type),
            strtoupper($txn->source),
            number_format($txn->amount, 2),
            $txn->category?->name ?? 'Uncategorized',
            $txn->business?->name ?? '—',
            $txn->description ?? '—',
            $txn->reference_no ?? '—',
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            // Header row bold + background
            1 => [
                'font'    => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill'    => ['fillType' => 'solid', 'startColor' => ['rgb' => '1B3A6B']],
            ],
        ];
    }
}
