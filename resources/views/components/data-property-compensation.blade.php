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
    <strong class="mr-2">{{ $compensation->free ? '[FREE]' :  '[PAID]' }}</strong>
    <a href="{{ route('admin.calendar-events.show', $calendarEvent) }}">{{ $linkText }} {{ lmsCarbonDateFormat($calendarEvent->starting_at) }}</a>
</x-data-property>
