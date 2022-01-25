<?php

namespace App\Http\Controllers;

use App\Models\CalendarEvent;
use App\Models\CalendarEventUser;
use App\Models\Group;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CalendarEventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  CalendarEvent  $calendarEvent
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
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
}
