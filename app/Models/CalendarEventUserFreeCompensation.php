<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CalendarEventUserFreeCompensation extends Model
{
    // Must specify table explicitly as Laravel has bug for name resolution for compensation
    protected $table = 'calendar_event_user_free_compensations';

    protected $fillable = [
        'calendar_event_user_status_id',
        'calendar_event_id',
        'user_id',
        'status'
    ];

    public function calendarEventUserStatus(): BelongsTo
    {
        return $this->belongsTo(CalendarEventUserStatus::class);
    }

    public function calendarEvent(): BelongsTo
    {
        return $this->belongsTo(CalendarEvent::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
