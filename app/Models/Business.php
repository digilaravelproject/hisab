<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Business extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'type',
        'standard_income',
        'standard_expense',
        'auto_tag_transactions',
        'active',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    protected $casts = [
        'standard_income' => 'decimal:2',
        'standard_expense' => 'decimal:2',
        'auto_tag_transactions' => 'boolean',
        'active' => 'boolean',
    ];
}

