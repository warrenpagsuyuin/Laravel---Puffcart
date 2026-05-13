@extends('layouts.admin')

@section('title', 'Orders')
@section('page-title', 'Orders')
@section('page-subtitle', 'Review orders and update fulfillment status')

@push('styles')
    <style>
        .order-filter-form {
            align-items: center;
            display: grid;
            gap: 10px;
            grid-template-columns: minmax(220px, 1fr) 170px 112px;
            width: min(100%, 560px);
        }

        .order-filter-form input,
        .order-filter-form select {
            min-height: 42px;
        }

        .order-filter-form input.is-searching {
            background-image: linear-gradient(90deg, transparent, rgba(11, 102, 255, 0.08), transparent);
            background-size: 220% 100%;
            animation: orderSearchPulse 0.9s ease infinite;
        }

        @keyframes orderSearchPulse {
            0% {
                background-position: 160% 0;
            }

            100% {
                background-position: -60% 0;
            }
        }

        @media (max-width: 780px) {
            .section-title {
                align-items: stretch;
                flex-direction: column;
                gap: 12px;
            }

            .order-filter-form {
                grid-template-columns: 1fr;
                width: 100%;
            }
        }
    </style>
@endpush

@section('content')
    <section class="panel">
        <div class="section-title">
            <h2>All Orders</h2>
            <form id="order-filter-form" method="GET" action="{{ route('admin.orders.index') }}" class="order-filter-form">
                <input id="order-search" name="search" value="{{ request('search') }}" placeholder="Search order or customer" autocomplete="off">
                <select name="status" aria-label="Filter orders by status">
                    <option value="">All statuses</option>
                    @foreach(['pending', 'processing', 'packed', 'out_for_delivery', 'completed', 'cancelled'] as $status)
                        <option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst($status) }}</option>
                    @endforeach
                </select>
                <select name="per_page" aria-label="Orders per page">
                    <option value="5" @selected((int) request('per_page', 5) === 5)>5 rows</option>
                    <option value="10" @selected((int) request('per_page', 5) === 10)>10 rows</option>
                </select>
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

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.getElementById('order-filter-form');
            const search = document.getElementById('order-search');

            if (!form) {
                return;
            }

            let timer = null;

            function submitOrderFilters(delay = 0) {
                window.clearTimeout(timer);

                if (search && delay > 0) {
                    search.classList.add('is-searching');
                }

                timer = window.setTimeout(() => {
                    form.requestSubmit();
                }, delay);
            }

            search?.addEventListener('input', () => submitOrderFilters(450));

            form.querySelectorAll('select').forEach((select) => {
                select.addEventListener('change', () => submitOrderFilters());
            });
        });
    </script>
@endpush
