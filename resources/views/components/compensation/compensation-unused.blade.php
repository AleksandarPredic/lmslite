@php
    /**
     * This component is used to show the compensation in the calendar event where the compensation is used at.
     * @var \App\Models\CalendarEventUserStatus $calendarEventUserStatus
     */
@endphp
@props(['calendarEventUserStatus', 'linkText'])

@php($calendarEvent = $calendarEventUserStatus->calendarEvent)

<x-data-property
    class="lms-compensation ml-4 admin-form__inner-field--flex flex"
>
    <a class="flex items-center" href="{{ route('admin.calendar-events.show', $calendarEvent) }}">
        <span class="mr-2">
            <x-compensation.partials.icon />
        </span>
        <span class="mr-2">
            @if(in_array($calendarEventUserStatus->status, \App\Models\CalendarEventUserCompensation::getCalendarEventUserStatusesForPaidCompensation()))
                <x-compensation.partials.label-paid />
            @else
                <x-compensation.partials.label-free />
            @endif
        </span>
        <span class="lms-compensation-status-date">{{ $calendarEventUserStatus->status ? '(' . ucfirst($calendarEventUserStatus->status) . ')' : '(None)' }} {{ $linkText }} {{ lmsCarbonDateFormat($calendarEvent->starting_at) }}</span>
    </a>
</x-data-property>
