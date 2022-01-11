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
            $event->singleEvent()->create([
                'starting_at' => $event->starting_at,
                'ending_at' => $event->ending_at
            ]);

            return;
        }

        // Recurring event mapping
        if ('daily' === $event->occurrence) {
            // TODO: handle daily recurring meeting
        }

        if ('weekly' === $event->occurrence) {
            $durationInSeconds = $event->ending_at->diffInSeconds($event->starting_at);
            $period = CarbonPeriod::create($event->starting_at, $event->recurring_until);

            foreach ($period as $date) {
                // Monday, Tuesday, Wednesday, Thursday, Friday, Saturday, Sunday - $date->dayName
                // 0, 1, 2, 3, 4, 5, 6 - $date->dayOfWeek starting from Monday as 0
                if (! in_array($date->dayOfWeek, [1, 3])) {
                    continue;
                }

                $event->recurringEvent()->create([
                    'starting_at' => $date,
                    'ending_at' => (clone $date)->addSeconds($durationInSeconds),
                ]);
            }
        }
    }

    /**
     * Handle the Event "updated" event.
     *
     * @param  \App\Models\Event  $event
     * @return void
     */
    public function updated(Event $event)
    {
        // TODO: Create change for updated event. If single all the same, if we have recurring event, we should ask the user from when to apply the update
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
}
