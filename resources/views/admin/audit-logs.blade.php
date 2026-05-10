@extends('layouts.admin')

@section('title', 'Audit Logs')
@section('page-title', 'Audit Logs')

@section('content')

    {{-- Filters --}}
    <section class="panel">
        <div class="section-title">
            <h2>Filters</h2>
        </div>

        <form method="GET" action="{{ route('admin.audit-logs.index') }}">

            <div class="form-grid">

                <div class="form-group">
                    <label>Search</label>

                    <input
                        type="text"
                        name="search"
                        placeholder="Action, description, IP..."
                        value="{{ request('search') }}"
                    >
                </div>

                <div class="form-group">
                    <label>Action Type</label>

                    <select name="action">
                        <option value="">All Actions</option>

                        @foreach ($actions as $action)
                            <option
                                value="{{ $action }}"
                                {{ request('action') === $action ? 'selected' : '' }}
                            >
                                {{ str_replace('_', ' ', ucfirst($action)) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>From Date</label>

                    <input
                        type="date"
                        name="date_from"
                        value="{{ request('date_from') }}"
                    >
                </div>

                <div class="form-group">
                    <label>To Date</label>

                    <input
                        type="date"
                        name="date_to"
                        value="{{ request('date_to') }}"
                    >
                </div>

            </div>

            <div class="actions" style="margin-top:16px;">
                <button type="submit" class="btn btn-primary">
                    Apply Filters
                </button>

                <a
                    href="{{ route('admin.audit-logs.index') }}"
                    class="btn btn-secondary"
                >
                    Reset
                </a>
            </div>

        </form>
    </section>

    {{-- Activity Logs --}}
    <section class="panel" style="margin-top:16px;">

        <div class="section-title">
            <h2>Activity Logs</h2>
        </div>

        <div class="table-wrap">

            <table class="admin-table">

                <thead>
                    <tr>
                        <th>Date & Time</th>
                        <th>User</th>
                        <th>Action</th>
                        <th>Description</th>
                        <th>IP Address</th>
                        <th></th>
                    </tr>
                </thead>

                <tbody>

                    @forelse ($logs as $log)

                        <tr>

                            <td>
                                <strong>
                                    {{ $log->created_at->format('M d, Y') }}
                                </strong>

                                <div class="muted" style="font-size:12px; margin-top:2px;">
                                    {{ $log->created_at->format('h:i A') }}
                                </div>
                            </td>

                            <td>
                                @if ($log->user)
                                    <a
                                        href="{{ route('admin.users.show', $log->user) }}"
                                        style="font-weight:600;"
                                    >
                                        {{ $log->user->email }}
                                    </a>
                                @else
                                    <span class="muted">System</span>
                                @endif
                            </td>

                            <td>
                                @php
                                    $badgeClass = match($log->action) {
                                        'create', 'created' => 'badge-green',
                                        'update', 'updated' => 'badge-blue',
                                        'delete', 'deleted' => 'badge-red',
                                        default => 'badge-gray'
                                    };
                                @endphp

                                <span class="badge {{ $badgeClass }}">
                                    {{ ucfirst(str_replace('_', ' ', $log->action)) }}
                                </span>
                            </td>

                            <td style="max-width:320px;">
                                <div style="
                                    overflow:hidden;
                                    text-overflow:ellipsis;
                                    white-space:nowrap;
                                ">
                                    {{ $log->description ?: 'No description available.' }}
                                </div>
                            </td>

                            <td>
                                {{ $log->ip_address ?: 'N/A' }}
                            </td>

                            <td style="text-align:right;">
                                <a
                                    href="{{ route('admin.audit-logs.show', $log) }}"
                                    class="btn btn-secondary"
                                    style="
                                        min-height:34px;
                                        padding:6px 12px;
                                        font-size:13px;
                                    "
                                >
                                    View Details
                                </a>
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="6" class="muted">
                                No audit logs found.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        <div class="pagination">
            {{ $logs->links() }}
        </div>

    </section>

@endsection