<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index()
    {
        return view('admin.event.index', [
            'events' => Event::orderBy('starting_at', 'asc')->paginate(10)->withQueryString()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return RedirectResponse
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
     * @return RedirectResponse
     */
    public function store(Request $request)
    {
        //dd($request->all());
        //dd(Carbon::parse($request->get('starting_at')));
        // TODO: handle Carbon exception here + validation request
        return back()->withInput();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Event  $event
     * @return View
     */
    public function show(Event $event)
    {
        // TODO: Lazy load relationships with Event::WithAll()
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Event  $event
     * @return View
     */
    public function edit(Event $event)
    {
        return view('admin.event.edit', [
            'event' => $event
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Event  $event
     * @return RedirectResponse
     */
    public function update(Request $request, Event $event)
    {
        // TODO: Update validation request
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Event  $event
     * @return RedirectResponse
     */
    public function destroy(Event $event)
    {
        //
    }
}
