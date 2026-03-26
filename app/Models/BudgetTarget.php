<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BudgetTarget extends Model
{
    use HasFactory;

    protected $fillable = [
        'budget_id',
        'old_amount',
        'new_amount',
        'reason',
        'changed_at',
    ];

    protected $casts = [
        'old_amount' => 'decimal:2',
        'new_amount' => 'decimal:2',
        'changed_at' => 'datetime',
    ];

    // ── Relationships ──

    public function budget(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Budget::class);
    }
}
