@props(['options', 'value', 'disabled'])

<x-admin.form.select
    class="admin-form-event__recurring"
    name="recurring"
    :value="(int)old('recurring', $value)"
    :label="__('Recurring')"
    :options="$options"
    :disabled="$disabled"
    :required="true"
/>
