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
 * App\Models\CalendarEvent
 *
 * @property int $id
 * @property int $event_id
 * @property \Illuminate\Support\Carbon|null $starting_at
 * @property \Illuminate\Support\Carbon|null $ending_at
 * @property string|null $note
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Event $event
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $userStatuses
 * @property-read int|null $user_statuses_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder|CalendarEvent newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CalendarEvent newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CalendarEvent query()
 * @method static \Illuminate\Database\Eloquent\Builder|CalendarEvent whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CalendarEvent whereEndingAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CalendarEvent whereEventId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CalendarEvent whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CalendarEvent whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CalendarEvent whereStartingAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CalendarEvent whereUpdatedAt($value)
 */
	class CalendarEvent extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\CalendarEventUser
 *
 * @property int $id
 * @property int $calendar_event_id
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|CalendarEventUser newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CalendarEventUser newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CalendarEventUser query()
 * @method static \Illuminate\Database\Eloquent\Builder|CalendarEventUser whereCalendarEventId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CalendarEventUser whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CalendarEventUser whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CalendarEventUser whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CalendarEventUser whereUserId($value)
 */
	class CalendarEventUser extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\CalendarEventUserStatus
 *
 * @property int $id
 * @property int $calendar_event_id
 * @property int $user_id
 * @property string|null $status
 * @property string|null $info
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\CalendarEvent[] $calendarEvents
 * @property-read int|null $calendar_events_count
 * @method static \Illuminate\Database\Eloquent\Builder|CalendarEventUserStatus newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CalendarEventUserStatus newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CalendarEventUserStatus query()
 * @method static \Illuminate\Database\Eloquent\Builder|CalendarEventUserStatus whereCalendarEventId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CalendarEventUserStatus whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CalendarEventUserStatus whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CalendarEventUserStatus whereInfo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CalendarEventUserStatus whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CalendarEventUserStatus whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CalendarEventUserStatus whereUserId($value)
 */
	class CalendarEventUserStatus extends \Eloquent {}
}

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
 * @method static \Illuminate\Database\Eloquent\Builder|Course orderByName()
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
 * @property null|int $group_id
 * @property string $name
 * @property bool $recurring
 * @property array|null $days
 * @property \Illuminate\Support\Carbon|null $starting_at
 * @property \Illuminate\Support\Carbon|null $ending_at
 * @property \Illuminate\Support\Carbon|null $recurring_until
 * @property string|null $note
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\CalendarEvent[] $calendarEvents
 * @property-read int|null $calendar_events_count
 * @property-read \App\Models\Group|null $group
 * @method static \Database\Factories\EventFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Event newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Event newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Event query()
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereDays($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereEndingAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereGroupId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereRecurring($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereRecurringUntil($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereStartingAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereUpdatedAt($value)
 */
	class Event extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Group
 *
 * @property int $id
 * @property null|int $course_id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $starting_at
 * @property \Illuminate\Support\Carbon|null $ending_at
 * @property string|null $note
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Course|null $course
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Event[] $events
 * @property-read int|null $events_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $users
 * @property-read int|null $users_count
 * @method static \Database\Factories\GroupFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Group newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Group newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Group orderByName()
 * @method static \Illuminate\Database\Eloquent\Builder|Group query()
 * @method static \Illuminate\Database\Eloquent\Builder|Group whereCourseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Group whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Group whereEndingAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Group whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Group whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Group whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Group whereStartingAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Group whereUpdatedAt($value)
 */
	class Group extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Role
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Role excludeAdminRole()
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
 * App\Models\User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property string|null $parent_1_name
 * @property string|null $parent_1_phone
 * @property string|null $parent_2_name
 * @property string|null $parent_2_phone
 * @property \Illuminate\Support\Carbon|null $date_of_birth
 * @property string|null $address
 * @property string|null $school
 * @property string|null $school_info
 * @property \Illuminate\Support\Carbon|null $sign_up_date
 * @property int $active
 * @property string|null $note
 * @property string|null $thumbnail
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\CalendarEventUserStatus[] $calendarEventStatuses
 * @property-read int|null $calendar_event_statuses_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\CalendarEvent[] $calendarEvents
 * @property-read int|null $calendar_events_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Group[] $groups
 * @property-read int|null $groups_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Role[] $role
 * @property-read int|null $role_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Sanctum\PersonalAccessToken[] $tokens
 * @property-read int|null $tokens_count
 * @method static \Illuminate\Database\Eloquent\Builder|User activeUsers()
 * @method static \Illuminate\Database\Eloquent\Builder|User allExceptAdmins()
 * @method static \Database\Factories\UserFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|User filterByName(?string $name)
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User userDefaultSorting()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereDateOfBirth($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereParent1Name($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereParent1Phone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereParent2Name($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereParent2Phone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereSchool($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereSchoolInfo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereSignUpDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereThumbnail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 */
	class User extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\UserGroup
 *
 * @property int $id
 * @property int $group_id
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|UserGroup newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserGroup newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserGroup query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserGroup whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserGroup whereGroupId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserGroup whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserGroup whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserGroup whereUserId($value)
 */
	class UserGroup extends \Eloquent {}
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

