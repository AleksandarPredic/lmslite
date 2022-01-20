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
        //return Event::latest()->with(['calendarEvents', 'group'])->get(); // TODO: delete this
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
        return view('admin.event.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return RedirectResponse
     */
    public function store()
    {
        $attributes = $this->validateSanitizeRequest(new Event());

        // Create event
        $event = Event::create($attributes);

        return redirect(route('admin.events.show', $event))->with(
            'admin.message.success',
            sprintf(
                'Event, %s created!',
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
       return view('admin.event.show', [
           'event' => $event->load('calendarEvents'),
           'calendarEvents' => $event->calendarEvents->sortBy('starting_at')
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
        $attributes = $this->validateSanitizeRequest($event);

        // Update observer will only be fired if the model is dirty
        $updated = $event->update($attributes);

        if (! $updated) {
            return redirect()->back()->with(
                'admin.message.error',
                sprintf(
                    '[ERROR] Event, %s could not be updated!',
                    $event->name
                )
            );
        }

        return redirect()->back()->with(
            'admin.message.success',
            sprintf(
                'Event, %s updated!',
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
     * @param Event $event
     *
     * @return array
     *
     */
    protected function validateSanitizeRequest(Event $event): array
    {
        $attributes = request()->validate([
            'name' => ['required', 'min:3', 'max:255'],
            'group_id' => ['nullable', 'numeric'],
            'recurring' => ['required', 'boolean'],
            'days' => ['exclude_if:recurring,false', 'required', 'array', Rule::in(Days::getDaysOptions(true))],
            'starting_at' => ['required', 'date', 'after:today'],
            'ending_at' => ['required', 'date', 'after:starting_at'],
            'recurring_until' => ['exclude_if:recurring,false', 'required', 'date', 'after_or_equal:+6 day'],
            'note' => ['nullable', 'min:3', 'max:255'],
        ]);

        // Additional validation rules for update action
        if ($event->exists) {
            if ((bool)$attributes['recurring'] !== $event->recurring) {
                throw ValidationException::withMessages(
                    ['recurring' => 'Change is not allowed. Please create a new event.']
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
