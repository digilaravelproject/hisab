<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name',
        'mobile',
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
            'user_types'   => 'array',    // JSON column auto cast
            'is_active'    => 'boolean',
            'is_admin'     => 'boolean',
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
}
