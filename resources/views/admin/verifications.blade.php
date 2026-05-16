@extends('layouts.admin')

@section('title', 'Age Verification')
@section('page-title', 'Age Verification')
@section('page-subtitle', 'Review uploaded IDs for pending customer accounts')

@section('content')
    <section class="panel">
        <div class="section-title">
            <h2>Pending Reviews</h2>
        </div>

        <div class="table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Customer</th>
                        <th>Email</th>
                        <th>Date of Birth</th>
                        <th>Valid ID</th>
                        <th>Submitted</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pendingUsers as $user)
                        <tr>
                            <td><strong>{{ $user->name }}</strong></td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->date_of_birth?->format('M d, Y') ?? 'None' }}</td>
                            <td>
                                @if($user->valid_id_path)
                                    <a href="{{ route('admin.verifications.document', $user) }}" target="_blank" rel="noopener" class="btn btn-secondary">Open ID</a>
                                @else
                                    <span class="muted">No file</span>
                                @endif
                            </td>
                            <td>{{ $user->created_at?->format('M d, Y') }}</td>
                            <td>
                                <div class="actions">
                                    <form method="POST" action="{{ route('admin.verifications.approve', $user) }}">
                                        @csrf
                                        <button type="submit" class="btn btn-primary">Approve</button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.verifications.reject', $user) }}">
                                        @csrf
                                        <button type="submit" class="btn btn-danger">Reject</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="muted">No pending age verifications.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pagination">
            {{ $pendingUsers->links() }}
        </div>
    </section>
@endsection
