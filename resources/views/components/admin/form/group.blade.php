@props(['options', 'value'])

<x-admin.form.select
    name="group_id"
    :value="(int)old('group_id', $value)"
    :label="__('Group')"
    :options="$options"
/>
