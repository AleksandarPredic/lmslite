@props(['options', 'value'])

<x-admin.form.select
    name="occurrence"
    :value="old('occurrence', $value)"
    :label="__('Occurrence')"
    :options="$options"
    :required="true"
/>
