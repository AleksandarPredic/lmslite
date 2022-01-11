@props(['options', 'value'])

<x-admin.form.select
    class="admin-form-event__recurring"
    name="recurring"
    :value="(int)old('recurring', $value)"
    :label="__('Recurring')"
    :options="$options"
    :required="true"
/>
