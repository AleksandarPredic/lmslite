@props(['name', 'calendarEventStatuses'])

@php
/**
 * @var string $name
 * @var \Illuminate\Database\Eloquent\Collection $calendarEventStatuses
 */
@endphp

@if($calendarEventStatuses->isNotEmpty())
    <details class="mb-4">
        <summary>
            {{ $name }}: <strong>({{ $calendarEventStatuses->count() }})</strong>
        </summary>
        <div>
            @foreach($calendarEventStatuses as $calendarEventStatus)

                @foreach($calendarEventStatus->calendarEvents as $calendarEvent)
                    <x-admin.singular.meta.item-calendar-event
                        :calendar-event="$calendarEvent"
                    />
                @endforeach
            @endforeach
        </div>
    </details>
@endif
