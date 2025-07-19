# Calendar Event Status Update

This document describes all possible cases when updating user status and info using the CalendarEventStatusUpdate JS class and the updateUserStatus PHP method.

## Success Cases

- **Status Update Success**
    - User selects a new status value in the dropdown
    - AJAX request is sent with the new status value
    - Server validates and updates the status
    - UI shows success message that disappears after 3 seconds
    - The select field background changes to green temporarily
    - The `data-currentvalue` attribute is updated to the new value

- **Info Update Success**
    - User selects a new info value in the dropdown
    - Server validates and updates the info if the status is already set
    - UI shows success message that disappears after 3 seconds
    - The select field background changes to green temporarily

## Special Status Cases

- **Status Update to 'attended'**
    - When status is updated to 'attended', the entire user item gets a CSS class `singular-meta__item-user-attended` and become green background.
    - This visually highlights users who have attended

- **Status Update to 'none'**
    - When status is set to 'none', the info field is automatically reset to 'none'
    - This prevents having info without a status, which could affect statistics

## Error Cases

- **Info Update Without Status**
    - User tries to update info when status is 'none' or empty
    - Server validation fails with message "Please select the status first"
    - Select field reverts to previous value
    - Error message is displayed with red background

- **Update Validation Error**
    - Server validation fails due to invalid status or info values
    - Select field reverts to previous value (or 'none' if previous value was empty)
    - Error message is displayed with red background

- **Generic Error**
    - Network error or other server issue occurs
    - Select field reverts to previous value (or 'none' if previous value was empty)
    - Generic error message "Ooops! Error!" is displayed with red background

## HTML Attributes

- **`data-currentvalue` Attribute**
    - Stores the currently selected value of the select field
    - Used as a fallback value when errors occur during updates
    - When a successful update occurs, this attribute is updated to the new value
    - Ensures the UI can revert to the last valid state if an AJAX request fails
    - Prevents data inconsistency between what's displayed and what's stored in the database

## UI States

- **Form Submission During Update**
    - Select field is disabled while the AJAX request is processing
    - "saving..." message is displayed
    - Field is re-enabled after the request completes (success or error)
