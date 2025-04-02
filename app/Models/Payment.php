<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'group_id',
        'amount',
        'payment_date',
        'payment_month',
        'payment_year',
        'note',
        'created_by_id',
    ];

    protected $casts = [
        'payment_date' => 'date',
        'payment_month' => 'integer',
        'payment_year' => 'integer',
        'amount' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }
}
