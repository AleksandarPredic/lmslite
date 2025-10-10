<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CalendarEventUserCompensation extends Model
{
    private const COMPENSATION_SEARCH_RANGE_IN_MONTHS_PAST = 3;
    private const COMPENSATION_SEARCH_RANGE_IN_MONTHS_FUTURE = 1;

    private const CALENDAR_EVENT_USER_STATUS_STATUSES_FOR_FREE_COMPENSATION = ['canceled'];
    private const CALENDAR_EVENT_USER_STATUS_STATUSES_FOR_PAID_COMPENSATION = ['no-show'];

    // Must specify table explicitly as Laravel has bug for name resolution for compensation
    protected $table = 'calendar_event_user_compensations';

    protected $fillable = [
        'calendar_event_user_status_id',
        'calendar_event_id',
        'user_id',
        'status',
        'free',
        'payment_completed'
    ];

    /**
     * Get all available status values
     *
     * @return array
     */
    public static function getStatusEnumValues(): array
    {
        return [
            'attended',
            'no-show',
        ];
    }

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

    /**
     * @return int
     */
    public static function getCompensationSearchRangeInMonthsPast()
    {
        return self::COMPENSATION_SEARCH_RANGE_IN_MONTHS_PAST;
    }

    /**
     * @return int
     */
    public static function getCompensationSearchRangeInMonthsFuture()
    {
        return self::COMPENSATION_SEARCH_RANGE_IN_MONTHS_FUTURE;
    }

    /**
     * @return string[]
     */
    public static function getCalendarEventUserStatusesForPaidCompensation() {
        return self::CALENDAR_EVENT_USER_STATUS_STATUSES_FOR_PAID_COMPENSATION;
    }

    /**
     * @param User $user
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getUserStatusesEligibleForCompensation($user)
    {
        return $user
             ->calendarEventStatuses()
             ->with(['calendarEvent', 'calendarEvent.event'])
             ->whereIn(
                 'status',
                 array_merge(
                     self::CALENDAR_EVENT_USER_STATUS_STATUSES_FOR_FREE_COMPENSATION,
                     self::CALENDAR_EVENT_USER_STATUS_STATUSES_FOR_PAID_COMPENSATION
                 )
             )
            // Filter statuses whose calendar events occurred between now and 3 months ago
             ->whereHas('calendarEvent', function ($query) {
                $query->where('starting_at', '<=', now()->addMonths(self::COMPENSATION_SEARCH_RANGE_IN_MONTHS_FUTURE))
                      ->where('starting_at', '>=', now()->subMonths(self::COMPENSATION_SEARCH_RANGE_IN_MONTHS_PAST));
            })
            // Exclude statuses which have any compensation relationships
            ->whereDoesntHave('compensations')
            ->get();
    }
}
