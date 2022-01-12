<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Event extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'recurring', 'days', 'occurrence', 'starting_at', 'ending_at', 'recurring_until', 'note'];

    /**
     * The attributes that should be cast.
     * https://laravel.com/docs/8.x/eloquent-mutators#attribute-casting
     *
     * @var array<string, string>
     */
    protected $casts = [
        'recurring' => 'bool',
        'days' => 'array',
        'starting_at' => 'datetime',
        'ending_at' => 'datetime',
        'recurring_until' => 'datetime',
    ];

    /**
     * Used in the blade files for select field and in controllers for validation rules
     * @return array
     */
    public static function getOccurrenceOptions(): array
    {
        return [
            'daily' => __('Daily'),
            'weekly' => __('Weekly')
        ];
    }

    public function scopeWithAll($query)
    {
        $query->with('singleEvent', 'recurringEvent');
    }

    public function singleEvent(): HasOne
    {
        return $this->hasOne(SingleEvent::class);
    }

    public function recurringEvent(): HasMany
    {
        return $this->hasMany(RecurringEvent::class);
    }
}
