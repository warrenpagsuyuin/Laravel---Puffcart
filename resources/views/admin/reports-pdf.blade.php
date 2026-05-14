<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Puffcart Performance Report</title>
    <style>
        @page {
            margin: 26px 30px;
        }

        body {
            color: #111827;
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            line-height: 1.45;
            margin: 0;
        }

        h1,
        h2,
        h3 {
            margin: 0;
        }

        .header {
            border-bottom: 2px solid #0b63f6;
            margin-bottom: 18px;
            padding-bottom: 12px;
        }

        .brand {
            color: #0b63f6;
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .subtitle {
            color: #64748b;
            margin-top: 4px;
        }

        h1 {
            font-size: 26px;
            line-height: 1.15;
        }

        .kpis {
            display: table;
            border-collapse: collapse;
            margin-bottom: 18px;
            table-layout: fixed;
            width: 100%;
        }

        .kpi {
            background: #f8fafc;
            border: 1px solid #dbe4ef;
            display: table-cell;
            padding: 12px 14px;
            width: 33.333%;
        }

        .kpi span {
            color: #64748b;
            display: block;
            font-size: 9px;
            font-weight: 700;
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }

        .kpi strong {
            color: #0f172a;
            display: block;
            font-size: 20px;
            line-height: 1.25;
            margin-top: 8px;
        }

        .kpi strong.range {
            font-size: 17px;
            white-space: nowrap;
        }

        .section {
            margin-top: 16px;
            page-break-inside: avoid;
        }

        .section h2 {
            border-bottom: 1px solid #dbe4ef;
            color: #0f172a;
            font-size: 15px;
            margin-bottom: 8px;
            padding-bottom: 6px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        th {
            background: #eef4ff;
            color: #64748b;
            font-size: 9px;
            letter-spacing: 0.03em;
            text-align: left;
            text-transform: uppercase;
        }

        th,
        td {
            border-bottom: 1px solid #e5e7eb;
            padding: 7px 8px;
            vertical-align: top;
        }

        tbody tr:nth-child(even) td {
            background: #fbfdff;
        }

        .text-right {
            text-align: right;
        }

        .empty {
            color: #64748b;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="brand">Puffcart</div>
        <h1>Performance Report</h1>
        <div class="subtitle">
            {{ $from->format('M d, Y') }} to {{ $to->format('M d, Y') }}
        </div>
    </div>

    <div class="kpis">
        <div class="kpi">
            <span>Orders</span>
            <strong>{{ number_format($totalOrders) }}</strong>
        </div>
        <div class="kpi">
            <span>Paid Revenue</span>
            <strong>PHP {{ number_format($totalRevenue, 2) }}</strong>
        </div>
        <div class="kpi">
            <span>Report Range</span>
            <strong class="range">{{ $from->format('M d') }} - {{ $to->format('M d, Y') }}</strong>
        </div>
    </div>

    <div class="section">
        <h2>Top Products</h2>
        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Category</th>
                    <th class="text-right">Units Sold</th>
                </tr>
            </thead>
            <tbody>
                @forelse($topProducts as $product)
                    <tr>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->category ?: 'Uncategorized' }}</td>
                        <td class="text-right">{{ (int) ($product->units_sold ?? 0) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="empty">No product sales yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="section">
        <h2>Order Status</h2>
        <table>
            <thead>
                <tr>
                    <th>Status</th>
                    <th class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                @forelse($statusCounts as $status => $total)
                    <tr>
                        <td>{{ ucfirst(str_replace('_', ' ', $status)) }}</td>
                        <td class="text-right">{{ $total }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2" class="empty">No orders yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="section">
        <h2>Daily Sales</h2>
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                @forelse($dailySales as $day)
                    <tr>
                        <td>{{ \Illuminate\Support\Carbon::parse($day->date)->format('M d, Y') }}</td>
                        <td class="text-right">PHP {{ number_format($day->total, 2) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2" class="empty">No paid sales in this date range.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="section">
        <h2>Recent Payments</h2>
        <table>
            <thead>
                <tr>
                    <th>Order</th>
                    <th>Method</th>
                    <th>Status</th>
                    <th class="text-right">Amount</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentPayments as $payment)
                    <tr>
                        <td>{{ $payment->order?->order_number ?? 'No order' }}</td>
                        <td>{{ strtoupper(str_replace('_', ' ', $payment->method)) }}</td>
                        <td>{{ ucfirst($payment->status) }}</td>
                        <td class="text-right">PHP {{ number_format($payment->amount, 2) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="empty">No payments recorded.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</body>
</html>
