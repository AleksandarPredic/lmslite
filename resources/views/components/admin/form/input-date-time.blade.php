@props(['name', 'value', 'label', 'required' => false])

{{--
# Wrapper so we can change date time if needed in the future
# https://developer.mozilla.org/en-US/docs/Web/HTML/Element/input/datetime-local

# One thing to note is that the displayed date and time formats differ from the actual value;
# the displayed date and time are formatted according to the user's locale as reported by their operating system,
# whereas the date/time value is always formatted YYYY-MM-DDThh:mm
--}}
@php($value = is_a($value, \Carbon\Carbon::class) ? $value->format('Y-m-d\TH:i:s') : $value)
<x-admin.form.input
    name="{{ $name }}"
    type="datetime-local"
    :value="$value"
    :label="$label"
    :required="$required"
/>
