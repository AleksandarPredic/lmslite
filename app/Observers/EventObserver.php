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

        // Recurring event mapping: daily, weekly

        // If no occurrence provided, we will create daily event as a default
        $durationInSeconds = $event->ending_at->diffInSeconds($event->starting_at);
        $period = CarbonPeriod::create($event->starting_at, $event->recurring_until);

        foreach ($period as $date) {
            // For daily event just create event every day
            if ('weekly' === $event->occurrence) {
                // Monday, Tuesday, Wednesday, Thursday, Friday, Saturday, Sunday - $date->dayName
                // 0, 1, 2, 3, 4, 5, 6 - $date->dayOfWeek starting from Monday as 0
                if (! in_array($date->dayOfWeek, $event->days)) {
                    continue;
                }
            }

            $event->calendarEvents()->create([
                'starting_at' => $date,
                'ending_at' => (clone $date)->addSeconds($durationInSeconds),
            ]);
        }
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
        dd('continue here to handle observer on update');

        /**
         * IMPORTANT: For proper testing and to avoid testing it again, we can't start this until we have Groups and Calendar
         * overrides created and working.
         */

        /**
         * Edit to cover
         *
         * RECURRING UNTIL CHANGES but Start at and Start end is the same
         * - When the new recurring_until is later than current one, add new calendar events
         * - When the new recurring_until is earlier delete later calendar events
         *
         * COMPARING IF EVENT EXISTS
         * - Compare Carbon objects but set seconds to 00, to avoid new events accidentally scheduled
         *
         * WHEN NEW START AT is later than the old start at (can't be smaller by validation)
         * - Delete all calendar events from now in the future
         * - Do not delete old calendar events until now, we need them for the history and statistics
         * - Schedule new calendar events from new start at until recurring until
         *
         * WHEN OCCURRENCE CHANGE
         * - Occurrence change not allowed to keep the logic as simple as possible, as this will not be used in this
         * project. The user will delete the current event and schedule a new one. This can be implemented in the future
         * if we see this case start happening.
         *
         * WHEN DAYS CHANGE
         * - Occurrence change not allowed to keep the logic as simple as possible, as this will not be used in this
         * project. The user will delete the current event and schedule a new one. This can be implemented in the future
         * if we see this case start happening.
         *
         * WHEN RECURRING CHANGE
         * - Converting single event to recurring and opposite is not allowed to keep the logic as simple as possible,
         * as this will not be used in this project. The user will delete the current event and schedule a new one.
         * This can be implemented in the future if we see this case start happening.
         *
         * TO test observer
         * - Change only recurring_until
         * - Change only starting at
         * - Change only Ending at
         * - Change only starting at and ending at
         * - Change only starting at and recurring_until
         * - Change only ending at and recurring_until
         * - Change only starting at, ending at and recurring_until
         *
         * DB testing
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
        dd('continue here to handle observer on delete');
        // TODO: I don't think we have anything todo on delete as we have cascade on delete in the table relationship
    }
}
