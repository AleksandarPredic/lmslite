<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\View\Components\Admin\Form\Event\Days;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
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
        //return Event::latest()->with('calendarEvents')->first(); // TODO: delete this
        //return CalendarEvent::latest()->with('event')->get(); // TODO: delete this
        return view('admin.event.index', [
            //'events' => Event::orderBy('starting_at', 'asc')->paginate(10)->withQueryString() // TODO: restore this
            'events' => Event::latest()->paginate(10)->withQueryString()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create()
    {
        return view('admin.event.create', [
            'occurrenceDefault' => 'weekly'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return RedirectResponse
     */
    public function store()
    {
        $attributes = $this->validateSanitizeRequest();

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
     * @param Event $event
     *
     * @return View
     */
    public function show(Event $event)
    {
        // TODO: Lazy load relationships with Event::WithAll()
       return view('admin.event.show', [
           'event' => $event->load('calendarEvents')
       ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Event $event
     *
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
     * @param Event  $event
     *
     * @return RedirectResponse
     */
    public function update(Event $event)
    {
        $attributes = $this->validateSanitizeRequest();

        // Update observer will only be fired if the model is dirty
        $updated = $event->update($attributes);

        if (! $updated) {
            return redirect()->back()->with(
                'admin.message.error',
                sprintf(
                    '[ERROR] Event, <a href="%1$s">%2$s</a> could not be updated!',
                    route('admin.events.show', $event),
                    $event->name
                )
            );
        }

        return redirect()->back()->with(
            'admin.message.success',
            sprintf(
                'Event, <a href="%1$s">%2$s</a> updated!',
                route('admin.events.show', $event),
                $event->name
            )
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Event  $event
     *
     * @return RedirectResponse
     */
    public function destroy(Event $event)
    {
        //
    }

    /**
     * Validate and sanatize request
     *
     * @return array
     *
     * @throws ValidationException
     */
    protected function validateSanitizeRequest(): array
    {
        $attributes = request()->validate([
            'name' => ['required', 'min:3', 'max:255'],
            'recurring' => ['required', 'boolean'],
            'occurrence' => ['nullable', Rule::in(Event::getOccurrenceOptions(true))],
            'days' => ['nullable', 'array', Rule::in(Days::getDaysOptions(true))],
            'starting_at' => ['required', 'date', 'after:today'],
            'ending_at' => ['required', 'date', 'after:today'],
            'recurring_until' => ['nullable', 'date', 'after:tomorrow'],
            'note' => ['nullable', 'min:3', 'max:255'],
        ]);

        // Additional validation for recurring events
        if ($attributes['recurring']) {
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
            if (! isset($attributes['recurring_until'])) {
                throw ValidationException::withMessages(
                    ['recurring_until' => 'Please select until date!']
                );
            }
        }

        // additional sanitization
        $attributes['name'] = strip_tags($attributes['name']);

        if (isset($attributes['note'])) {
            $attributes['note'] = strip_tags($attributes['note']);
        }

        return $attributes;
    }
}
