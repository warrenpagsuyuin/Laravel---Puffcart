<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

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

    public function document(User $user)
    {
        abort_unless($user->role === 'customer', 404);
        abort_unless((bool) $user->valid_id_path, 404);

        $path = $user->valid_id_path;
        $disk = Storage::disk('local')->exists($path) ? 'local' : null;

        if (!$disk && Storage::disk('public')->exists($path)) {
            $disk = 'public';
        }

        abort_unless($disk, 404);

        return Storage::disk($disk)->response($path, null, [
            'Content-Disposition' => 'inline; filename="' . basename($path) . '"',
            'X-Content-Type-Options' => 'nosniff',
            'Cache-Control' => 'private, no-store',
        ]);
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
