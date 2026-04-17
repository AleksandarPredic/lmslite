@php
    $courseValue = (int)old('course_id', $value);
    $required = $required ?? false;

    /**
     * ⚠️  HTML5 Validation NOT Possible with value="0"
     *
     * DEVELOPER WARNING: Do NOT attempt to implement HTML5 validation for this field!
     *
     * The "None" option uses value="0" which is baked into the system as the
     * sentinel for "no course selected". You CANNOT change it to empty string
     * without breaking multiple parts of the application:
     *
     * 1. StatisticsController.php (line 38)
     *    - Defaults $courseId to 0 when not provided
     *    - Uses 0 to conditionally skip the where('course_id') query
     *
     * 2. Group.php View Component (line 69)
     *    - Uses course_id=0 for CSS class: group-course-id-0
     *    - JavaScript in statistics/index.blade.php depends on this
     *
     * 3. statistics/index.blade.php (JavaScript)
     *    - Checks selectedCourseId === '0' to filter group options
     *    - Sets groupSelect.value = '0' on course change
     *
     * 4. Group.php Model (lines 121, 125)
     *    - The course_id setter converts 0 to null
     *
     * HTML5's required attribute will NOT prevent submission with value="0"
     * because 0 is a valid value. Use backend validation only (GroupController) for making sure we have this required.
     */
@endphp

<x-admin.form.select
    name="course_id"
    :value="$courseValue"
    :label="__('Courses')"
    :options="$options"
    :required="$required"
/>


