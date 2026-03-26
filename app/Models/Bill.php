<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Bill extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'business_id',
        'title',
        'bill_type',
        'amount',
        'bill_date',
        'month',
        'year',
        'file_path',
        'file_type',
        'notes',
    ];

    protected $casts = [
        'bill_date' => 'date',
        'amount'    => 'decimal:2',
    ];

    // ── Relationships ──

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function business(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Business::class);
    }
}
