@props(['options', 'value'])

<x-admin.form.select
    class="admin-form-event__occurrence"
    name="occurrence"
    :value="old('occurrence', $value)"
    :label="__('Occurrence')"
    :options="$options"
    :required="false"
/>
