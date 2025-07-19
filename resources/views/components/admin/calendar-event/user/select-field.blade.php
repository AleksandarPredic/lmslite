@props(['name', 'value', 'options', 'route'])

{{--
 * We are using ajax to submit the select field and update the Calendar event user status.
 * See: resources/js/calendar-event/CalendarEventStatusUpdate.js
--}}
<div {!! $attributes->merge(['class' => 'cal-event-user-status']) !!}>
    <div class="text-center">{{ ucfirst($name) }}</div>
    <form action="{{ $route }}" method="post">

        <select
            name="{{ $name }}"
            id="{{ $name }}"
            data-currentvalue="{{ $value }}"
            required
        >
            @foreach($options as $optionValue => $optionName)
                @php
                    $selected = $optionValue === $value ? 'selected' : '';
                @endphp

                <option
                    value="{{ $optionValue }}"
                    {{ $selected }}
                >{{ $optionName }}</option>
            @endforeach
        </select>
    </form>
    <div class="cal-event-user-status__message"></div>
</div>
