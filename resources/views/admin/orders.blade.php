@extends('layouts.admin')

@section('title', 'Orders')
@section('page-title', 'Orders')
@section('page-subtitle', 'Review orders and update fulfillment status')

@section('content')
    <section class="panel">
        <div class="section-title">
            <h2>All Orders</h2>
            <form method="GET" action="{{ route('admin.orders.index') }}" class="actions">
                <input name="search" value="{{ request('search') }}" placeholder="Search order or customer" style="width:240px;">
                <select name="status" style="width:160px;">
                    <option value="">All statuses</option>
                    @foreach(['pending', 'processing', 'packed', 'out_for_delivery', 'completed', 'cancelled'] as $status)
                        <option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst($status) }}</option>
                    @endforeach
                </select>
                <button class="btn btn-primary" type="submit">Filter</button>
            </form>
        </div>

        <div class="table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Order</th>
                        <th>Customer</th>
                        <th>Status</th>
                        <th>Payment</th>
                        <th>Total</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td><strong>{{ $order->order_number }}</strong></td>
                            <td>{{ $order->user?->name ?? 'Guest' }}</td>
                            <td>
                                @php
                                    $statusClass = match($order->status) {
                                        'delivered', 'completed' => 'badge-green',
                                        'cancelled' => 'badge-red',
                                        'pending' => 'badge-yellow',
                                        default => 'badge-blue',
                                    };
                                @endphp
                                <span class="badge {{ $statusClass }}">{{ ucfirst(str_replace('_', ' ', $order->status)) }}</span>
                            </td>
                            <td>{{ strtoupper(str_replace('_', ' ', $order->payment_method ?? 'n/a')) }}</td>
                            <td>₱{{ number_format($order->total, 2) }}</td>
                            <td>{{ $order->created_at?->format('M d, Y') }}</td>
                            <td>
                                <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-secondary">View</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="muted">No orders found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pagination">
            {{ $orders->links() }}
        </div>
    </section>
@endsection
