@extends('layouts.admin')

@section('title', 'Audit Log Details')
@section('page-title', 'Audit Log Details')

@section('actions')
    <a href="{{ route('admin.audit-logs.index') }}" class="btn btn-secondary">
        Back to Audit Logs
    </a>
@endsection

@section('content')

    {{-- Log Information --}}
    <section class="panel">

        <div class="section-title">
            <h2>Log Information</h2>
        </div>

        <table class="admin-table" style="min-width:0;">
            <tbody>

                <tr>
                    <td><strong>Action</strong></td>

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
                </tr>

                <tr>
                    <td><strong>User</strong></td>

                    <td>
                        @if ($log->user)
                            <a href="{{ route('admin.users.show', $log->user) }}">
                                {{ $log->user->email }}
                            </a>
                        @else
                            <span class="muted">System</span>
                        @endif
                    </td>
                </tr>

                <tr>
                    <td><strong>Date & Time</strong></td>

                    <td>
                        {{ $log->created_at->format('F d, Y h:i:s A') }}
                    </td>
                </tr>

                <tr>
                    <td><strong>IP Address</strong></td>

                    <td>
                        {{ $log->ip_address ?: 'N/A' }}
                    </td>
                </tr>

                <tr>
                    <td><strong>User Agent</strong></td>

                    <td style="word-break:break-word;">
                        {{ $log->user_agent ?: 'N/A' }}
                    </td>
                </tr>

            </tbody>
        </table>

    </section>

    {{-- Description --}}
    <section class="panel" style="margin-top:16px;">

        <div class="section-title">
            <h2>Description</h2>
        </div>

        <div style="
            color: var(--text-secondary);
            line-height: 1.7;
            font-size: 14px;
        ">
            {{ $log->description ?: 'No additional details available.' }}
        </div>

    </section>

@endsection