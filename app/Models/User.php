<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'role',
        'phone',
        'address',
        'date_of_birth',
        'valid_id_path',
        'age_verified',
        'age_confirmed',
        'privacy_consent',
        'verification_status',
        'verification_reviewed_at',
        'is_active',
        'failed_login_attempts',
        'last_failed_login_at',
        'mfa_enabled',
        'locked_until',
        'email_verified_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'age_verified' => 'boolean',
        'age_confirmed' => 'boolean',
        'privacy_consent' => 'boolean',
        'verification_reviewed_at' => 'datetime',
        'is_active' => 'boolean',
        'last_failed_login_at' => 'datetime',
        'mfa_enabled' => 'boolean',
        'locked_until' => 'datetime',
        'email_verified_at' => 'datetime',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function mfaCodes()
    {
        return $this->hasMany(MFACode::class);
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if account is locked
     */
    public function isLocked(): bool
    {
        return $this->locked_until && now()->lessThan($this->locked_until);
    }

    /**
     * Unlock the account
     */
    public function unlock(): void
    {
        $this->update([
            'failed_login_attempts' => 0,
            'last_failed_login_at' => null,
            'locked_until' => null,
        ]);
    }
}
