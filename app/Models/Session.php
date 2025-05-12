<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Session extends Model
{
    protected $table = "sessions";
    protected $fillable = [
        'id',
        'token',
        'user_id',
        'ip_address',
        'device_type',
        'user_agent',
        'payload',
        'expires_at',
        'forced_expires_at',
        'is_active',
        'last_activity',
        'location',
        'is_expired',
        'browser',
        'os',
        'is_mobile',
        'failed_attempts',
    ];
    /**
     * Relationship with the user
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}