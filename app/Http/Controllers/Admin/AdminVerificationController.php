<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class AdminVerificationController extends Controller
{
    public function index()
    {
        $pendingUsers = User::where('role', 'customer')
            ->where('verification_status', 'pending')
            ->latest()
            ->paginate(15);

        return view('admin.verifications', compact('pendingUsers'));
    }

    public function show(User $user)
    {
        return view('admin.verifications-show', compact('user'));
    }

    public function approve(User $user)
    {
        $user->update([
            'verification_status' => 'approved',
            'verification_reviewed_at' => now(),
            'age_verified' => true,
            'is_active' => true,
        ]);

        return back()->with('success', 'Age verification approved.');
    }

    public function reject(User $user)
    {
        $user->update([
            'verification_status' => 'rejected',
            'verification_reviewed_at' => now(),
            'age_verified' => false,
        ]);

        return back()->with('success', 'Age verification rejected.');
    }
}