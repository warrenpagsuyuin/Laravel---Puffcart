@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
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