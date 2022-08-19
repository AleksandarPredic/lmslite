<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\RequestValidationRulesTrait;
use App\Models\CalendarEvent;
use App\Models\CalendarEventUserStatus;
use App\Models\Group;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
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
        $users = $calendarEvent->users()->userDefaultSorting()->get();
        $event = $calendarEvent->event;
        // This relationship cam be null as it is nullable in migration
        $group = $event->group;
        $groupUsers = $group ? $group->users()->userDefaultSorting()->get() : null;
        $usersStatuses = $calendarEvent->userStatuses ? $calendarEvent->userStatuses->map(fn($user) => $user->pivot)->toArray() : [];
        $userIdsWithAttendedStatus = array_map(
            fn($status) => $status['user_id'],
            array_filter(
                $usersStatuses,
                fn($status) => $status['status'] === 'attended' ?? false
            )
        );
        /**
         * Users that were in the group, have calendar event user status, but were removed from the group later.
         * We should display them as legacy users so the admin can see that this user attended this calendar event
         */
        $legacyUsers = new Collection();
        if ($calendarEvent->userStatuses->isNotEmpty()) {
            // First remove all user statusses that belong to the group current users
            if ($group && $group->users->isNotEmpty()) {
                $legacyUsers = $calendarEvent->userStatuses->filter(fn($user) => ! $group->users->find($user));
            }

            // Then remove all statuses that belong to the calendar event users
            if ($users->isNotEmpty()) {
                $legacyUsers = $legacyUsers->filter(fn($user) => ! $users->find($user));
            }

            // After this we have our legacy group users that had on the calendar event user status but were removed form the group later
        }

        // Collect all user ids to exclude form search, group and event users, but count in the group removed users also
        $exclude = $groupUsers ? $groupUsers->pluck('id')->toArray() : [];
        $exclude = $users->isNotEmpty() ? array_merge($exclude, $users->pluck('id')->toArray()) : $exclude;
        $exclude = $legacyUsers->isNotEmpty() ? array_merge($exclude, $legacyUsers->pluck('id')->toArray()) : $exclude;

        return view('admin.calendar-event.show', [
            'calendarEvent' => $calendarEvent,
            'users' => $users,
            'event' => $event,
            'group' => $group,
            'groupUsers' => $groupUsers,
            'legacyUsers' => $legacyUsers,
            'usersStatuses' => $usersStatuses,
            'userIdsWithAttendedStatus' => $userIdsWithAttendedStatus,
            'exclude' => $exclude,
            'numberOfusers' => count($exclude)
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
        $attributes = $this->validateSanitizeRequest($calendarEvent);

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
     * @param  CalendarEvent  $calendarEvent
     * @return RedirectResponse
     */
    public function destroy(CalendarEvent $calendarEvent)
    {
        $calendarEvent->delete();

        return redirect(route('admin.events.show', $calendarEvent->event->id))
            ->with('admin.message.success', "Calendar event deleted!");
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
     * Add user status for this calendar event
     * Triggered via ajax in resources/js/calendar-event/CalendarEventStatusUpdate.js
     *
     * @param CalendarEvent $calendarEvent
     * @param User $user
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateUserStatus(CalendarEvent $calendarEvent, User $user)
    {

        $attributes = \request()->validate([
            'status' => [Rule::in(CalendarEventUserStatus::getStatusEnumValues())],
            'info' => [Rule::in(CalendarEventUserStatus::getInfoEnumValues())]
        ]);

        $status = $attributes['status'] ?? null;
        $info = $attributes['info'] ?? null;

        $calendarEvent->updateUserStatus($user, $status, $info);

        return response()->json([
            'message' => 'Updated',
        ]);
    }

    /**
     * Validate and sanitize request
     *
     * @return array
     *
     */
    protected function validateSanitizeRequest(CalendarEvent $calendarEvent): array
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

        if ((new Carbon($attributes['starting_at'])) < $calendarEvent->event->starting_at) {
            throw ValidationException::withMessages(
                ['starting_at' => 'Please chose date and time after the parent event start.']
            );
        }

        return $attributes;
    }
}
