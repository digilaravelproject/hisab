<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens; // ← MOST IMPORTANT LINE

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes; // ← HasApiTokens first

    protected $fillable = [
        'name',
        'mobile',
        'profile_photo',
        'gender',
        'user_types',
        'reminder_time',
        'is_active',
    ];

    protected $hidden = [
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'user_types' => 'array',
            'is_active'  => 'boolean',
            'is_admin'   => 'boolean',
        ];
    }

    // ── Relationships ──
    public function transactions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function businesses(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Business::class);
    }

    public function budgets(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Budget::class);
    }

    public function bills(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Bill::class);
    }

    public function settings(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(UserSetting::class);
    }

    public function settingsOrCreate(): UserSetting
    {
        return $this->settings()->firstOrCreate([
            'user_id' => $this->id,
        ], [
            'notifications_enabled' => false,
            'biometric_enabled'    => false,
        ]);
    }
}
