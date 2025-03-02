<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Group extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'starting_at',
        'ending_at',
        'course_id',
        'note',
        'active',
        'price_1',
        'price_2',
    ];

    /**
     * The attributes that should be cast.
     * https://laravel.com/docs/8.x/eloquent-mutators#attribute-casting
     *
     * @var array<string, string>
     */
    protected $casts = [
        'starting_at' => 'datetime',
        'ending_at' => 'datetime',
        'active' => 'boolean',
    ];

    public function scopeOrderByName(Builder $query): Collection
    {
        return $query->orderBy('name')->get();
    }

    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_groups')->withPivot('id');
    }

    /**
     * Add the user to the group.
     * Adds a record in the pivot table
     *
     * @param User $user
     *
     * @return UserGroup
     */
    public function addUser(User $user)
    {
        // Prevent creating extra same users in hacking attempt
        return UserGroup::firstOrCreate([
            'group_id' => $this->id,
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
        $this->getGroupUser($user->id)->deleteOrFail();
    }

    /**
     * Get group user pivot table record
     *
     * @param $id
     *
     * @return UserGroup
     * @throws ModelNotFoundException
     */
    protected function getGroupUser($id)
    {
        return UserGroup::whereUserId($id)
                    ->whereGroupId($this->id)
                    ->firstOrFail();
    }

    /**
     * Convert 0 to null as this is a nullable foreign_id
     * @param int $courseId
     *
     * @return void
     */
    public function setCourseIdAttribute(int $courseId)
    {
        if ($courseId) {
            $this->attributes['course_id'] = $courseId;
            return;
        }

        $this->attributes['course_id'] = null;
    }

    /**
     * Return 0 if the value is null, or return the value
     * @param int|null $courseId
     *
     * @return null|int
     */
    public function getCourseIdAttribute($courseId)
    {
        if (! $courseId) {
            return 0;
        }

        return $courseId;
    }
}
