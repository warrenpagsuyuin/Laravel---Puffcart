@extends('layouts.admin')

@section('title', 'Reports')
@section('page-title', 'Reports')
@section('page-subtitle', 'Sales, order, payment, and product performance summaries')

@section('content')
    <style>
        .reports-toolbar {
            align-items: end;
            display: grid;
            gap: 14px;
            grid-template-columns: 1fr auto;
            margin-bottom: 18px;
        }

        .reports-toolbar__copy h2 {
            font-size: 24px;
            margin: 0 0 4px;
        }

        .reports-toolbar__copy p {
            margin: 0;
        }

        .reports-filter {
            align-items: end;
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            justify-content: flex-end;
        }

        .reports-filter .form-group {
            display: grid;
            gap: 6px;
            min-width: 170px;
        }

        .reports-filter label {
            color: var(--text-muted);
            font-size: 12px;
            font-weight: 800;
            letter-spacing: 0.02em;
            text-transform: uppercase;
        }

        .reports-filter input {
            min-height: 42px;
        }

        .reports-export {
            background: #0f172a;
            color: #ffffff;
        }

        .reports-export:hover {
            background: #1e293b;
            color: #ffffff;
        }

        .report-kpis {
            display: grid;
            gap: 16px;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            margin-bottom: 18px;
        }

        .report-kpi {
            background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-sm);
            padding: 20px;
        }

        .report-kpi span {
            color: var(--text-muted);
            display: block;
            font-size: 12px;
            font-weight: 800;
            letter-spacing: 0.04em;
            margin-bottom: 12px;
            text-transform: uppercase;
        }

        .report-kpi strong {
            color: var(--text-primary);
            display: block;
            font-family: 'Poppins', sans-serif;
            font-size: 34px;
            line-height: 1.1;
        }

        .report-kpi small {
            color: var(--text-secondary);
            display: block;
            margin-top: 8px;
        }

        .report-grid {
            display: grid;
            gap: 18px;
            grid-template-columns: minmax(0, 1fr) minmax(360px, 0.72fr);
            margin-top: 18px;
        }

        .report-panel {
            overflow: hidden;
            padding: 0;
        }

        .report-panel__header {
            align-items: center;
            border-bottom: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            padding: 18px 20px;
        }

        .report-panel__header h2 {
            font-size: 20px;
            margin: 0;
        }

        .report-panel__body {
            padding: 4px 20px 20px;
        }

        .report-panel .admin-table th,
        .report-panel .admin-table td {
            padding-left: 0;
            padding-right: 0;
        }

        @media (max-width: 1100px) {
            .reports-toolbar,
            .report-grid,
            .report-kpis {
                grid-template-columns: 1fr;
            }

            .reports-filter {
                justify-content: flex-start;
            }
        }
    </style>

    <section class="panel reports-toolbar">
        <div class="reports-toolbar__copy">
            <h2>Performance Report</h2>
            <p class="muted">Review sales, orders, product movement, and payment activity for the selected period.</p>
        </div>

        <form method="GET" action="{{ route('admin.reports.index') }}" class="reports-filter">
            <div class="form-group">
                <label for="from">From</label>
                <input id="from" type="date" name="from" value="{{ $from->toDateString() }}">
            </div>
            <div class="form-group">
                <label for="to">To</label>
                <input id="to" type="date" name="to" value="{{ $to->toDateString() }}">
            </div>
            <button type="submit" class="btn btn-primary">Apply</button>
            <a
                class="btn reports-export"
                href="{{ route('admin.reports.export-pdf', ['from' => $from->toDateString(), 'to' => $to->toDateString()]) }}"
            >
                Export PDF
            </a>
        </form>
    </section>

    <div class="report-kpis">
        <div class="report-kpi">
            <span>Orders</span>
            <strong>{{ number_format($totalOrders) }}</strong>
            <small>Total online and walk-in orders</small>
        </div>
        <div class="report-kpi">
            <span>Paid Revenue</span>
            <strong>PHP {{ number_format($totalRevenue, 2) }}</strong>
            <small>Completed online and walk-in sales</small>
        </div>
        <div class="report-kpi">
            <span>Report Range</span>
            <strong style="font-size:24px;">{{ $from->format('M d') }} - {{ $to->format('M d, Y') }}</strong>
            <small>{{ (int) $from->copy()->startOfDay()->diffInDays($to->copy()->startOfDay()) + 1 }} calendar days</small>
        </div>
    </div>

    <div class="report-grid">
        <section class="panel report-panel">
            <div class="report-panel__header">
                <h2>Top Products</h2>
                <span class="muted">By units sold</span>
            </div>

            <div class="report-panel__body table-wrap">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Category</th>
                            <th>Units Sold</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($topProducts as $product)
                            <tr>
                                <td><strong>{{ $product->name }}</strong></td>
                                <td>{{ $product->category ?: 'Uncategorized' }}</td>
                                <td>{{ (int) ($product->units_sold ?? 0) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="muted">No product sales yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <section class="panel report-panel">
            <div class="report-panel__header">
                <h2>Order Status</h2>
                <span class="muted">Current distribution</span>
            </div>

            <div class="report-panel__body table-wrap">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Status</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($statusCounts as $status => $total)
                            <tr>
                                <td><span class="badge badge-blue">{{ ucfirst(str_replace('_', ' ', $status)) }}</span></td>
                                <td>{{ $total }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="muted">No orders yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>

    <div class="report-grid">
        <section class="panel report-panel">
            <div class="report-panel__header">
                <h2>Daily Sales</h2>
                <span class="muted">Paid totals</span>
            </div>

            <div class="report-panel__body table-wrap">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($dailySales as $day)
                            <tr>
                                <td>{{ \Illuminate\Support\Carbon::parse($day->date)->format('M d, Y') }}</td>
                                <td>PHP {{ number_format($day->total, 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="muted">No paid sales in this date range.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <section class="panel report-panel">
            <div class="report-panel__header">
                <h2>Recent Payments</h2>
                <span class="muted">Latest 10</span>
            </div>

            <div class="report-panel__body table-wrap">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Order</th>
                            <th>Method</th>
                            <th>Status</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentPayments as $payment)
                            <tr>
                                <td>{{ $payment->order?->order_number ?? 'No order' }}</td>
                                <td>{{ strtoupper(str_replace('_', ' ', $payment->method)) }}</td>
                                <td><span class="badge badge-blue">{{ ucfirst($payment->status) }}</span></td>
                                <td>PHP {{ number_format($payment->amount, 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="muted">No payments recorded.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>
@endsection
