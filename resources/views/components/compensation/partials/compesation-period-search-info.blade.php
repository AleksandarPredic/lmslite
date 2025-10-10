@php
    /**
     * %1$s - param for the number of months in the future
     * %2$s - param for the number of months in the past
     */
@endphp

@props(['textWithPlaceholders'])

<div class="mb-4 text-sm text-gray-500">
    {{ sprintf($textWithPlaceholders, \App\Models\CalendarEventUserCompensation::getCompensationSearchRangeInMonthsFuture(), \App\Models\CalendarEventUserCompensation::getCompensationSearchRangeInMonthsPast()) }}
</div>
