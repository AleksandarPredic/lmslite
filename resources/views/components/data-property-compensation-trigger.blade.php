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
    <strong class="mr-2">{{ $compensation->paid ? '[PAID]' : '[FREE]' }}</strong>
    <a href="{{ route('admin.calendar-events.show', $calendarEvent) }}">{{ $linkText }} {{ lmsCarbonDateFormat($calendarEvent->starting_at) }}</a>
</x-data-property>
