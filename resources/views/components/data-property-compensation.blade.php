@php
    /**
     * This component is used to show the compensation in the calendar event where the compensation is used at.
     * @var \App\Models\CalendarEventUserCompensation $compensation
     */
@endphp
@props(['compensation', 'linkText'])

@php($calendarEvent = $compensation->calendarEventUserStatus->calendarEvent)

<x-data-property
    class="ml-4 admin-form__inner-field--flex flex"
>
    <span class="mr-2">
        <svg xmlns="http://www.w3.org/2000/svg" height="20px" viewBox="0 -960 960 960" width="20px" fill="#000000"><path d="M672-336v-336H336v-72h408v408h-72ZM480-144v-336H144v-72h408v408h-72Z"/></svg>
    </span>
    <strong class="mr-2">{{ $compensation->free ? '[FREE]' :  '[PAID]' }}</strong>
    <a href="{{ route('admin.calendar-events.show', $calendarEvent) }}">{{ $linkText }} {{ lmsCarbonDateFormat($calendarEvent->starting_at) }}</a>
</x-data-property>
