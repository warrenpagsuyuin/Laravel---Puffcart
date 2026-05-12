@extends('layouts.admin')

@section('title', 'Verification Details')
@section('page-title', 'Verification Details')

@section('actions')
    <a href="{{ route('admin.verifications.index') }}" class="btn btn-secondary">Back to Age Verification</a>
@endsection

@section('content')
    <div class="grid grid-2">
        <section class="panel">
            <div class="section-title">
                <h2>Customer</h2>
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
                </tbody>
            </table>
        </section>

        <section class="panel">
            <div class="section-title">
                <h2>Verification</h2>
            </div>

            <table class="admin-table" style="min-width:0;">
                <tbody>
                    <tr>
                        <td><strong>Status</strong></td>
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
                    <tr>
                        <td><strong>Date of Birth</strong></td>
                        <td>{{ $user->date_of_birth?->format('M d, Y') ?? 'None' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Valid ID</strong></td>
                        <td>
                            @if($user->valid_id_path)
                                <a href="{{ asset('storage/' . $user->valid_id_path) }}" target="_blank" class="btn btn-secondary">Open ID</a>
                            @else
                                <span class="muted">No file uploaded</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Submitted</strong></td>
                        <td>{{ $user->created_at?->format('M d, Y') }}</td>
                    </tr>
                    <tr>
                        <td><strong>Reviewed</strong></td>
                        <td>{{ $user->verification_reviewed_at?->format('M d, Y h:i A') ?? 'Not reviewed' }}</td>
                    </tr>
                </tbody>
            </table>

            <div class="actions" style="margin-top:16px;">
                <form method="POST" action="{{ route('admin.verifications.approve', $user) }}">
                    @csrf
                    <button type="submit" class="btn btn-primary">Approve</button>
                </form>
                <form method="POST" action="{{ route('admin.verifications.reject', $user) }}">
                    @csrf
                    <button type="submit" class="btn btn-danger">Reject</button>
                </form>
            </div>
        </section>
    </div>
@endsection