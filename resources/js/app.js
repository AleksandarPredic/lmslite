require('./bootstrap');

import Alpine from 'alpinejs';
import CalendarEventStatusUpdate from "./calendar-event/CalendarEventStatusUpdate";
import CalendarEventAddCompensation from "./calendar-event/CalendarEventAddCompensation";
import CalendarEventUpdateCompensation from "./calendar-event/CalendarEventUpdateCompensation";

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
    new CalendarEventAddCompensation();
}

/*
 * Ajax updating compensation status and payment_completed on the calendar event
 * @see resources/views/admin/calendar-event/show.blade.php
 */
document.querySelectorAll('.cal-event-compensation__update').forEach(container => {
    new CalendarEventUpdateCompensation(container);
});

