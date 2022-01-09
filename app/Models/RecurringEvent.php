<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RecurringEvent extends Model
{
    use HasFactory;

    protected $fillable = ['event_id', 'starting_at', 'ending_at'];

    /**
     * The attributes that should be cast.
     * https://laravel.com/docs/8.x/eloquent-mutators#attribute-casting
     *
     * @var array<string, string>
     */
    protected $casts = [
        'starting_at' => 'datetime',
        'ending_at' => 'datetime',
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }
}
