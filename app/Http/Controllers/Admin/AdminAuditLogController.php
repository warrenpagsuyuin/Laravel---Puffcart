<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class AdminAuditLogController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function index(Request $request)
    {
        $query = AuditLog::with('user');

        // Search by action
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('action', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%")
                ->orWhere('ip_address', 'like', "%{$search}%");
        }

        // Filter by action type
        if ($request->filled('action')) {
            $query->where('action', $request->input('action'));
        }

        // Filter by user
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->input('user_id'));
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->input('date_from'));
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->input('date_to'));
        }

        $logs = $query->latest('created_at')->paginate(50);

        // Get action types for filter dropdown
        $actions = AuditLog::distinct('action')
            ->pluck('action')
            ->sort()
            ->values();

        return view('admin.audit-logs', [
            'logs' => $logs,
            'actions' => $actions,
        ]);
    }

    public function show(AuditLog $auditLog)
    {
        return view('admin.audit-logs-show', ['log' => $auditLog]);
    }
}
