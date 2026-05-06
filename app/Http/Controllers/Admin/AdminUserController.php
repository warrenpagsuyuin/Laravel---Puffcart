<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\User;
use App\Services\AuthenticationService;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    protected AuthenticationService $authService;

    public function __construct(AuthenticationService $authService)
    {
        $this->authService = $authService;
    }

    public function index(Request $request)
    {
        $users = User::where('role', 'customer')
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->search;

                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('username', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.users', compact('users'));
    }

    public function show(User $user)
    {
        $user->load(['orders' => fn ($query) => $query->latest()->take(10)]);

        return view('admin.user-show', compact('user'));
    }

    public function approve(User $user, Request $request)
    {
        $user->update([
            'verification_status' => 'approved',
            'verification_reviewed_at' => now(),
            'age_verified' => true,
            'is_active' => true,
        ]);

        AuditLog::log(
            'user_approved',
            "Customer {$user->email} approved",
            auth()->id(),
            $request->ip(),
            $request->userAgent()
        );

        return back()->with('success', 'Customer approved.');
    }

    public function reject(User $user, Request $request)
    {
        $user->update([
            'verification_status' => 'rejected',
            'verification_reviewed_at' => now(),
            'age_verified' => false,
        ]);

        AuditLog::log(
            'user_rejected',
            "Customer {$user->email} rejected",
            auth()->id(),
            $request->ip(),
            $request->userAgent()
        );

        return back()->with('success', 'Customer verification rejected.');
    }

    public function unlock(User $user, Request $request)
    {
        $this->authService->unlockAccount($user, auth()->user(), $request->ip(), $request->userAgent());

        return back()->with('success', 'Customer account unlocked.');
    }
}

