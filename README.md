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
  * Starting and ending date and time
  * Group attached to this event
  * Note

* Recurring event fields
  * Name
  * Group
  * Starting and ending date and time
  * Note
  * Select group attached to this event
  * Days
  * Recurring until

These fields can't be changed via the Event edit screen:
* Group attached to this event. In this case you can create a new event with 
different group.
* Can not convert single event to the recurring one


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

## Group features
Ecah group manage screen will display these features:
* Basic information
* Added new users, which are not already in the group 
* Users that are in the group

### Managing users that are in the group
We can:
* Remove user from the group
* Set a custom discount amount per user. The final price is calculated as: price - discount_amount.

### Create group
When creating the group, the important is to set the group starting at and ending at dates. Those dates will be 
used to track payments per group.

### Setting the group to non active
If you set the `Active` property of the Group to `No`, then this group will not be visible in many select fields across the LMS.

## User Payments
If you visit the user screen, and select to view the user, you will have the "Payments" button available.

Clicking the button, will open a new screen where you can manage user payments for the Groups which he is member of.

Here you can see the list of Groups, and per group list of months. For each month you can see if the user has paid, or 
you will have the fields to add the payment or delete the payment.

IMPORTANT: All the payments for the users are managed in this screen only, so all the related functionality is in this screen.

### Payments preview
In this menu item you can preview payments per day or selected period.

## Statistics
In the main manu, we have the "Statistics" link. 

Here you can preview all the user attendance per each event, per month. You can also preview the user payments for the Groups.

IMPORTANT: This screen will only display the users that have user event statuses. If the user has no statuses for the selected period 
the user will not be listed.

## User Groups History
If you visit the user screen, and select to view the user, you will have the "History" button available.

Here you can preview all Groups, user has ever been member of, sorted by Courses.

## Production setup

### Seed UserType data
`php artisan db:seed --class=RoleSeeder`
`php artisan db:seed --class=ProdSeeder`
