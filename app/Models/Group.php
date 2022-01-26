<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Group extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'starting_at', 'ending_at', 'note'];

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

    public function scopeOrderByName(Builder $query): Collection
    {
        return $query->orderBy('name')->get();
    }

    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
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
}
