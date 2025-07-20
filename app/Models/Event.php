<?php

namespace App\Models;

use App\Models\Traits\ResourceScopeFilterSearchByName;
use App\View\Components\Admin\Form\Event\Days;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    use HasFactory;
    use ResourceScopeFilterSearchByName;

    protected $fillable = ['group_id', 'name', 'recurring', 'days', 'occurrence', 'starting_at', 'ending_at', 'recurring_until', 'note'];

    /**
     * The attributes that should be cast.
     * https://laravel.com/docs/8.x/eloquent-mutators#attribute-casting
     *
     * @var array<string, string>
     */
    protected $casts = [
        'group_id' => 'int',
        'recurring' => 'bool',
        'days' => 'array',
        'starting_at' => 'datetime',
        'ending_at' => 'datetime',
        'recurring_until' => 'datetime',
    ];

    public function calendarEvents(): HasMany
    {
        return $this->hasMany(CalendarEvent::class);
    }

    public function group(): belongsTo
    {
        return $this->belongsTo(Group::class);
    }

    /**
     * Convert day numbers to day names in array
     *
     * @return array
     */
    public function getDaysAsNames()
    {
        if (empty($this->days)) {
            return [];
        }

        $days = Days::getDaysOptions();

        return array_map(
            fn($key) => $days[$key],
            $this->days
        );
    }

    /**
     * Convert 0 to null as this is a nullable foreign_id
     * @param int $groupId
     *
     * @return void
     */
    public function setGroupIdAttribute(int $groupId)
    {
        if ($groupId) {
            $this->attributes['group_id'] = $groupId;
            return;
        }

        $this->attributes['group_id'] = null;
    }

    /**
     * Return 0 if the value is null, or return the value
     * @param int|null $groupId
     *
     * @return null|int
     */
    public function getGroupIdAttribute($groupId)
    {
        if (! $groupId) {
            return 0;
        }

        return $groupId;
    }
}
