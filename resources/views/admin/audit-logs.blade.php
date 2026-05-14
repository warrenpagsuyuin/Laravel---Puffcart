@extends('layouts.admin')

@section('title', 'Audit Logs')
@section('page-title', 'Audit Logs')

@section('content')
    <style>
        .audit-filter-panel {
            padding: 18px 20px;
        }

        .audit-filter-header {
            align-items: center;
            display: flex;
            gap: 16px;
            justify-content: space-between;
            margin-bottom: 16px;
        }

        .audit-filter-header h2 {
            font-size: 20px;
            margin: 0;
        }

        .audit-filter-header .muted {
            font-size: 13px;
        }

        .audit-filter-grid {
            align-items: end;
            display: grid;
            gap: 14px;
            grid-template-columns: minmax(260px, 1.4fr) minmax(190px, 0.8fr) minmax(160px, 0.6fr) minmax(160px, 0.6fr) auto;
        }

        .audit-filter-grid .form-group {
            display: grid;
            gap: 6px;
        }

        .audit-filter-grid label {
            color: var(--text-muted);
            font-size: 12px;
            font-weight: 800;
            letter-spacing: 0.02em;
            text-transform: uppercase;
        }

        .audit-filter-grid input,
        .audit-filter-grid select {
            min-height: 42px;
        }

        .audit-reset {
            min-height: 42px;
            white-space: nowrap;
        }

        @media (max-width: 1100px) {
            .audit-filter-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .audit-reset {
                justify-self: start;
            }
        }

        @media (max-width: 640px) {
            .audit-filter-header {
                align-items: flex-start;
                flex-direction: column;
            }

            .audit-filter-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>

    {{-- Filters --}}
    <section class="panel audit-filter-panel">
        <div class="audit-filter-header">
            <div>
                <h2>Filters</h2>
                <div class="muted">Results update automatically as you type or change filters.</div>
            </div>
        </div>

        <form id="audit-filter-form" method="GET" action="{{ route('admin.audit-logs.index') }}">

            <div class="audit-filter-grid">

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

                <a
                    href="{{ route('admin.audit-logs.index') }}"
                    class="btn btn-secondary audit-reset"
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

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('audit-filter-form');
            if (!form) return;

            let searchTimer = null;

            function submitFilters(delay = 0) {
                window.clearTimeout(searchTimer);
                searchTimer = window.setTimeout(function () {
                    form.requestSubmit();
                }, delay);
            }

            form.querySelectorAll('input[type="text"]').forEach(function (input) {
                input.addEventListener('input', function () {
                    submitFilters(450);
                });
            });

            form.querySelectorAll('select, input[type="date"]').forEach(function (field) {
                field.addEventListener('change', function () {
                    submitFilters();
                });
            });
        });
    </script>

@endsection
