<?php

namespace App\Services;

use App\Models\MFACode;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class MFAService
{
    /**
     * Generate and send MFA code to user
     */
    public function generateAndSendCode(User $user): void
    {
        // Generate 6-digit code
        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Hash the code before storing
        $hashedCode = Hash::make($code);

        // Create MFA code record (expires in 5 minutes)
        MFACode::create([
            'user_id' => $user->id,
            'code' => $hashedCode,
            'expires_at' => now()->addMinutes(5),
        ]);

        // Send code via email (implement email sending based on your mail driver)
        // For now, we'll just log it
        \Illuminate\Support\Facades\Log::info("MFA Code for user {$user->email}: {$code}");

        // In production, you would send an email:
        // Mail::send('emails.mfa-code', ['code' => $code, 'user' => $user], function ($mail) use ($user) {
        //     $mail->to($user->email)->subject('Your Puffcart MFA Code');
        // });
    }

    /**
     * Verify MFA code
     */
    public function verifyCode(User $user, string $code): bool
    {
        $mfaCode = MFACode::where('user_id', $user->id)
            ->where('used', false)
            ->orderByDesc('created_at')
            ->first();

        if (!$mfaCode) {
            return false;
        }

        if (!$mfaCode->isValid()) {
            return false;
        }

        if (!Hash::check($code, $mfaCode->code)) {
            return false;
        }

        // Mark code as used
        $mfaCode->markAsUsed();

        return true;
    }

    /**
     * Clean up expired codes
     */
    public function cleanupExpiredCodes(): void
    {
        MFACode::where('expires_at', '<', now())
            ->where('used', false)
            ->delete();
    }
}
