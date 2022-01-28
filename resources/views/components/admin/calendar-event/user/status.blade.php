{{--
# Controller app/View/Components/Admin/CalendarEvent/User/Status.php
--}}

@props(['calendarEvent', 'user', 'userStatuses'])

<x-admin.calendar-event.user.select-field
    name="status"
    value="{{ $status }}"
    route="{{ $route }}"
    :options="$statusOptions"
/>

<x-admin.calendar-event.user.select-field
    name="info"
    value="{{ $info }}"
    route="{{ $route }}"
    :options="$infoOptions"
/>
