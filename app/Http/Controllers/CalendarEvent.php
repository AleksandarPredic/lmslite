<?php

namespace App\Http\Controllers;

use App\Models\CalendarEvent as CalendarEventModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class CalendarEvent extends Controller
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
     * @param  CalendarEventModel  $calendarEvent
     */
    public function show(CalendarEventModel $calendarEvent)
    {
        $calendarEvent = $calendarEvent->load('users');
        $event = $calendarEvent->event->load('group');
        $group = $event->group;
        // Users added independently to this calendar event
        $usersAdded = $calendarEvent->users->filter(fn ($user) => $user->pivot->operation === 'add');
        // Users marked as not attending. Using the same calendar_event_users table. If in table, group user is not attending
        $usersRemoved = $calendarEvent->users->filter(fn ($user) => $user->pivot->operation === 'remove');
        // Remove users which are marked as not attending
        $groupUsers = $group->load('users')->users->filter(fn ($user) => ! $usersRemoved->find($user));

        return view('admin.calendar-event.show', [
            'calendarEvent' => $calendarEvent,
            'usersAdded' => $usersAdded,
            'usersRemoved' => $usersRemoved,
            'event' => $event,
            'group' => $group,
            'groupUsers' => $groupUsers,
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
}
