<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;

class Event extends Model
{
    use HasFactory;

    protected $observerData;

    protected $fillable = ['name', 'recurring', 'days', 'occurrence', 'starting_at', 'ending_at', 'note'];

    // Always eager load with these
    protected $with = ['singleEvent', 'recurringEvent'];

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
    ];

    public function setObserverData($data)
    {
        $this->observerData = $data;
    }

    public function getObserverData($data)
    {
        return $this->observerData = $data;
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
