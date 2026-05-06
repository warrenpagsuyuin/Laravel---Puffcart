<?php

namespace App\Services;

use App\Models\AuditLog;
use App\Models\User;

class AuthenticationService
{
    protected const MAX_FAILED_ATTEMPTS = 3;
    protected const LOCKOUT_DURATION_MINUTES = 30;

    /**
     * Record failed login attempt
     */
    public function recordFailedAttempt(User $user, string $ipAddress, string $userAgent): array
    {
        $user->increment('failed_login_attempts');
        $user->update(['last_failed_login_at' => now()]);

        // Log the failed attempt
        AuditLog::log(
            'admin_login_failed',
            "Failed admin login attempt (attempt {$user->failed_login_attempts})",
            $user->id,
            $ipAddress,
            $userAgent
        );

        $attempts = $user->failed_login_attempts;

        // Lock account after MAX_FAILED_ATTEMPTS
        if ($attempts >= self::MAX_FAILED_ATTEMPTS) {
            $lockoutTime = now()->addMinutes(self::LOCKOUT_DURATION_MINUTES);
            $user->update(['locked_until' => $lockoutTime]);

            AuditLog::log(
                'account_locked',
                "Account locked due to {$attempts} failed login attempts",
                $user->id,
                $ipAddress,
                $userAgent
            );

            $remainingTime = $lockoutTime->diffInSeconds(now());

            return [
                'locked' => true,
                'message' => "Account locked due to too many failed login attempts. Try again in " . $this->formatSeconds($remainingTime),
                'remaining_seconds' => $remainingTime,
            ];
        }

        $remainingAttempts = self::MAX_FAILED_ATTEMPTS - $attempts;

        return [
            'locked' => false,
            'message' => "Invalid credentials. {$remainingAttempts} attempt" . ($remainingAttempts > 1 ? 's' : '') . " remaining.",
            'remaining_attempts' => $remainingAttempts,
        ];
    }

    /**
     * Reset failed login attempts on successful login
     */
    public function resetFailedAttempts(User $user): void
    {
        $user->update([
            'failed_login_attempts' => 0,
            'last_failed_login_at' => null,
            'locked_until' => null,
        ]);
    }

    /**
     * Check if account is locked
     */
    public function isAccountLocked(User $user): array
    {
        if (!$user->isLocked()) {
            return ['locked' => false];
        }

        $remainingTime = $user->locked_until->diffInSeconds(now());

        return [
            'locked' => true,
            'message' => "Account is locked. Try again in " . $this->formatSeconds($remainingTime),
            'remaining_seconds' => $remainingTime,
        ];
    }

    /**
     * Unlock account manually (admin action)
     */
    public function unlockAccount(User $user, ?User $performedBy = null, string $ipAddress = null, string $userAgent = null): void
    {
        $user->unlock();

        AuditLog::log(
            'account_unlocked',
            "Account unlocked by " . ($performedBy ? $performedBy->email : 'system'),
            $performedBy?->id,
            $ipAddress,
            $userAgent
        );
    }

    /**
     * Format seconds to human-readable format
     */
    protected function formatSeconds(int $seconds): string
    {
        $minutes = (int) ($seconds / 60);
        $remainingSeconds = $seconds % 60;

        if ($minutes > 0) {
            return "{$minutes} minute" . ($minutes > 1 ? 's' : '');
        }

        return "{$remainingSeconds} second" . ($remainingSeconds > 1 ? 's' : '');
    }
}
