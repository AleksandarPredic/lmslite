# Changelog

## 2025-10-22
- **BREAKING CHANGE: Replaced price_2 system with per-user discount system**
    - Removed price_2 column from groups table
    - Removed price_type column from user_groups pivot table
    - Renamed price_1 to price on groups table
    - Added discount_amount column (decimal 10,2) to user_groups pivot table
    - Updated Group, UserGroup, and User models to use discount_amount instead of price_type
    - Replaced price type dropdown with discount amount input field in group management view
    - Updated payment calculations: final_price = price - discount_amount
    - Updated GroupController with new updateUserDiscount() method
    - Updated all views (create, edit, show, index) to remove price_2 references
    - Updated factories and seeders to reflect new structure
    - Visual indicator now shows when a discount is applied (pink background)
    - Final price is displayed next to discount input for transparency

## 2025-04-04
- Fixed user payments mobile view
- Fixed navigation responsive links
- Typo fix
- Added new user view: Groups history, where we displayed all Groups that user was member of

## 2025-04-02
- Now we are saving which admin ID added the payment
- Added new payments review screen to list all payments in some date range
- Fixed statistics error flash message persisting between requests
- Made course or group field required on server side in the StatisticsController.php
- Fixed a bug on sorting the data for statistics view causing the data to be displayed in a different month
- Added payment date field to user payment screen, so we can setup the payment date in the past if needed

## 2025-03-25
- Upgraded StatisticsController.php to display payments per user per month for all groups user has. This way we show all payments, not just payments for groups related to the filtered user calendar event statuses

## 2025-03-24
- Added statistics view placeholder for payment data
- Fixed date setup for statistics search from the user payments screen

## 2025-03-18
- Small fixes
- Restored link to user profile from the group, added link to user payments from the statistics
- Fix to hide user note section when we have no note text
- Added link to the user payments screen from the group show view, when you click on user name
- Added user payment_note and displayed it also in the UserPayments screen
- Added Groupe course property in the Group show view so we know to which course this group belongs

## 2025-03-17
- Added option to delete payment

## 2025-03-10
### 1. User Payments
- Added statistics for the attendance
- Added link to statistics route for a more detailed preview
### 2. Group component fix
- Fixed to show non active groups when we have $_GET['group_id']

## 2025-03-06
- Add price_type functionality to user-group relationships:
    - Set default 'price_1' in migrations for user_groups table
    - Added price type selection UI in group management view
    - Implemented updateUserPriceType controller method
    - Load price_type pivot data in UserPaymentsController
    - Display different price options based on user's assigned price type
    - Added visual indication for selected price type with background color
- Work on User -> Payments screen

## 2025-03-05
- Work on User -> Payments screen

## 2025-03-04
- Started new screen, User -> Payments

## 2025-03-02
- Add price type to user groups relationship:
    - Added price_type column to user_groups pivot table (nullable)
    - Extended belongsToMany relationship with withPivot for price_type
    - Created getUserPivotPriceType() helper method for default fallback
    - Added select field to update price type per user in the Group show view
    - Implemented auto-submit functionality for price type select
    - Added visual indicator for price_2 selections with background color
- Created lmsPricePublicFormat to conditionally show decimals:
    - Modified the price formatter to show decimals (2 places) only when needed
    - Added seeding data
- Add price fields to Group model:
    - Added price_1 and price_2 fields (decimal type) to groups table
    - Updated Group model to include new fields in $fillable array
    - Added form inputs for both prices in create and edit views
    - Updated validation rules in GroupController for the new price fields
    - Both fields are nullable, numeric with minimum value of 0
- Renamed user statistics.blade.php to next-calendar-events.blade.php
- In this screen now we only show next events for this user
- Add event name in the user next 5 calendar events
- Seeder fix, attach courses to created groups
- Fix StatisticsController.php when the course_id is 0 to avoid making the where request
- Added note to the Group edit view to explain when we use active property

## 2024-01-15
- Merge pull request #7: Add active column to groups

## 2023-10-04
- Sorting events index, sorting groups by start_date desc
- Changed to have 20 items per page
- Color for the pagination current item changed

## 2023-09-14
- Merge pull request #6: Added HTML form for filtering statistics

## 2022-12-27
- Merge pull request #5: Statistics CSS bug fix
- Statistics - added a possibility to test this by dates from $_GET param

## 2022-12-14
- Statistics - added legend
- Merge pull request #4: Statistics MVP ready for testing - added link to calendar event
- Merge pull request #3: Statistics MVP ready for testing

## 2022-12-13
- Table and starting logic for the statistics done
- Started statistics

