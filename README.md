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

## Production setup

### Seed UserType data
`php artisan db:seed --class=RoleSeeder`
