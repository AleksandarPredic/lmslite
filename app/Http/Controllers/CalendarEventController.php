<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\RequestValidationRulesTrait;
use App\Models\CalendarEvent;
use App\Models\CalendarEventUserStatus;
use App\Models\Group;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class CalendarEventController extends Controller
{
    use RequestValidationRulesTrait;

    /**
     * Display the specified resource.
     *
     * @param CalendarEvent $calendarEvent
     *
     * @return View
     */
    public function show(CalendarEvent $calendarEvent)
    {
        $users = $calendarEvent->load('users')->users()->userDefaultSorting()->get();
        $userStatuses = $users->load('calendarEventStatuses')->map(fn($user) => $user->calendarEventStatuses->first())->toArray();
        $event = $calendarEvent->event;
        // This relationship cam be null as it is nullable in migration
        $group = $event->group ? $event->load('group')->group : null;
        $groupUsers = $group ? $group->load('users')->users()->userDefaultSorting()->get() : null;
        $groupUsersStatuses = $groupUsers->load('calendarEventStatuses')->map(fn($user) => $user->calendarEventStatuses->first())->toArray();
        // Collect all user ids to exclude form search, group and event users
        $exclude = $groupUsers ? $groupUsers->pluck('id')->toArray() : [];
        $exclude = $users->isNotEmpty() ? array_merge($exclude, $users->pluck('id')->toArray()) : $exclude;

        return view('admin.calendar-event.show', [
            'calendarEvent' => $calendarEvent,
            'users' => $users,
            'usersStatuses' => $userStatuses,
            'event' => $event,
            'group' => $group,
            'groupUsers' => $groupUsers,
            'groupUsersStatuses' => $groupUsersStatuses,
            'exclude' => $exclude,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  CalendarEvent  $calendarEvent
     * @return View
     */
    public function edit(CalendarEvent $calendarEvent)
    {
        return view('admin.calendar-event.edit', [
            'calendarEvent' => $calendarEvent->load('event')
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  CalendarEvent  $calendarEvent
     * @return RedirectResponse
     */
    public function update(CalendarEvent $calendarEvent)
    {
        $attributes = $this->validateSanitizeRequest();

        // Update observer will only be fired if the model is dirty
        $updated = $calendarEvent->update($attributes);

        if (! $updated) {
            // TODO: Add logger here
            return redirect()->back()->with(
                'admin.message.error',
                sprintf(
                    '[ERROR] Calendar event with id, %s could not be updated!',
                    $calendarEvent->id
                )
            );
        }

        return redirect()->back()->with(
            'admin.message.success',
            'Calendar event, updated!'
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // TODO: create delete one calendar event and test all cascade delete from DB
    }

    /**
     * Add user to the CalendarEvent relationship via pivot table CalendarEventUser
     *
     * @param ?Group $group
     * @param CalendarEvent $calendarEvent
     *
     * @return RedirectResponse
     */
    public function addUser(CalendarEvent $calendarEvent, ?Group $group): RedirectResponse
    {
        $attributes = \request()->validate([
            'user_id' => ['required', 'numeric', 'min:1']
        ]);

        $userId = $attributes['user_id'];

        $user = User::find($userId);

        if (! $user) {
            return redirect()->back()->with(
                'admin.message.error',
                '[ERROR] User you are trying to add doesn\'t exists in our records!'
            );
        }

        // Check if we already have this user to this event group, we will also exclude ids in resources/views/components/admin/user/add-user.blade.php
        if ($group->users()->find($userId)) {
            throw ValidationException::withMessages(
                ['user_id' => 'User is already in the Event group.']
            );
        }

        $calendarEventUser = $calendarEvent->addUser($user);

        if (! $calendarEventUser->wasRecentlyCreated) {
            throw ValidationException::withMessages(
                ['user_id' => 'User is already in the Event.']
            );
        }

        return back()->with(
            'admin.message.success',
            sprintf(
                'User %s added to the calendar event!',
                $user->name
            )
        );
    }

    /**
     * Remove the user relationship via the pivot table
     *
     * @param  User $user
     * @param  CalendarEvent $calendarEvent
     *
     * @return RedirectResponse
     */
    public function removeUser(CalendarEvent $calendarEvent, User $user): RedirectResponse
    {
        try {
            $calendarEvent->removeUser($user);

            return redirect()->back()->with(
                'admin.message.success',
                sprintf(
                    'User %s removed from this calendar event!',
                    $user->name
                )
            );

        } catch (\Throwable $exception) {
            // TODO: Add logger here with id of the user

            return redirect()->back()->with(
                'admin.message.error',
                sprintf(
                    '[ERROR] User %s could not be removed from this calendar event!',
                    $user->name
                )
            );
        }
    }

    /**
     * Add user status for this calendar event.
     *
     * @param CalendarEvent $calendarEvent
     * @param User $user
     *
     * @return RedirectResponse
     */
    public function updateUserStatus(CalendarEvent $calendarEvent, User $user)
    {
        $attributes = \request()->validate([
            'status' => ['nullable', Rule::in(CalendarEventUserStatus::getStatusEnumValues())],
            'info' => ['nullable', Rule::in(CalendarEventUserStatus::getInfoEnumValues())]
        ]);

        $status = $attributes['status'] ?? null;
        $info = $attributes['info'] ?? null;

        $calendarEvent->updateUserStatus($user, $status, $info);

        return redirect()->back()->with(
            'admin.message.success',
            sprintf(
                'User %s status changed!',
                $user->name
            )
        );
    }

    /**
     * Validate and sanitize request
     *
     * @return array
     *
     */
    protected function validateSanitizeRequest(): array
    {
        $attributes = request()->validate([
            'starting_at' => array_merge(['required'], $this->getStartingAtFieldRules()),
            'ending_at' => array_merge(['required'], $this->getEndingAtFieldRules()),
            'note' => array_merge(['nullable'], $this->getNoteFieldRules()),
        ]);

        // additional sanitization
        if (isset($attributes['note'])) {
            $attributes['note'] = strip_tags($attributes['note']);
        }

        return $attributes;
    }
}
