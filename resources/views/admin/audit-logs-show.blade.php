@extends('layouts.admin')

@section('content')
<div class="p-8">
    <div class="mb-6">
        <a href="{{ route('admin.audit-logs.index') }}" class="text-[#0066ff] hover:underline font-medium">
            ← Back to Audit Logs
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-lg p-8">
        <h1 class="text-3xl font-bold text-[#1a1a1a] mb-8">Audit Log Details</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div>
                <h3 class="text-sm font-semibold text-[#1a1a1a] mb-2">Action</h3>
                <p class="text-[#666666] mb-6">{{ str_replace('_', ' ', ucfirst($log->action)) }}</p>

                <h3 class="text-sm font-semibold text-[#1a1a1a] mb-2">User</h3>
                <p class="text-[#666666] mb-6">
                    @if ($log->user)
                        <a href="{{ route('admin.users.show', $log->user) }}" class="text-[#0066ff] hover:underline">
                            {{ $log->user->email }}
                        </a>
                    @else
                        <span>System</span>
                    @endif
                </p>

                <h3 class="text-sm font-semibold text-[#1a1a1a] mb-2">Date & Time</h3>
                <p class="text-[#666666] mb-6">{{ $log->created_at->format('F d, Y H:i:s') }}</p>

                <h3 class="text-sm font-semibold text-[#1a1a1a] mb-2">IP Address</h3>
                <p class="text-[#666666] mb-6">{{ $log->ip_address ?? 'N/A' }}</p>
            </div>

            <div>
                <h3 class="text-sm font-semibold text-[#1a1a1a] mb-2">User Agent</h3>
                <p class="text-[#666666] mb-6 text-xs break-all bg-[#f9f9f9] p-3 rounded border border-[#e0e0e0]">
                    {{ $log->user_agent ?? 'N/A' }}
                </p>

                <h3 class="text-sm font-semibold text-[#1a1a1a] mb-2">Description</h3>
                <p class="text-[#666666] bg-[#f9f9f9] p-3 rounded border border-[#e0e0e0]">
                    {{ $log->description ?? 'No additional details' }}
                </p>
            </div>
        </div>

        <div class="mt-8 pt-8 border-t border-[#e0e0e0]">
            <a href="{{ route('admin.audit-logs.index') }}" class="px-6 py-2 bg-[#0066ff] hover:bg-[#0052cc] text-white font-semibold rounded-lg transition">
                Back to Logs
            </a>
        </div>
    </div>
</div>
@endsection
