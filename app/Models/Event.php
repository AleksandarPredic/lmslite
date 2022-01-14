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
     *
     * @param bool $returnKeys
     *
     * @return array
     */
    public static function getOccurrenceOptions(bool $returnKeys = false): array
    {
        $options = [
            'daily' => __('Daily'),
            'weekly' => __('Weekly')
        ];

        if ($returnKeys) {
            return array_keys($options);
        }

        return $options;
    }

    public function scopeWithAll($query)
    {
        $query->with('calendarEvents');
    }

    public function calendarEvents(): HasMany
    {
        return $this->hasMany(CalendarEvent::class);
    }
}
