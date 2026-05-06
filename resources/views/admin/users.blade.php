@extends('layouts.admin')

@section('title', 'Customers')
@section('page-title', 'Customers')
@section('page-subtitle', 'Search customers and review account verification status')

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
@endsection
