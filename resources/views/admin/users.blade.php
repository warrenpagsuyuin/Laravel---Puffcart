@extends('layouts.admin')

@section('title', 'Customers')
@section('page-title', 'Customers')
@section('page-subtitle', 'Search customers and review account verification status')

@push('styles')
<style>
    .walkin-modal {
        position: fixed;
        inset: 0;
        z-index: 1000;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 24px;
        background: rgba(15, 23, 42, 0.48);
    }

    .walkin-modal.is-open { display: flex; }

    .walkin-dialog {
        width: min(720px, 100%);
        max-height: min(720px, 90vh);
        overflow: hidden;
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 12px;
        box-shadow: 0 24px 70px rgba(15, 23, 42, 0.24);
        display: flex;
        flex-direction: column;
    }

    .walkin-dialog-header {
        padding: 20px 24px;
        border-bottom: 1px solid var(--border);
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 16px;
    }

    .walkin-dialog-header h3 {
        margin: 0 0 4px;
        font-size: 20px;
    }

    .walkin-dialog-body {
        padding: 20px 24px 24px;
        overflow-y: auto;
    }

    .walkin-metrics {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 10px;
        margin-bottom: 18px;
    }

    .walkin-metric {
        border: 1px solid var(--border);
        border-radius: 8px;
        padding: 12px;
        background: var(--bg-light);
    }

    .walkin-metric span {
        display: block;
        color: var(--text-muted);
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        margin-bottom: 4px;
    }

    .walkin-metric strong {
        color: var(--text-primary);
        font-size: 15px;
    }

    .walkin-item-list {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .walkin-item {
        border: 1px solid var(--border);
        border-radius: 8px;
        padding: 12px 14px;
        display: flex;
        justify-content: space-between;
        gap: 14px;
        background: #fff;
    }

    .walkin-item-title {
        font-weight: 700;
        color: var(--text-primary);
    }

    .walkin-item-variant {
        margin-top: 3px;
        font-size: 12px;
        color: var(--text-muted);
    }

    .walkin-close {
        min-width: 38px;
        min-height: 38px;
        border-radius: 8px;
        border: 1px solid var(--border);
        background: #fff;
        cursor: pointer;
        font-size: 20px;
        line-height: 1;
    }

    @media (max-width: 720px) {
        .walkin-metrics { grid-template-columns: 1fr; }
        .walkin-item { flex-direction: column; }
    }
</style>
@endpush

@section('content')
    <section class="panel">
        <div class="section-title">
            <h2>Customer Accounts</h2>
            <form method="GET" action="{{ route('admin.users.index') }}" class="actions">
                <input name="search" value="{{ request('search') }}" placeholder="Search name, email, username" style="width:280px;">
                <button class="btn btn-primary" type="submit">Search</button>
            </form>
        </div>

        <div class="table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Username</th>
                        <th>Verification</th>
                        <th>Joined</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td><strong>{{ $user->name }}</strong></td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->username ?: 'None' }}</td>
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
                            <td>{{ $user->created_at?->format('M d, Y') }}</td>
                            <td>
                                <div class="actions">
                                    <a href="{{ route('admin.users.show', $user) }}" class="btn btn-secondary">View</a>
                                    @if($user->verification_status === 'pending')
                                        <form method="POST" action="{{ route('admin.users.approve', $user) }}">
                                            @csrf
                                            <button type="submit" class="btn btn-primary">Approve</button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.users.reject', $user) }}">
                                            @csrf
                                            <button type="submit" class="btn btn-danger">Reject</button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="muted">No customers found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pagination">
            {{ $users->links() }}
        </div>
    </section>

    <section class="panel" style="margin-top: 20px;">
        <div class="section-title">
            <h2>Walk-In Customers</h2>
            <p class="muted">Names and emails from walk-in checkouts. These are not registered accounts.</p>
        </div>

        <div class="table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Orders</th>
                        <th>Total Spent</th>
                        <th>Last Purchase</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($walkInCustomers as $customer)
                        <tr>
                            <td><strong>{{ $customer->customer_name }}</strong></td>
                            <td>{{ $customer->customer_email }}</td>
                            <td>{{ number_format($customer->orders_count) }}</td>
                            <td>PHP {{ number_format($customer->total_spent, 2) }}</td>
                            <td>{{ $customer->last_order_at ? \Illuminate\Support\Carbon::parse($customer->last_order_at)->format('M d, Y h:i A') : 'None' }}</td>
                            <td>
                                <button type="button" class="btn btn-secondary" data-walkin-open="walkin-customer-{{ $loop->index }}">View</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="muted">No walk-in customers yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    @foreach($walkInCustomers as $customer)
        <div class="walkin-modal" id="walkin-customer-{{ $loop->index }}" aria-hidden="true">
            <div class="walkin-dialog" role="dialog" aria-modal="true" aria-labelledby="walkin-title-{{ $loop->index }}">
                <div class="walkin-dialog-header">
                    <div>
                        <h3 id="walkin-title-{{ $loop->index }}">{{ $customer->customer_name }}</h3>
                        <div class="muted">{{ $customer->customer_email }}</div>
                    </div>
                    <button type="button" class="walkin-close" data-walkin-close aria-label="Close">&times;</button>
                </div>

                <div class="walkin-dialog-body">
                    <div class="walkin-metrics">
                        <div class="walkin-metric">
                            <span>Orders</span>
                            <strong>{{ number_format($customer->orders_count) }}</strong>
                        </div>
                        <div class="walkin-metric">
                            <span>Total Spent</span>
                            <strong>PHP {{ number_format($customer->total_spent, 2) }}</strong>
                        </div>
                        <div class="walkin-metric">
                            <span>Last Purchase</span>
                            <strong>{{ $customer->last_order_at ? \Illuminate\Support\Carbon::parse($customer->last_order_at)->format('M d, Y h:i A') : 'None' }}</strong>
                        </div>
                    </div>

                    <div class="walkin-item-list">
                        @forelse($customer->ordered_items as $item)
                            <div class="walkin-item">
                                <div>
                                    <div class="walkin-item-title">{{ $item->name }}</div>
                                    @if($item->variants)
                                        <div class="walkin-item-variant">{{ $item->variants }}</div>
                                    @endif
                                </div>
                                <span class="badge badge-gray">x {{ $item->quantity }}</span>
                            </div>
                        @empty
                            <div class="muted">No items recorded.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endsection

@push('scripts')
<script>
    document.querySelectorAll('[data-walkin-open]').forEach((button) => {
        button.addEventListener('click', () => {
            const modal = document.getElementById(button.dataset.walkinOpen);
            modal?.classList.add('is-open');
            modal?.setAttribute('aria-hidden', 'false');
        });
    });

    document.querySelectorAll('.walkin-modal').forEach((modal) => {
        modal.addEventListener('click', (event) => {
            if (event.target === modal || event.target.hasAttribute('data-walkin-close')) {
                modal.classList.remove('is-open');
                modal.setAttribute('aria-hidden', 'true');
            }
        });
    });
</script>
@endpush
