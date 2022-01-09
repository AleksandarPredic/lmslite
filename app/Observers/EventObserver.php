<?php

namespace App\Observers;

use App\Models\Event;
use Carbon\CarbonInterval;
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
            $period = CarbonPeriod::create($event->starting_at, $event->ending_at);

            foreach ($period as $date) {
                // Monday, Tuesday, Wednesday, Thursday, Friday, Saturday, Sunday - $date->dayName
                // 0, 1, 2, 3, 4, 5, 6 - $date->dayOfWeek starting from Monday as 0
                if (! in_array($date->dayOfWeek, [1, 3])) {
                    continue;
                }

                $event->recurringEvent()->create([
                    'starting_at' => $date,
                    'ending_at' => $date->addSeconds($durationInSeconds),
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
        //
    }

    /**
     * Handle the Event "deleted" event.
     *
     * @param  \App\Models\Event  $event
     * @return void
     */
    public function deleted(Event $event)
    {
        //
    }

    /**
     * Handle the Event "restored" event.
     *
     * @param  \App\Models\Event  $event
     * @return void
     */
    public function restored(Event $event)
    {
        //
    }

    /**
     * Handle the Event "force deleted" event.
     *
     * @param  \App\Models\Event  $event
     * @return void
     */
    public function forceDeleted(Event $event)
    {
        //
    }
}
