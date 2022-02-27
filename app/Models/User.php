<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'parent_1_name',
        'parent_1_phone',
        'parent_2_name',
        'parent_2_phone',
        'date_of_birth',
        'address',
        'school',
        'school_info',
        'sign_up_date',
        'active',
        'note',
        'thumbnail',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'date_of_birth' => 'datetime',
        'sign_up_date' => 'datetime'
    ];

    public function role(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'user_roles')->withPivot('id');
    }

    public function groups(): BelongsToMany
    {
        return $this->belongsToMany(Group::class, 'user_groups')->withPivot('id');
    }

    public function calendarEvents(): BelongsToMany
    {
        return $this->belongsToMany(CalendarEvent::class, 'calendar_event_users')->withPivot('id');
    }

    public function calendarEventStatuses(): HasMany
    {
        return $this->hasMany(CalendarEventUserStatus::class);
    }

    /**
     * Create user role
     *
     * @param int $roleId
     *
     * @return UserRole
     */
    public function createRole(int $roleId): UserRole
    {
        return UserRole::create([
            'role_id' => $roleId,
            'user_id' => $this->id
        ]);
    }

    /**
     * Update user role
     *
     * @param int $roleId
     *
     * @return bool
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function updateRole(int $roleId): bool
    {
        $role = UserRole::findOrFail($roleId);

        return $role->update([
            'role_id' => $roleId,
        ]);
    }

    /**
     * Return roles separated by comma, as string
     *
     * We don't yet support multiple roles, but in the future we might
     *
     * @return string
     */
    public function getRolesString()
    {
        return $this->role->implode('name', ', ');
    }

    /**
     * Sort users on many screens: group.show, calendarEvent.show, any other screen that displays user collection.
     * So using this we will always keep the same sorting on the whole app as the app user will have better experience.
     *
     * @param Builder $builder
     *
     * @return void
     */
    public function scopeUserDefaultSorting(Builder $builder)
    {
        $builder->orderBy('name', 'asc');
    }

    /**
     * Filter all active users
     *
     * @param Builder $builder
     *
     * @return void
     */
    public function scopeFilterByName(Builder $builder, ?string $name)
    {
        $builder->when($name, function ($builder, $name) {
            $builder->whereRaw('lower(name) like (?)',["%{$name}%"]);
        });
    }

    /**
     * Filter all active users
     *
     * @param Builder $builder
     *
     * @return void
     */
    public function scopeActiveUsers(Builder $builder)
    {
        $builder->whereActive(true);
    }

    /**
     * Filter all users except admin users
     *
     * @param Builder $builder
     *
     * @return void
     */
    public function scopeAllExceptAdmins(Builder $builder)
    {
        $builder->with('role')->whereHas('role', function ($query) {
            $query->where('role_id', '!=', 1);
        });
    }

    /**
     * Return user image src url
     *
     * @return string
     */
    public function imageSrcUrl()
    {
        // TODO: Add user image upload in the future and use this as a placeholder
        return asset('/images/user-placeholder.png');
    }

    /**
     * Return next calendar events for this user
     * We have two types here:
     * - CalendarEvent where user is added directly
     * - CalendarEvent where the user is added via group, via event group
     *
     * @param int $limit
     *
     * @return Collection
     */
    public function getUserNextEvents(int $limit)
    {
        return cache()->remember(
            "user.calendarEvents.{$this->id}.{$limit}",
            now()->addMinutes(30),
            function () use ($limit) {
                // Get events that this user is added outside event assigned group
                $calendarEvents = $this->calendarEvents()
                                        ->without('event')
                                       ->whereDate('starting_at', '>', now())
                                       ->orderBy('starting_at')
                                       ->limit($limit)
                                       ->get();

                // Get all user calendar events, if the group is assigned to an event. That means that this user will attent them
                $groupCalendarEvents = new Collection();
                if ($this->groups->isNotEmpty()) {
                    // Collect all events that has this group, as it has the user also
                    $events = new Collection();
                    foreach ($this->groups as $group) {
                        if ($group->events->isNotEmpty()) {
                            $events = $events->merge($group->events);
                        }

                    }

                    // We have events, now lets get next 5 calendar events
                    if ($events->isNotEmpty()) {
                        $groupCalendarEvents = CalendarEvent::without('event')
                                                            ->whereIn('event_id', $events->pluck('id')->toArray())
                                                            ->whereDate('starting_at', '>', now())
                                                            ->orderBy('starting_at')
                                                            ->limit(5)
                                                            ->get();
                    }
                }

                return $calendarEvents->merge($groupCalendarEvents)->sortBy('starting_at')->take($limit);
            }
        );
    }

    /**
     * Get user statuses for the last number of months
     *
     * @param int $months
     *
     * @return Builder[]|Collection|HasMany[]
     * @throws \Exception
     */
    public function getCalendarEventStatusesLastMonths(int $months)
    {
        return cache()->remember(
            "user.calendarEventsStatuses.{$this->id}.{$months}",
            now()->addMinutes(30),
            function () use ($months) {
                return $this->calendarEventStatuses()
                            ->with(['calendarEvents' => function($query) {
                                $query->without('event');
                            }])
                            ->whereHas('calendarEvents', function($query) use ($months) {
                                $query->whereDate('starting_at', '>', now()->subMonths(6));
                            })
                            ->get();
            }
        );
    }

}
