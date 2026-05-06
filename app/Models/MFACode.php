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

    public function isValid(): bool
    {
        return !$this->used && now()->lessThan($this->expires_at);
    }

    public function markAsUsed(): void
    {
        $this->update(['used' => true]);
    }
}
