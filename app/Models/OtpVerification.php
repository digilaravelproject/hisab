<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OtpVerification extends Model
{
    protected $fillable = [
        'mobile',
        'otp',
        'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    /**
     * Check karo OTP expired hai ya nahi
     */
    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    /**
     * Scope: Sirf valid (non-expired) OTPs
     */
    public function scopeValid($query)
    {
        return $query->where('expires_at', '>', now());
    }
}
