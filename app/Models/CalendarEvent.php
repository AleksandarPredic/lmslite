<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
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

    /**
     * Parent event for which this represents one calendar dateTime instance
     *
     * @return BelongsTo
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Users added manually to the calendar event.
     * Not Parent event group users
     *
     * @return BelongsToMany
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'calendar_event_users')
                    ->withPivot(['id', 'created_at', 'updated_at']);
    }

    public function userStatuses(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'calendar_event_user_statuses')
                    ->withPivot(['id', 'status', 'info']);
    }

    /**
     * Add the user to the calendar event.
     * Adds a record in the pivot table
     * This is not connected to parent event group users, only for newly added users to this calendar event
     *
     * @param User $user
     *
     * @return CalendarEventUser
     */
    public function addUser(User $user)
    {
        // Prevent creating extra same users in hacking attempt
        return CalendarEventUser::firstOrCreate([
            'calendar_event_id' => $this->id,
            'user_id' => $user->id
        ]);
    }

    /**
     * Remove user from calendar event. Remove the record in the pivot relationship table.
     * This is not interacting with the parent event group users.
     *
     * @throws \Throwable
     */
    public function removeUser(User $user)
    {
        // Remove user from the event
        $this->getCalendarUser($user->id)->deleteOrFail();

        // Also remove user status as when we removed it, we don't need it anymore
        try {
            $UserStatus = $this->getCalendarUserStatus($user->id);
            $UserStatus->delete();
        } catch (ModelNotFoundException $exception) {
            // No action needed
        }
    }

    /**
     * @param $user
     *
     * @return CalendarEventUserStatus
     * @throws ModelNotFoundException
     */
    public function getUserStatus(User $user)
    {
        return $this->getCalendarUserStatus($user->id);
    }

    /**
     * Update calendar event user status, either user from parent event group or newly added user
     * Adds a record in the pivot table
     *
     * @param User $user
     * @param string $status
     * @param string $info
     *
     * @return CalendarEventUserStatus
     */
    public function updateUserStatus(User $user, ?string $status, ?string $info)
    {
        try {
            $UserStatus = $this->getCalendarUserStatus($user->id);
        } catch (ModelNotFoundException $exception) {
            $UserStatus = null;
        }

        $attributes = [
            'calendar_event_id' => $this->id,
            'user_id' => $user->id,
        ];

        // Add only if we need to update it, if we add null it will overwrite existing value
        if (! empty($status)) {
            $attributes['status'] = $status;
        }

        if (! empty($info)) {
            $attributes['info'] = $info;
        }

        if ($UserStatus) {
            $UserStatus->update($attributes);
            return $UserStatus;
        }

        return CalendarEventUserStatus::create($attributes);
    }

    /**
     * @param $id
     *
     * @return CalendarEventUserStatus
     * @throws ModelNotFoundException
     */
    protected function getCalendarUserStatus($id)
    {
        return CalendarEventUserStatus::whereUserId($id)
                         ->whereCalendarEventId($this->id)
                         ->firstOrFail();
    }

    /**
     * Get calendar event user pivot table record
     *
     * @param int $id User id
     *
     * @return CalendarEventUser
     * @throws ModelNotFoundException
     */
    protected function getCalendarUser($id)
    {
        return CalendarEventUser::whereUserId($id)
                 ->whereCalendarEventId($this->id)
                 ->firstOrFail();
    }
}
