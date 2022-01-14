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
        // TODO: Create change for updated event. If single all the same, if we have recurring event, we should ask the user from when to apply the update
        /**
         * Things to cover for recurring events:
         * - If they have any override, that should be kept somehow, or maybe deleted as we anyway ara changing the date so the override maybe doesn't apply
         * - Maybe we need to add cascade data on update, but I'm not sure what that does
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
