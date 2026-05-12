@extends('layouts.admin')

@section('title', 'Customer Details')
@section('page-title', 'Customer Details')

@section('actions')
    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Back to Customers</a>
@endsection

@section('content')
    <section class="panel">
        <div class="section-title">
            <h2>Account</h2>
        </div>

        <table class="admin-table" style="min-width:0;">
            <tbody>
                <tr>
                    <td><strong>Name</strong></td>
                    <td>{{ $user->name }}</td>
                </tr>
                <tr>
                    <td><strong>Email</strong></td>
                    <td>{{ $user->email }}</td>
                </tr>
                <tr>
                    <td><strong>Username</strong></td>
                    <td>{{ $user->username ?: 'None' }}</td>
                </tr>
                <tr>
                    <td><strong>Phone</strong></td>
                    <td>{{ $user->phone ?: 'None' }}</td>
                </tr>
                <tr>
                    <td><strong>Address</strong></td>
                    <td>{{ $user->address ?: 'None' }}</td>
                </tr>
            </tbody>
        </table>
    </section>

    <section class="panel" style="margin-top:16px;">
        <div class="section-title">
            <h2>Recent Orders</h2>
        </div>

        <div class="table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Order</th>
                        <th>Status</th>
                        <th>Total</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($user->orders as $order)
                        <tr>
                            <td><a href="{{ route('admin.orders.show', $order) }}"><strong>{{ $order->order_number }}</strong></a></td>
                            <td><span class="badge badge-blue">{{ ucfirst(str_replace('_', ' ', $order->status)) }}</span></td>
                            <td>₱{{ number_format($order->total, 2) }}</td>
                            <td>{{ $order->created_at?->format('M d, Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="muted">No orders recorded for this customer.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection