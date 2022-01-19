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

## Event scheduling features

* Single event fields
  * Name
  * Starting and ending date and time
  * Note
  * Select group attached to this event

* Recurring event fields
  * Name
  * Starting and ending date and time
  * Note
  * Select group attached to this event
  * Days
  * Recurring until

## Event edit observer actions on CalendarEvent

Steps to make sure we update CalendarModel relationship correctly after Event model update:

1. Check recurring_until changes to get correct new number of calendar events (add or remove)
2. Check each day from new starting_at until recurring_until
3. Do not modify CalendarEvent models before starting_at, so we can keep them for the history and stats

## Production setup

### Seed UserType data
`php artisan db:seed --class=RoleSeeder`

