<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transactions Report</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 10px;
            color: #333;
            line-height: 1.4;
        }
        
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #1B3A6B;
            padding-bottom: 10px;
        }
        
        .header h1 {
            font-size: 18px;
            color: #1B3A6B;
            margin-bottom: 5px;
        }
        
        .header p {
            font-size: 9px;
            color: #666;
        }
        
        .header-info {
            display: flex;
            justify-content: space-between;
            font-size: 9px;
            margin-bottom: 15px;
            padding: 10px 0;
        }
        
        .header-info .item {
            flex: 1;
        }
        
        .header-info label {
            font-weight: bold;
            color: #1B3A6B;
        }
        
        .summary {
            display: flex;
            gap: 30px;
            margin-bottom: 20px;
            padding: 10px;
            background: #f5f5f5;
            border-radius: 4px;
        }
        
        .summary-box {
            flex: 1;
        }
        
        .summary-box h4 {
            font-size: 9px;
            color: #666;
            text-transform: uppercase;
            margin-bottom: 5px;
        }
        
        .summary-box .amount {
            font-size: 14px;
            font-weight: bold;
            color: #1B3A6B;
        }
        
        .summary-box.credit .amount {
            color: #27ae60;
        }
        
        .summary-box.debit .amount {
            color: #e74c3c;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        
        table thead {
            background: #1B3A6B;
            color: white;
        }
        
        table thead th {
            padding: 8px;
            text-align: left;
            font-weight: bold;
            font-size: 9px;
            border: 1px solid #1B3A6B;
        }
        
        table tbody td {
            padding: 7px;
            border: 1px solid #ddd;
            font-size: 9px;
        }
        
        table tbody tr:nth-child(even) {
            background: #f9f9f9;
        }
        
        table tbody tr:hover {
            background: #f0f0f0;
        }
        
        .text-center {
            text-align: center;
        }
        
        .text-right {
            text-align: right;
        }
        
        .type-credit {
            color: #27ae60;
            font-weight: bold;
        }
        
        .type-debit {
            color: #e74c3c;
            font-weight: bold;
        }
        
        .footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #ddd;
            text-align: center;
            font-size: 8px;
            color: #999;
        }
        
        .page-break {
            page-break-after: always;
        }
        
        .no-data {
            text-align: center;
            padding: 30px;
            color: #999;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>📊 Transaction Report</h1>
        <p>Generated on {{ now()->format('d M Y, H:i') }}</p>
    </div>
    
    <div class="header-info">
        <div class="item">
            <label>User Name:</label> {{ $user->name ?? 'N/A' }}
        </div>
        <div class="item">
            <label>Mobile:</label> {{ $user->mobile ?? 'N/A' }}
        </div>
        <div class="item">
            <label>Period:</label> {{ $from_date }} to {{ $to_date }}
        </div>
    </div>
    
    @if(count($transactions) > 0)
        <div class="summary">
            <div class="summary-box credit">
                <h4>Total Credit</h4>
                <div class="amount">₹{{ number_format($total_credit, 2) }}</div>
            </div>
            <div class="summary-box debit">
                <h4>Total Debit</h4>
                <div class="amount">₹{{ number_format($total_debit, 2) }}</div>
            </div>
            <div class="summary-box">
                <h4>Net Balance</h4>
                <div class="amount" style="color: {{ $total_credit >= $total_debit ? '#27ae60' : '#e74c3c' }}">
                    ₹{{ number_format($total_credit - $total_debit, 2) }}
                </div>
            </div>
            <div class="summary-box">
                <h4>Transaction Count</h4>
                <div class="amount">{{ count($transactions) }}</div>
            </div>
        </div>
        
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Date</th>
                    <th>Type</th>
                    <th>Source</th>
                    <th>Amount (₹)</th>
                    <th>Category</th>
                    <th>Business</th>
                    <th>Description</th>
                    <th>Reference No</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactions as $index => $txn)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $txn->transaction_date?->format('d-m-Y') }}</td>
                        <td class="text-center">
                            <span class="{{ $txn->type === 'credit' ? 'type-credit' : 'type-debit' }}">
                                {{ strtoupper($txn->type) }}
                            </span>
                        </td>
                        <td class="text-center">{{ strtoupper($txn->source) }}</td>
                        <td class="text-right">{{ number_format($txn->amount, 2) }}</td>
                        <td>{{ $txn->category?->name ?? 'Uncategorized' }}</td>
                        <td>{{ $txn->business?->name ?? '—' }}</td>
                        <td>{{ $txn->description ?? '—' }}</td>
                        <td>{{ $txn->reference_no ?? '—' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="no-data">No transactions found for the selected period.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    @else
        <div class="no-data">
            <p>No transactions found for the selected date range and filters.</p>
        </div>
    @endif
    
    <div class="footer">
        <p>This is an auto-generated report. Please verify the data before taking any decisions.</p>
        <p>Report generated at {{ now()->format('d M Y, H:i:s') }}</p>
    </div>
</body>
</html>
