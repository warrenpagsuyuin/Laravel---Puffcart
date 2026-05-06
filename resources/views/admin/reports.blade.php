@extends('layouts.admin')

@section('title', 'Reports')
@section('page-title', 'Reports')
@section('page-subtitle', 'Sales, order, payment, and product performance summaries')

@section('content')
    <section class="panel" style="margin-bottom:16px;">
        <form method="GET" action="{{ route('admin.reports.index') }}" class="actions">
            <div class="form-group" style="min-width:180px;">
                <label for="from">From</label>
                <input id="from" type="date" name="from" value="{{ $from->toDateString() }}">
            </div>
            <div class="form-group" style="min-width:180px;">
                <label for="to">To</label>
                <input id="to" type="date" name="to" value="{{ $to->toDateString() }}">
            </div>
            <button type="submit" class="btn btn-primary" style="align-self:end;">Apply</button>
        </form>
    </section>

    <div class="grid grid-3">
        <div class="stat-card">
            <div class="stat-label">Orders</div>
            <div class="stat-value">{{ number_format($totalOrders) }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Paid Revenue</div>
            <div class="stat-value">₱{{ number_format($totalRevenue, 2) }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Report Range</div>
            <div class="stat-value" style="font-size:18px;">{{ $from->format('M d') }} - {{ $to->format('M d') }}</div>
        </div>
    </div>

    <div class="grid grid-2" style="margin-top:16px;">
        <section class="panel">
            <div class="section-title">
                <h2>Top Products</h2>
            </div>

            <div class="table-wrap">
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

        <section class="panel">
            <div class="section-title">
                <h2>Order Status</h2>
            </div>

            <div class="table-wrap">
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

    <div class="grid grid-2" style="margin-top:16px;">
        <section class="panel">
            <div class="section-title">
                <h2>Daily Sales</h2>
            </div>

            <div class="table-wrap">
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
                                <td>₱{{ number_format($day->total, 2) }}</td>
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

        <section class="panel">
            <div class="section-title">
                <h2>Recent Payments</h2>
            </div>

            <div class="table-wrap">
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
                                <td>₱{{ number_format($payment->amount, 2) }}</td>
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
