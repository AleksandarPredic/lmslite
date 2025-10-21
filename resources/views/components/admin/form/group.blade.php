@props(['options', 'value', 'disabled'])

@php
    $name = 'group_id';
    $label = __('Group');
    $value = (int)old('group_id', $value);
@endphp

<x-admin.form.field
    data-selected="{{ $value }}"
>
    <x-label for="{{ $name }}" :value="$label" />

    @php
        $disabled = $disabled ? 'disabled' : '';
    @endphp

    <select
        class="block mt-1 w-full"
        name="{{ $name }}"
        id="{{ $name }}"
        onchange="this.parentElement.dataset.selected = this.value;"
    >
        @foreach($options as $option)
            @php
                $optionValue = $option['id'];
                $selected = $optionValue === $value ? 'selected' : '';
            @endphp

            <option
                {{-- // course_id is used for some JS interactions with the course select field (example statistics screen) --}}
                class="group-course-id-{{ $option['course_id'] }}"
                value="{{ $optionValue }}"
                {{ $selected }}
            >{{ $option['name'] }}</option>
        @endforeach
    </select>

    <x-admin.form.error name="{{ $name }}" />
</x-admin.form.field>
