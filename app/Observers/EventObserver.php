<?php

namespace App\Observers;

use App\Models\Event;
use Carbon\CarbonPeriod;

class EventObserver
{
    /**
     * Handle the Event "created" event.
     *
     * @param  \App\Models\Event  $event
     * @return void
     */
    public function created(Event $event)
    {
        // Single event mapping
        if (! $event->recurring) {
            $event->calendarEvents()->create([
                'starting_at' => $event->starting_at,
                'ending_at' => $event->ending_at
            ]);

            return;
        }

        // Recurring event
        $this->createCalendarEventsUntil($event);
    }

    /**
     * Handle the Event "updated" event.
     * IMPORTANT: Update event will only be fired if the model is dirty
     *
     * @param  \App\Models\Event  $event
     * @return void
     */
    public function updated(Event $event)
    {
        // In single event case, only update the starting_at and ending at of the relationships
        if (! $event->recurring) {
            $event->calendarEvents[0]->update([
                'starting_at' => $event->starting_at,
                'ending_at' => $event->ending_at
            ]);

            return;
        }

        /**
         * Steps to make sure we update calendar events correctly
         * 1. Check recurring_until changes to get correct new number of calendar events
         * 2. Check each day from new starting_at until recurring_until
         * 3. Do not modify CalendarEvent models before starting_at, so we can keep them for the history and stats
         */

        // 1. Check if recurring_until is earlier to remove extra event after, new ones will be created below
        if ($event->isDirty(['recurring_until'])) {
            if ($event->recurring_until < $event->getOriginal('recurring_until')) {
                foreach ($event->calendarEvents as $calendarEvent) {
                    if ($calendarEvent->starting_at <= $event->recurring_until) {
                        continue;
                    }

                    $calendarEvent->delete();
                }

                unset($calendarEvent);
            }
        }

        /**
         * 2. Check each day from new starting_at until recurring_until
         * - If CalendarEvent is not in new days array, delete it
         * - If we have any CalendarEvent in new days array, update starting_at and ending_at
         * - If we don't have CalendarEvent in new days array, create new CalendarEvent
         *
         * 3. Do not modify CalendarEvent models before starting_at, so we can keep them for the history and stats
         * - It can happen that the starting_at is the same as before, but we run the process fully for checks
         */
        // If any of the recurring fields change, run through check all dates again and update data
        if (
            $event->isDirty(['starting_at'])
            || $event->isDirty(['ending_at'])
            || $event->isDirty(['days'])
            || $event->isDirty(['recurring_until'])
        ) {

            // Prepare existing CalendarEvent models for check
            $existingCalendarEvents = [];
            $comparisonDateFormat = 'Y-m-d';
            foreach ($event->calendarEvents as $calendarEvent) {
                $existingCalendarEvents[$calendarEvent->starting_at->format($comparisonDateFormat)] = $calendarEvent;
            }

            $durationInSeconds = $event->ending_at->diffInSeconds($event->starting_at);
            $period = CarbonPeriod::create($event->starting_at, $event->recurring_until);

            foreach ($period as $date) {
                $dateComparisonFormat = (clone $date)->format($comparisonDateFormat);

                // Monday, Tuesday, Wednesday, Thursday, Friday, Saturday, Sunday - $date->dayName
                // 0, 1, 2, 3, 4, 5, 6 - $date->dayOfWeek starting from Monday as 0
                if (! in_array($date->dayOfWeek, $event->days)) {

                    // Delete existing CalendarEvent models that doesn't belong to the newly selected days
                    if (key_exists($dateComparisonFormat, $existingCalendarEvents)) {
                        $existingCalendarEvents[$dateComparisonFormat]->delete();
                    }

                    continue;
                }

                $updatedStartingAt = $date;
                $updatedEndingAt = (clone $date)->addSeconds($durationInSeconds);

                // If we have CalendarEvent on this day, update it
                if (key_exists($dateComparisonFormat, $existingCalendarEvents)) {
                    $existingCalendarEvents[$dateComparisonFormat]->update([
                        'starting_at' => $updatedStartingAt,
                        'ending_at' => $updatedEndingAt,
                    ]);

                    continue;
                }

                // Finally, create a new CalendarEvent for this day
                $event->calendarEvents()->create([
                    'starting_at' => $updatedStartingAt,
                    'ending_at' => $updatedEndingAt,
                ]);
            }
        }

        /**
         * TODO: DB testing
         * - Maybe we need to add cascade DB data on update, but I'm not sure what that does
         * - Test if changes above, removing events or connected data, remove linked data in db table. Example, if
         * when we change the event, the unneeded calendar events are deleted
         * - Test if overrides for calendar event are deleted when we delete calendar event
         */
    }

    /**
     * Handle the Event "deleted" event.
     *
     * @param  \App\Models\Event  $event
     * @return void
     */
    public function deleted(Event $event)
    {
        // TODO: I don't think we have anything todo on delete as we have cascade on delete in the table relationship
    }

    /**
     * Create calendar events for the difference between starting_at and recurring until
     *
     * @param Event $event
     *
     * @return Event
     */
    protected function createCalendarEventsUntil(Event $event): Event
    {
        $durationInSeconds = $event->ending_at->diffInSeconds($event->starting_at);
        $period = CarbonPeriod::create($event->starting_at, $event->recurring_until);

        foreach ($period as $date) {
            // Monday, Tuesday, Wednesday, Thursday, Friday, Saturday, Sunday - $date->dayName
            // 0, 1, 2, 3, 4, 5, 6 - $date->dayOfWeek starting from Monday as 0
            if (! in_array($date->dayOfWeek, $event->days)) {
                continue;
            }

            $event->calendarEvents()->create([
                'starting_at' => $date,
                'ending_at' => (clone $date)->addSeconds($durationInSeconds),
            ]);
        }

        return $event;
    }

}
