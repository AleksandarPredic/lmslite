<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\View\Components\Admin\Form\Event\Days;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

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
     * @return RedirectResponse
     */
    public function store()
    {
        $attributes = request()->validate([
            'name' => ['required', 'min:3', 'max:255'],
            'recurring' => ['required', 'boolean'],
            'occurrence' => ['nullable', Rule::in(array_keys(Event::getOccurrenceOptions()))],
            'days' => ['nullable', 'array', Rule::in(array_keys(Days::getDaysOptions()))],
            'starting_at' => ['required', 'date', 'after:today'],
            'ending_at' => ['required', 'date', 'after:today'],
            'recurring_until' => ['nullable', 'date', 'after:tomorrow'],
            'note' => ['nullable'],
        ]);

        // Additional validation

        // If occurrence is daily we should not pass any selected days
        if (isset($attributes['occurrence']) && 'daily' === $attributes['occurrence'] && isset($attributes['days'])) {
            // Show message on occurrence as days will not be visible in the form
            throw ValidationException::withMessages(
                ['occurrence' => 'Please deselect days! We can\'t assign days for daily occurrence.']
            );

        }

        // If occurrence is weekly we must pass any selected days
        if (isset($attributes['occurrence']) && 'weekly' === $attributes['occurrence'] && ! isset($attributes['days'])) {
            throw ValidationException::withMessages(
                ['days' => 'Please select days!']
            );
        }

        // If we selected recurring event, we need recurring_until date as required field
        if ($attributes['recurring'] && ! isset($attributes['recurring_until'])) {
            throw ValidationException::withMessages(
                ['recurring_until' => 'Please select until date!']
            );
        }

        // additional sanitization
        $attributes['name'] = strip_tags($attributes['name']);

        if (isset($attributes['note'])) {
            $attributes['note'] = strip_tags($attributes['note']);
        }

        // Create event
        $event = Event::create($attributes);

        return redirect(route('admin.events.index'))->with(
            'admin.message.success',
            sprintf(
                'Event, <a href="%1$s">%2$s</a> created!',
                route('admin.events.show', $event),
                $event->name
            )
        );
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