## 2022-12-08
- Started statistics
- Fixed calendar event update status marking green. It was removing green on Info select change

## 2022-08-19
- Fixed calendar event user item added property. Replaced with date of birth.
- Merge pull request #2: Fix for calendar event when the user was added to the calendar event
- Added green background to the calendar event user if status === attended for easier preview
- Fix for calendar event when the user was added to the calendar event and after that, the same user is added to the group
- Now hide user status select fields in the group section as fields are displayed in the upper "Event users" section

## 2022-08-18
- Merge pull request #1: Calendar event status update ajax
- Compiled for production

## 2022-04-11
- Added create new user button on the user edit and show screens for faster user addition
- Removed Events' validation to prevent adding events before today's date
- Fixed Event factory
- Fixed EventObserver for the Carbon diff adding 1 hour extra

## 2022-03-22
- Fixed a bug for updating user role
- Removed datetime-local step larger than 60 as it has browser validation bugs
- We will just ignore the time when we don't use it

## 2022-03-18
- Fix for user controller request validation
- Changed user email to be nullable field as we don't use it in this version (client requirement)

## 2022-02-27
- Changed user statistics cache to 10 minutes
- Moved user statistics to a separate screen
- Added user statistic for CalendarEventUserStatus and added next 5 events to users.show screen
- Added links to user profile on groups.show and calendarEvent.show
- User CRUD done. Added roles select to user edit and create. Many small refactors.

## 2022-02-23
- Started User CRUD. Added migration, seed and route

## 2022-02-16
- Fix to not allow adding users removed from the group to calendar event if they have calendar event user statuses
- Added calendar event legacy users: users removed from the group but who attended the event
- Fix to remove user status from calendar event user statuses when removing user from calendar event

## 2022-02-15
- Prevented editing calendar event starting_at before the parent event starting at
- Disabled event group change as this would require wiping all previous calendar event user statuses

## 2022-02-01
- Fixed bug with statuses on calendar event
- Removed enum info regular from calendar event user statuses

## 2022-01-31
- Various UI and navigation improvements:
    - Calendar event edit back arrow moved left
    - Added button to go to parent event to avoid loops
    - Changed routes after edit calendar event
    - Model helper update
    - Added event and group deletion functionality
    - Added course select field to group as nullable field
    - UI fixes

## 2022-01-30
- Composer.json changes

## 2022-01-29
- Added number of users on calendar event
- Fixed calendar event statuses responsive display
- Created new calendar week component for admin dashboard
- Sorted components into admin and common categories
- Separated CSS files

## 2022-01-28
- Added CalendarEventUserStatus model integrated to calendar event
- Now we can add status and info to users

## 2022-01-27
- Added CalendarEventUserStatus model and migration. Started implementing it.

## 2022-01-26
- Refactored CalendarEvent and Group models to use addUser and RemoveUser
- Calendar event edit and show views completed

## 2022-01-25
- Refactored initial idea of CalendarEventUser model for simplification

## 2022-01-24
- Work on calendar event show view, adding/removing users
- Added CalendarEventUser model and appropriate classes
- Made timezone env setting
- Removed registration functionality for now
- Added CalendarEvents controller
- Responsive fixes

## 2022-01-23
- Finished adding users to the group and ajax searching for the user

## 2022-01-22
- Refactored user roles relationship to use pivot for easier managing roles
- Refactored removing user from group via pivot table
- Created users destroy button on group show view
- Added user_groups pivot table
- Created route show singular components

## 2022-01-21
- Group edit and create views and controller methods done

## 2022-01-20
- Started adding groups

## 2022-01-19
- Event update observer logic cleanup and finished

## 2022-01-17
- Work on the Event update observer
- Refactored EventController validation
- Finished single event update observer

## 2022-01-15
- Added logic for observer update
- Small changes on the event admin form
- Added back link component

## 2022-01-14
- npm package fixes
- .gitignore clockwork files in storage
- Changed data-property.blade.php
- Work on Event show view and components
- Added note to the Event create and edit form
- Replaced RecurringEvent and SingleEvent with CalendarEvent model

## 2022-01-13
- Added debug code
- EventController.php edit logic

## 2022-01-12
- EventController.php store validation logic
- Work on event create/edit form UI improvements

## 2022-01-11
- Work on Event create, edit and index views

## 2022-01-10
- Work on Event create and index view

## 2022-01-09
- Fix for Event factory
- Started CRUD for event create/edit + logic for event observer

## 2022-01-03
- Courses admin CRUD done

## 2022-01-02
- Removed scss from npm
- Added courses, some small cleaning up

## 2022-01-01
- Init basic users and role structure
- Initial project setup
