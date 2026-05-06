@extends('layouts.admin')

@section('content')
<div class="p-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-[#1a1a1a]">Audit Logs</h1>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
        <form method="GET" action="{{ route('admin.audit-logs.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-[#1a1a1a] mb-2">Search</label>
                    <input
                        type="text"
                        name="search"
                        placeholder="Action, description, IP..."
                        value="{{ request('search') }}"
                        class="w-full px-4 py-2 border border-[#e0e0e0] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0066ff]"
                    />
                </div>

                <div>
                    <label class="block text-sm font-medium text-[#1a1a1a] mb-2">Action Type</label>
                    <select name="action" class="w-full px-4 py-2 border border-[#e0e0e0] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0066ff]">
                        <option value="">All Actions</option>
                        @foreach ($actions as $action)
                            <option value="{{ $action }}" {{ request('action') === $action ? 'selected' : '' }}>
                                {{ str_replace('_', ' ', ucfirst($action)) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-[#1a1a1a] mb-2">From Date</label>
                    <input
                        type="date"
                        name="date_from"
                        value="{{ request('date_from') }}"
                        class="w-full px-4 py-2 border border-[#e0e0e0] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0066ff]"
                    />
                </div>

                <div>
                    <label class="block text-sm font-medium text-[#1a1a1a] mb-2">To Date</label>
                    <input
                        type="date"
                        name="date_to"
                        value="{{ request('date_to') }}"
                        class="w-full px-4 py-2 border border-[#e0e0e0] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0066ff]"
                    />
                </div>
            </div>

            <div class="flex gap-2">
                <button
                    type="submit"
                    class="px-6 py-2 bg-[#0066ff] hover:bg-[#0052cc] text-white font-semibold rounded-lg transition"
                >
                    Filter
                </button>
                <a
                    href="{{ route('admin.audit-logs.index') }}"
                    class="px-6 py-2 bg-[#f9f9f9] hover:bg-[#e0e0e0] text-[#1a1a1a] font-semibold rounded-lg transition border border-[#e0e0e0]"
                >
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Logs Table -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-[#f9f9f9] border-b border-[#e0e0e0]">
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-[#1a1a1a]">Date & Time</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-[#1a1a1a]">User</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-[#1a1a1a]">Action</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-[#1a1a1a]">IP Address</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-[#1a1a1a]">Details</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($logs as $log)
                        <tr class="border-b border-[#e0e0e0] hover:bg-[#f9f9f9]">
                            <td class="px-6 py-4 text-sm text-[#666666]">
                                {{ $log->created_at->format('M d, Y h:i A') }}
                            </td>
                            <td class="px-6 py-4 text-sm text-[#1a1a1a]">
                                @if ($log->user)
                                    <a href="{{ route('admin.users.show', $log->user) }}" class="text-[#0066ff] hover:underline">
                                        {{ $log->user->email }}
                                    </a>
                                @else
                                    <span class="text-[#666666]">System</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <span class="inline-block px-3 py-1 bg-[#e6f0ff] text-[#0066ff] rounded-full text-xs font-semibold">
                                    {{ str_replace('_', ' ', ucfirst($log->action)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-[#666666]">
                                {{ $log->ip_address ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <a
                                    href="{{ route('admin.audit-logs.show', $log) }}"
                                    class="text-[#0066ff] hover:underline font-medium"
                                >
                                    View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-[#666666]">
                                No audit logs found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-[#e0e0e0]">
            {{ $logs->links() }}
        </div>
    </div>
</div>
@endsection
