<?php

// @formatter:off
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * App\Models\Course
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Database\Factories\CourseFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Course newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Course newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Course query()
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereUpdatedAt($value)
 */
	class Course extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Event
 *
 * @property int $id
 * @property string $name
 * @property bool $recurring
 * @property array|null $days
 * @property string|null $occurrence
 * @property \Illuminate\Support\Carbon $starting_at
 * @property \Illuminate\Support\Carbon $ending_at
 * @property string|null $note
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\RecurringEvent[] $recurringEvent
 * @property-read int|null $recurring_event_count
 * @property-read \App\Models\SingleEvent|null $singleEvent
 * @method static \Database\Factories\EventFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Event newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Event newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Event query()
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereDays($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereEndingAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereOccurrence($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereRecurring($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereStartingAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereUpdatedAt($value)
 */
	class Event extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\RecurringEvent
 *
 * @property int $id
 * @property int $event_id
 * @property \Illuminate\Support\Carbon $starting_at
 * @property \Illuminate\Support\Carbon $ending_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Event $event
 * @method static \Illuminate\Database\Eloquent\Builder|RecurringEvent newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RecurringEvent newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RecurringEvent query()
 * @method static \Illuminate\Database\Eloquent\Builder|RecurringEvent whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RecurringEvent whereEndingAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RecurringEvent whereEventId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RecurringEvent whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RecurringEvent whereStartingAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RecurringEvent whereUpdatedAt($value)
 */
	class RecurringEvent extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Role
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Role newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Role newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Role query()
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereUpdatedAt($value)
 */
	class Role extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\SingleEvent
 *
 * @property int $id
 * @property int $event_id
 * @property \Illuminate\Support\Carbon $starting_at
 * @property \Illuminate\Support\Carbon $ending_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Event $event
 * @method static \Illuminate\Database\Eloquent\Builder|SingleEvent newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SingleEvent newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SingleEvent query()
 * @method static \Illuminate\Database\Eloquent\Builder|SingleEvent whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SingleEvent whereEndingAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SingleEvent whereEventId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SingleEvent whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SingleEvent whereStartingAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SingleEvent whereUpdatedAt($value)
 */
	class SingleEvent extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \App\Models\UserRole|null $role
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Sanctum\PersonalAccessToken[] $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\UserFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 */
	class User extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\UserRole
 *
 * @property int $id
 * @property int $role_id
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|UserRole newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserRole newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserRole query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserRole whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserRole whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserRole whereRoleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserRole whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserRole whereUserId($value)
 */
	class UserRole extends \Eloquent {}
}

