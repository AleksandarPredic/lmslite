@props(['options', 'value'])

<x-admin.form.select
    name="course_id"
    :value="(int)old('course_id', $value)"
    :label="__('Courses')"
    :options="$options"
/>
