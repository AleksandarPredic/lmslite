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
* Overrides to event parent group users: Users removed from this event
* Added new users only for this calendar event

### Adding new users which are not in the event assigned group (if event has group)
* We can add new user to calendar event. We add the user to DB table `calendar_event_users` and set `operation` as `add`.
* We can remove added user from the calendar event. We remove the user from DB table `calendar_event_users`.

### Marking the user from event group as not attending
* We can mark the event group user as not attending from this calendar event and select the reason. 
We add the user to DB table `calendar_event_users` and set `operation` as `remove`
  * We still use the same DB table `calendar_event_users` for group and newly added users with the rule:
  If the event group user is added to the `calendar_event_users` table, that is 
  considered that he will not attend the calendar event. But we display these users
  on the calendar event for better user reference
* We can undo marking the event group user as not attending
* This allows us to generate statistic of user attendance
* We can mark the user as attended



## Event edit observer actions on CalendarEvent

Testing steps to make sure we update CalendarModel relationship correctly after Event model update:

1. Check recurring_until changes to get correct new number of calendar events (add or remove)
2. Check each day from new starting_at until recurring_until
3. Check that we do not modify CalendarEvent models before starting_at, so we can keep them for the history and stats

## Production setup

### Seed UserType data
`php artisan db:seed --class=RoleSeeder`

