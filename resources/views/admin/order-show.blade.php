@extends('layouts.admin')

@section('title', 'Order Details')
@section('page-title', 'Order Details')
@section('page-subtitle', $order->order_number)

@section('actions')
    <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">Back to Orders</a>
@endsection

@section('content')
    <div class="grid grid-2">
        <section class="panel">
            <div class="section-title">
                <h2>Order Summary</h2>
            </div>

            <table class="admin-table" style="min-width:0;">
                <tbody>
                    <tr>
                        <td><strong>Customer</strong></td>
                        <td>{{ $order->user?->name ?? 'Guest' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Email</strong></td>
                        <td>{{ $order->user?->email ?? 'No email' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Phone</strong></td>
                        <td>{{ $order->delivery_phone }}</td>
                    </tr>
                    <tr>
                        <td><strong>Address</strong></td>
                        <td>{{ $order->delivery_address }}</td>
                    </tr>
                    <tr>
                        <td><strong>Payment</strong></td>
                        <td>{{ strtoupper(str_replace('_', ' ', $order->payment_method ?? 'n/a')) }}</td>
                    </tr>
                    <tr>
                        <td><strong>Total</strong></td>
                        <td>₱{{ number_format($order->total, 2) }}</td>
                    </tr>
                </tbody>
            </table>
        </section>

        <section class="panel">
            <div class="section-title">
                <h2>Update Status</h2>
            </div>

            <form method="POST" action="{{ route('admin.orders.status', $order) }}">
                @csrf
                @method('PATCH')

                <div class="form-group">
                    <label for="status">Status</label>
                    <select id="status" name="status" required>
                        @foreach(['pending', 'processing', 'shipped', 'delivered', 'cancelled'] as $status)
                            <option value="{{ $status }}" @selected($order->status === $status)>{{ ucfirst($status) }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group" style="margin-top:14px;">
                    <label for="message">Tracking Message</label>
                    <textarea id="message" name="message" placeholder="Optional tracking note"></textarea>
                </div>

                <button type="submit" class="btn btn-primary" style="margin-top:14px;">Update Order</button>
            </form>
        </section>
    </div>

    <section class="panel" style="margin-top:16px;">
        <div class="section-title">
            <h2>Items</h2>
        </div>

        <div class="table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($order->items as $item)
                        <tr>
                            <td><strong>{{ $item->product_name }}</strong></td>
                            <td>₱{{ number_format($item->price, 2) }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>₱{{ number_format($item->subtotal, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="muted">No order items recorded.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <section class="panel" style="margin-top:16px;">
        <div class="section-title">
            <h2>Tracking History</h2>
        </div>

        <div class="table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Status</th>
                        <th>Message</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($order->tracking as $tracking)
                        <tr>
                            <td><span class="badge badge-blue">{{ ucfirst(str_replace('_', ' ', $tracking->status)) }}</span></td>
                            <td>{{ $tracking->message }}</td>
                            <td>{{ $tracking->occurred_at?->format('M d, Y h:i A') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="muted">No tracking updates yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection
