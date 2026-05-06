<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
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

    public function approve(User $user)
    {
        $user->update([
            'verification_status' => 'approved',
            'verification_reviewed_at' => now(),
            'age_verified' => true,
            'is_active' => true,
        ]);

        return back()->with('success', 'Customer approved.');
    }

    public function reject(User $user)
    {
        $user->update([
            'verification_status' => 'rejected',
            'verification_reviewed_at' => now(),
            'age_verified' => false,
        ]);

        return back()->with('success', 'Customer verification rejected.');
    }
}
