require('./bootstrap');

import Alpine from 'alpinejs';
import CalendarEventStatusUpdate from "./calendar-event/CalendarEventStatusUpdate";

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

