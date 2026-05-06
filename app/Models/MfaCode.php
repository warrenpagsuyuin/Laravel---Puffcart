<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MFACode extends Model
{
    protected $fillable = [
        'user_id',
        'code',
        'used',
        'expires_at',
    ];

    protected $casts = [
        'used' => 'boolean',
        'expires_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if the code is still valid and unused
     */
    public function isValid(): bool
    {
        return !$this->used && now()->lessThan($this->expires_at);
    }

    /**
     * Mark code as used
     */
    public function markAsUsed(): void
    {
        $this->update(['used' => true]);
    }
}
