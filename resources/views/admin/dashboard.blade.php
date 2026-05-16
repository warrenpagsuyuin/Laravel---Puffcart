@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
    <style>
        .dashboard-chart {
            display: grid;
            gap: 18px;
        }

        .chart-summary {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 16px;
            margin-bottom: 4px;
        }

        .chart-summary h2 {
            margin: 0 0 4px;
        }

        .chart-kpis {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            justify-content: flex-end;
        }

        .chart-kpi {
            padding: 8px 12px;
            border: 1px solid #dbe4ef;
            border-radius: 12px;
            background: #f8fafc;
            color: #53657d;
            font-size: 13px;
            font-weight: 700;
        }

        .chart-kpi strong {
            color: #0f172a;
        }

        .simple-chart {
            min-height: 230px;
            display: grid;
            grid-template-columns: repeat(7, minmax(54px, 1fr));
            gap: 14px;
            align-items: end;
            padding: 18px 10px 6px;
            border-radius: 16px;
            background:
                linear-gradient(180deg, rgba(11, 99, 246, 0.06), transparent 64%),
                repeating-linear-gradient(180deg, transparent 0 45px, #eef3f9 46px);
        }

        .chart-day {
            min-width: 0;
            display: grid;
            gap: 8px;
            align-items: end;
            text-align: center;
        }

        .chart-bars {
            height: 150px;
            display: flex;
            justify-content: center;
            align-items: end;
            gap: 5px;
        }

        .chart-bar {
            width: 18px;
            min-height: 8px;
            border-radius: 999px 999px 6px 6px;
            background: linear-gradient(180deg, #0b63f6, #084ec1);
            box-shadow: 0 10px 18px rgba(11, 99, 246, 0.18);
        }

        .chart-bar.orders {
            width: 10px;
            background: linear-gradient(180deg, #22c55e, #16a34a);
            box-shadow: 0 10px 18px rgba(34, 197, 94, 0.16);
        }

        .chart-day strong {
            color: #0f172a;
            font-size: 13px;
            line-height: 1;
        }

        .chart-day span {
            color: #8492a6;
            font-size: 12px;
        }

        .chart-legend {
            display: flex;
            gap: 16px;
            flex-wrap: wrap;
            color: #53657d;
            font-size: 13px;
            font-weight: 700;
        }

        .legend-dot {
            display: inline-block;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            margin-right: 6px;
            background: #0b63f6;
        }

        .legend-dot.orders {
            background: #22c55e;
        }

        @media (max-width: 900px) {
            .chart-summary {
                flex-direction: column;
            }

            .chart-kpis {
                justify-content: flex-start;
            }

            .simple-chart {
                overflow-x: auto;
                grid-template-columns: repeat(7, 70px);
            }
        }
    </style>

    <div class="grid grid-4">
        <div class="stat-card">
            <div class="stat-label">Total Products</div>
            <div class="stat-value">{{ number_format($totalProducts) }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Customers</div>
            <div class="stat-value">{{ number_format($totalCustomers) }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Pending Verifications</div>
            <div class="stat-value">{{ number_format($pendingVerifications) }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Revenue</div>
            <div class="stat-value">₱{{ number_format($totalRevenue, 2) }}</div>
        </div>
    </div>

    <div class="grid grid-2" style="margin-top:16px;">
        <div class="stat-card">
            <div class="stat-label">Total Orders</div>
            <div class="stat-value">{{ number_format($totalOrders) }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Low Stock Products</div>
            <div class="stat-value">{{ number_format($lowStockProducts->count()) }}</div>
        </div>
    </div>

    <section class="panel dashboard-chart" style="margin-top:16px;">
        <div class="chart-summary">
            <div>
                <h2>7-Day Snapshot</h2>
                <p class="muted">Daily revenue and order activity across online and walk-in sales.</p>
            </div>

            <div class="chart-kpis">
                <div class="chart-kpi">Revenue <strong>₱{{ number_format($dashboardChart['totalRevenue'] ?? 0, 2) }}</strong></div>
                <div class="chart-kpi">Orders <strong>{{ number_format($dashboardChart['totalOrders'] ?? 0) }}</strong></div>
            </div>
        </div>

        <div class="simple-chart" aria-label="Seven day dashboard graph">
            @foreach(($dashboardChart['items'] ?? collect()) as $day)
                @php
                    $revenueHeight = max(8, ((float) $day['revenue'] / ($dashboardChart['maxRevenue'] ?? 1)) * 150);
                    $ordersHeight = max(8, ((int) $day['orders'] / ($dashboardChart['maxOrders'] ?? 1)) * 150);
                @endphp
                <div class="chart-day" title="{{ $day['date'] }}: ₱{{ number_format($day['revenue'], 2) }} / {{ number_format($day['orders']) }} orders">
                    <div class="chart-bars">
                        <span class="chart-bar" style="height: {{ $revenueHeight }}px;"></span>
                        <span class="chart-bar orders" style="height: {{ $ordersHeight }}px;"></span>
                    </div>
                    <strong>{{ $day['label'] }}</strong>
                    <span>₱{{ number_format($day['revenue'], 0) }}</span>
                </div>
            @endforeach
        </div>

        <div class="chart-legend">
            <span><i class="legend-dot"></i>Revenue</span>
            <span><i class="legend-dot orders"></i>Orders</span>
        </div>
    </section>

    <div class="grid grid-2" style="margin-top:16px;">
        <section class="panel">
            <div class="section-title">
                <h2>Recent Orders</h2>
                <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">View all</a>
            </div>

            <div class="table-wrap">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Order</th>
                            <th>Customer</th>
                            <th>Status</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentOrders as $order)
                            <tr>
                                <td><strong>{{ $order->order_number }}</strong></td>
                                <td>{{ $order->user?->name ?? 'Guest' }}</td>
                                <td><span class="badge badge-blue">{{ ucfirst(str_replace('_', ' ', $order->status)) }}</span></td>
                                <td>₱{{ number_format($order->total, 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="muted">No orders yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <section class="panel">
            <div class="section-title">
                <h2>Recent Customers</h2>
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">View all</a>
            </div>

            <div class="table-wrap">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Verification</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentUsers as $user)
                            <tr>
                                <td><strong>{{ $user->name }}</strong></td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    @php
                                        $verificationClass = match($user->verification_status) {
                                            'approved' => 'badge-green',
                                            'rejected' => 'badge-red',
                                            default => 'badge-yellow',
                                        };
                                    @endphp
                                    <span class="badge {{ $verificationClass }}">{{ ucfirst($user->verification_status ?? 'pending') }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="muted">No customers yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>

    <section class="panel" style="margin-top:16px;">
        <div class="section-title">
            <h2>Low Stock Products</h2>
            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Manage products</a>
        </div>

        <div class="table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Category</th>
                        <th>Stock</th>
                        <th>Reorder Level</th>
                        <th>Price</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($lowStockProducts as $product)
                        <tr>
                            <td><strong>{{ $product->name }}</strong></td>
                            <td>{{ $product->category ?? 'Uncategorized' }}</td>
                            <td><span class="badge badge-red">{{ $product->stock }}</span></td>
                            <td>{{ $product->reorder_level ?? 5 }}</td>
                            <td>₱{{ number_format($product->price, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="muted">No low stock products.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection
