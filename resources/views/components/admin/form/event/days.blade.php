@props(['options', 'value'])

<x-admin.form.checkboxes
    name="days"
    :value="old('days', $value)"
    :label="__('Days')"
    :options="$options"
/>
