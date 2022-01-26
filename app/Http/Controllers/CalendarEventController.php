<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\RequestValidationRulesTrait;
use App\Models\CalendarEvent;
use App\Models\CalendarEventUser;
use App\Models\Group;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
        $calendarEvent = $calendarEvent->load('users');
        $users = $calendarEvent->users;
        $event = $calendarEvent->event->load('group');
        $group = $event->group ? $event->group->load('users') : null;
        $groupUsers = $group ? $group->users : null;
        $exclude = $groupUsers ? $groupUsers->pluck('id')->toArray() : null;

        return view('admin.calendar-event.show', [
            'calendarEvent' => $calendarEvent,
            'users' => $users,
            'event' => $event,
            'group' => $group,
            'groupUsers' => $groupUsers,
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

        // Check if we already have this user to this event group, we will also exclude ids in resources/views/components/admin/user/add-user.blade.php
        if ($group->users()->find($userId)) {
            throw ValidationException::withMessages(
                ['user_id' => 'User is already in the Event group.']
            );
        }

        CalendarEventUser::create([
            'calendar_event_id' => $calendarEvent->id,
            'user_id' => $userId
        ]);

        return back()->with(
            'admin.message.success',
            sprintf(
                'User %s added to the calendar event!',
                User::find($userId)->name
            )
        );
    }

    /**
     * Remove the user relationship via the pivot table
     *
     * @param  CalendarEventUser $calendarEventUser
     * @param  User $user
     *
     * @return RedirectResponse
     */
    public function removeUser(User $user, CalendarEventUser $calendarEventUser): RedirectResponse
    {
        try {
            $calendarEventUser->deleteOrFail();
            return redirect()->back()->with(
                'admin.message.success',
                sprintf(
                    'User %s removed successfully from this group!',
                    $user->name
                )
            );

        } catch (\Throwable $exception) {
            // TODO: Add logger here
            return redirect()->back()->with(
                'admin.message.error',
                sprintf(
                    '[ERROR] User with id %d could not be removed from this group!',
                    $user->id
                )
            );
        }
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
