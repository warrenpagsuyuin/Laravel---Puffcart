<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $fillable = [
        'name',
        'email',
        'password',
        'date_of_birth',
        'valid_id_path',
        'age_confirmed',
        'privacy_consent',
        'verification_status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'age_confirmed' => 'boolean',
        'privacy_consent' => 'boolean',
    ];
}