<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
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
}
