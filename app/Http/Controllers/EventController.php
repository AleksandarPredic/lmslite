<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Http\Request;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Event::all();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        return view('admin.event.create');

        /*$event = Event::create([
            'name' => 'Single event test',
            'recurring' => false,
            'starting_at' => Carbon::make('2022-01-15 10:45:25'),
            'ending_at' => Carbon::make('2022-01-15 11:45:25'),
            'note' => 'Some test note for recurring event'
        ]);*/

        $event = Event::create([
            'name' => 'Recurring event test',
            'recurring' => true,
            'days' => [1, 3],
            'occurrence' => 'weekly',
            'starting_at' => Carbon::make('2022-01-15 10:45:25'),
            'ending_at' => Carbon::make('2022-02-11 11:45:25'),
            'note' => 'Some test note for recurring event'
        ]);

        dd($event);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // TODO: handle Carbon exception here
        return back()->withInput();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function show(Event $event)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function edit(Event $event)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Event $event)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function destroy(Event $event)
    {
        //
    }
}
