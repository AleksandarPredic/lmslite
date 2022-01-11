@props(['name', 'value', 'label', 'options', 'required' => false, 'class' => ''])

<x-admin.form.field
    class="{{ $class }}"
    data-selected="{{ $value }}"
>
    <x-label for="{{ $name }}" :value="$label" />

    @php
        $required = $required? 'required' : '';
    @endphp

    <select
        class="block mt-1 w-full"
        name="{{ $name }}"
        id="{{ $name }}"
        onchange="this.parentElement.dataset.selected = this.value;"
        {{ $required }}
    >
        @foreach($options as $optionValue => $optionName)
            @php
                $selected = $optionValue === $value ? 'selected' : '';
            @endphp

            <option
                value="{{ $optionValue }}"
                {{ $selected }}
            >
                {{ $optionName }}
            </option>
        @endforeach
    </select>

    <x-admin.form.error name="{{ $name }}" />
</x-admin.form.field>
