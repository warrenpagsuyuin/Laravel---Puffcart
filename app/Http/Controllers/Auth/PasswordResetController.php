<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\PasswordResetMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Str;
use Throwable;

class PasswordResetController extends Controller
{
    public function showForgotForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $email = Str::lower((string) $request->email);
        $user = User::whereRaw('lower(email) = ?', [$email])->first();
        $genericResponse = back()->with('success', 'If this email belongs to an active account, a password reset link will be sent.');

        if (!$user || !$user->is_active) {
            Log::info('Password reset requested for non-existent or inactive account.', [
                'email_hash' => hash('sha256', $email),
            ]);

            return $genericResponse;
        }

        // Generate reset token
        $token = Str::random(64);

        // Store token in password_reset_tokens table
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $user->email],
            [
                'token' => Hash::make($token),
                'created_at' => now(),
            ]
        );

        try {
            Mail::to($user->email)->send(new PasswordResetMail($user, $token));
        } catch (Throwable $exception) {
            Log::error('Password reset email failed.', [
                'user_id' => $user->id,
                'email' => $user->email,
                'mailer' => config('mail.default'),
                'message' => $exception->getMessage(),
            ]);

            return $genericResponse;
        }

        return $genericResponse;
    }

    public function showResetForm($token)
    {
        return view('auth.reset-password', ['token' => $token]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'token' => 'required|string',
            'password' => [
                'required',
                'confirmed',
                Password::min(10)
                    ->mixedCase()
                    ->numbers()
                    ->symbols(),
            ],
        ]);

        $email = Str::lower((string) $request->email);
        $user = User::whereRaw('lower(email) = ?', [$email])->first();

        if (!$user || !$user->is_active) {
            return back()->with('error', 'This password reset link is invalid or has expired.');
        }

        // Find the reset token
        $resetRecord = DB::table('password_reset_tokens')
            ->where('email', $user->email)
            ->first();

        if (!$resetRecord || !Hash::check($request->token, $resetRecord->token)) {
            return back()->with('error', 'This password reset link is invalid or has expired.');
        }

        // Check if token is older than 1 hour
        if (now()->diffInMinutes($resetRecord->created_at) > 60) {
            DB::table('password_reset_tokens')->where('email', $user->email)->delete();
            return back()->with('error', 'This password reset link has expired.');
        }

        // Update user password
        $user->update(['password' => Hash::make($request->password)]);

        // Delete the reset token
        DB::table('password_reset_tokens')->where('email', $user->email)->delete();

        return redirect()->route('login')->with('success', 'Your password has been reset successfully. Please log in.');
    }
}
