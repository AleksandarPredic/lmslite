@php
    /**
     * This component is used to show the compensation in the calendar event where the compensation is used at.
     * @var \App\Models\CalendarEventUserCompensation $compensation
     */
@endphp
@props(['compensation', 'linkText'])

@php($calendarEvent = $compensation->calendarEventUserStatus->calendarEvent)

<x-data-property
    class="lms-compensation ml-4 admin-form__inner-field--flex flex"
>
    <a class="flex items-center" href="{{ route('admin.calendar-events.show', $calendarEvent) }}">
        <span class="mr-2">
            <x-compensation.partials.icon />
        </span>
        <span class="mr-2">@if($compensation->free) <x-compensation.partials.label-free /> @else <x-compensation.partials.label-paid /> @endif</span>
        <span>{{ $linkText }} {{ lmsCarbonDateFormat($calendarEvent->starting_at) }}</span>
    </a>
</x-data-property>
