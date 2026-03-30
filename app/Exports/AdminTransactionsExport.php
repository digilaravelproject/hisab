<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AdminTransactionsExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    WithStyles,
    ShouldAutoSize
{
    public function __construct(
        private \Illuminate\Database\Eloquent\Collection $transactions
    ) {}

    public function collection()
    {
        return $this->transactions;
    }

    public function headings(): array
    {
        return [
            '#',
            'Date',
            'Type',
            'Source',
            'Amount (₹)',
            'User',
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
            $txn->user?->name ?? '—',
            $txn->category?->name ?? 'Uncategorized',
            $txn->business?->name ?? '—',
            $txn->description ?? '—',
            $txn->reference_no ?? '—',
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '1B3A6B']],
            ],
        ];
    }
}
