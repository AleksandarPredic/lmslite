@props(['options', 'value'])

<x-admin.form.select
    name="recurring"
    :value="(int)old('recurring', $value)"
    :label="__('Recurring')"
    :options="$options"
    :required="true"
/>
