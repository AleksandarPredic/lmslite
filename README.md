# Simple lightweight LMS

Features
* Manage users
  * Admin can
    * Visit admin dashboard 
    * Create courses and groups
    * Create calendar events: single and recurring
  * Student can
    * Visit student dashboard
    * Preview calendar events
  * Student guest can
    * I still have no idea :) 

## Event features

Event will display this information:
* Basic information
* Event group
* Recurring event: Yes/No
* Calendar events scheduled for this event

### Event form fields
* Single event fields
  * Name
  * Group
  * Starting and ending date and time
  * Note
  * Select group attached to this event

* Recurring event fields
  * Name
  * Group
  * Starting and ending date and time
  * Note
  * Select group attached to this event
  * Days
  * Recurring until

## Calendar events features
Calendar event is a child object of event, which represents event occurrence in calendar.

Calendar event will display this information:
* Basic information
* Event parent: Group
* Event parent: Group users
* Added new users, which are not already in the Event parent group, only for this calendar event

### Adding new users which are not in the event group (if event has group assigned)
* We can add new user to calendar event.
* We can't add user that belongs to Event group, if event has group assigned 
* We can remove added user from the calendar event. 

### Managing Event group users
* We can not remove these users from the calendar event

### Managing newly added users and Event group users
* We can select status to any user on the calendar event
  * Attended
  * Canceled
  * No show

* We can also select status info:
  * Compensation
  * Promo class
  * Other
  

## Event edit observer actions on CalendarEvent

Testing steps to make sure we update CalendarModel relationship correctly after Event model update:

1. Check recurring_until changes to get correct new number of calendar events (add or remove)
2. Check each day from new starting_at until recurring_until
3. Check that we do not modify CalendarEvent models before starting_at, so we can keep them for the history and stats

## Production setup

### Seed UserType data
`php artisan db:seed --class=RoleSeeder`

