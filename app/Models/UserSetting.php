<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'notifications_enabled',
        'pin_code',
        'biometric_enabled',
        'daily_reminder_time',
        'weekly_budget_limit',
        'monthly_budget_limit',
    ];

    protected $casts = [
        'notifications_enabled' => 'boolean',
        'biometric_enabled' => 'boolean',
        'weekly_budget_limit' => 'decimal:2',
        'monthly_budget_limit' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
