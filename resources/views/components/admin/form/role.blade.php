@props(['options', 'value'])

<x-admin.form.select
    name="role_id"
    :value="(int)old('role_id', $value)"
    :label="__('Role')"
    :options="$options"
    :required="true"
/>
