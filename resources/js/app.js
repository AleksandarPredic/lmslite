require('./bootstrap');

import Alpine from 'alpinejs';
import CalendarEventStatusUpdate from "./calendar-event/CalendarEventStatusUpdate";
import CalendarEventAddFreeCompensation from "./calendar-event/CalendarEventAddFreeCompensation";

window.Alpine = Alpine;

Alpine.start();

/*
 * Ajax updates for the user status on the calendar event
 * Route calendar-events.users.status.update
 */
const calendarEventStatusses = document.getElementsByClassName('cal-event-user-status');
if (calendarEventStatusses.length) {
    for (let calendarEventStatus of calendarEventStatusses) {
        new CalendarEventStatusUpdate(calendarEventStatus);
    }
}

/*
 * Ajax adding compensation user on the calendar event
 * @see resources/views/admin/calendar-event/show.blade.php
 */
if (document.getElementsByClassName('cal-event-user-status').length) {
    new CalendarEventAddFreeCompensation();
}

