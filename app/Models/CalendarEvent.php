<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class CalendarEvent extends Model
{
    use HasFactory;

    protected $fillable = ['event_id', 'starting_at', 'ending_at', 'note'];

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

    protected $with = ['event'];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'calendar_event_users')
                    ->withPivot(['id', 'created_at', 'updated_at']);
    }
}
