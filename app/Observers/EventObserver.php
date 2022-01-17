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

        // Recurring event mapping

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

        dd('continue here to handle observer on update');

        // First check recurring_until
        dd($event->isDirty(['recurring_until']));

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
         * WHEN START AT OR END AT HOURS CHANGE
         * - just update existing events with new datetime
         *
         * WHEN NEW START AT is later than the old start at (can't be smaller by validation)
         * - Update existing ones with new datetime Schedule
         * - Delete all calendar events from now and up to Start at, now < start_at
         * - If recurring_until change perform the step above for recurring_until
         * - Do not delete old calendar events until now, we need them for the history and statistics
         *
         * WHEN DAYS CHANGE
         * - If I decide to allow this change then we need to run creation logic again but create new events only if
         * we don't have existing one at the same time with the same Event->id, and opposite, delete unselected days.
         * This can be added in some next versions.
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
