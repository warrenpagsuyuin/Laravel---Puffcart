@extends('layouts.admin')

@section('title', 'Machine Learning Insights')
@section('page-title', 'Machine Learning Insights')
@section('page-subtitle', 'Rule-based demand, inventory, and risk signals')

@section('content')
    <div class="grid grid-3">
        <div class="stat-card">
            <div class="stat-label">Orders Last 7 Days</div>
            <div class="stat-value">{{ number_format($salesTrend['orders_7_days']) }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Orders Last 30 Days</div>
            <div class="stat-value">{{ number_format($salesTrend['orders_30_days']) }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Paid Revenue Last 30 Days</div>
            <div class="stat-value">₱{{ number_format($salesTrend['paid_revenue_30_days'], 2) }}</div>
        </div>
    </div>

    <div class="grid grid-3" style="margin-top:16px;">
        <div class="stat-card">
            <div class="stat-label">Customer Accounts</div>
            <div class="stat-value">{{ number_format($customerBehavior['customers']) }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Repeat Customers</div>
            <div class="stat-value">{{ number_format($customerBehavior['repeat_customers']) }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Avg Orders Per Customer</div>
            <div class="stat-value">{{ number_format($customerBehavior['average_orders'], 2) }}</div>
        </div>
    </div>

    <div class="grid grid-2" style="margin-top:16px;">
        <section class="panel">
            <div class="section-title">
                <h2>Predicted High Demand Products</h2>
            </div>

            <div class="table-wrap">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Stock</th>
                            <th>Units Sold</th>
                            <th>Demand</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($highDemandProducts as $product)
                            <tr>
                                <td><strong>{{ $product->name }}</strong></td>
                                <td>{{ $product->stock }}</td>
                                <td>{{ $product->units_sold }}</td>
                                <td><span class="badge badge-blue">{{ $product->demand_label }}</span></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="muted">Demand signals will appear after products or orders exist.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <section class="panel">
            <div class="section-title">
                <h2>Best-Selling Products</h2>
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
                        @forelse($bestSellingProducts as $product)
                            <tr>
                                <td><strong>{{ $product->name }}</strong></td>
                                <td>{{ $product->category ?: 'Uncategorized' }}</td>
                                <td>{{ $product->units_sold }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="muted">No sales data yet.</td>
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
                <h2>Restock Recommendations</h2>
            </div>

            <div class="table-wrap">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Stock</th>
                            <th>Reorder Level</th>
                            <th>Suggested Qty</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($restockRecommendations as $product)
                            <tr>
                                <td><strong>{{ $product->name }}</strong></td>
                                <td><span class="badge badge-red">{{ $product->stock }}</span></td>
                                <td>{{ $product->reorder_level ?? 5 }}</td>
                                <td>{{ $product->recommended_restock }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="muted">No restock recommendations right now.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <section class="panel">
            <div class="section-title">
                <h2>Slow-Moving Products</h2>
            </div>

            <div class="table-wrap">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Stock</th>
                            <th>Units Sold</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($slowMovingProducts as $product)
                            <tr>
                                <td><strong>{{ $product->name }}</strong></td>
                                <td>{{ $product->stock }}</td>
                                <td>{{ $product->units_sold }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="muted">No slow-moving products detected.</td>
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
                <h2>Risk Alerts</h2>
            </div>

            <div class="table-wrap">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Level</th>
                            <th>Signal</th>
                            <th>Details</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($riskAlerts as $alert)
                            <tr>
                                <td>
                                    <span class="badge {{ $alert['level'] === 'High' ? 'badge-red' : ($alert['level'] === 'Medium' ? 'badge-yellow' : 'badge-green') }}">
                                        {{ $alert['level'] }}
                                    </span>
                                </td>
                                <td><strong>{{ $alert['title'] }}</strong></td>
                                <td>{{ $alert['detail'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>

        <section class="panel">
            <div class="section-title">
                <h2>Admin Recommendations</h2>
            </div>

            <div class="grid">
                @foreach($recommendations as $recommendation)
                    <div style="border:1px solid var(--border);border-radius:var(--radius);padding:14px;color:var(--text-secondary);">
                        {{ $recommendation }}
                    </div>
                @endforeach
            </div>
        </section>
    </div>
@endsection
