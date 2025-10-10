@php
    /**
     * This component is used to show the compensation link next to the Calendar Event User Status that triggered it.
     * The Calendar Event User Status with cancelled or no-show status based on which we added compensation to other Calendar Event
     * @var \App\Models\CalendarEventUserCompensation $compensation
     */
@endphp
@props(['compensation'])

@php($calendarEvent = $compensation->calendarEvent)

<x-data-property
    class="lms-compensation ml-4 admin-form__inner-field--flex flex"
>
    <a class="flex items-center" href="{{ route('admin.calendar-events.show', $calendarEvent) }}">
        <span class="mr-2">
            <x-compensation.partials.icon />
        </span>
        <span class="mr-2">@if($compensation->free) <x-compensation.partials.label-free /> @else <x-compensation.partials.label-paid /> @endif</span>
        <span class="mr-2 lms-compensation-status-date">{{ $compensation->status ? '(' . ucfirst($compensation->status) . ')' : '(None)' }} {{ lmsCarbonDateFormat($calendarEvent->starting_at) }}</span>
        @if(! $compensation->free)
            @if($compensation->payment_completed)
                <span>
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#9DC384"><path d="M441-120v-86q-53-12-91.5-46T293-348l74-30q15 48 44.5 73t77.5 25q41 0 69.5-18.5T587-356q0-35-22-55.5T463-458q-86-27-118-64.5T313-614q0-65 42-101t86-41v-84h80v84q50 8 82.5 36.5T651-650l-74 32q-12-32-34-48t-60-16q-44 0-67 19.5T393-614q0 33 30 52t104 40q69 20 104.5 63.5T667-358q0 71-42 108t-104 46v84h-80Z"/></svg>
                </span>
            @else
                <span>
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#EA3323"><path d="M574-618q-12-30-35.5-47T482-682q-18 0-35 5t-31 19l-58-58q14-14 38-25.5t44-14.5v-84h80v82q45 9 79 36.5t51 71.5l-76 32ZM792-56 608-240q-15 15-41 24.5T520-204v84h-80v-86q-56-14-93.5-51T292-350l80-32q12 42 40.5 72t75.5 30q18 0 33-4.5t29-13.5L56-792l56-56 736 736-56 56Z"/></svg>
                </span>
            @endif
        @endif
    </a>
</x-data-property>
