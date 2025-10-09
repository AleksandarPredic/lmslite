@php
    /**
     * This component is used to show the compensation link next to the Calendar Event User Status that triggered it.
     * The Calendar Event User Status with cancelled or no-show status based on which we added compensation to other Calendar Event
     * @var \App\Models\CalendarEventUserCompensation $compensation
     */
@endphp
@props(['compensation', 'linkText'])

@php($calendarEvent = $compensation->calendarEvent)

<x-data-property
    class="ml-4 admin-form__inner-field--flex flex"
>
    <a class="flex items-center" href="{{ route('admin.calendar-events.show', $calendarEvent) }}">
        <strong class="mr-2">{{ $compensation->free ? '[FREE]' :  '[PAID]' }}</strong>
        <span class="mr-2">{{ $linkText }} {{ lmsCarbonDateFormat($calendarEvent->starting_at) }}</span>
        @if(! $compensation->free)
            @if($compensation->payment_completed)
                <span>
                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#78A75A"><path d="m424-312 282-282-56-56-226 226-114-114-56 56 170 170ZM200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h560q33 0 56.5 23.5T840-760v560q0 33-23.5 56.5T760-120H200Zm0-80h560v-560H200v560Zm0-560v560-560Z"/></svg>
            </span>
            @else
                <span>
                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#EA3323"><path d="m336-280 144-144 144 144 56-56-144-144 144-144-56-56-144 144-144-144-56 56 144 144-144 144 56 56ZM200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h560q33 0 56.5 23.5T840-760v560q0 33-23.5 56.5T760-120H200Zm0-80h560v-560H200v560Zm0-560v560-560Z"/></svg>
            </span>
            @endif
        @endif
    </a>
</x-data-property>
