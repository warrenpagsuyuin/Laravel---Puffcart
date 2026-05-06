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
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
}
