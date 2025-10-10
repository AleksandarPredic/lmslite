@props(['name', 'value', 'label', 'required' => false, 'type' => 'text', 'step' => false, 'class' => ''])

<x-admin.form.field
    class="{{ $class }}"
>
    <x-admin.form.label for="{{ $name }}" :value="$label" />

    @php
        $required = $required ? 'required' : '';
        $step = $step ? sprintf('step=%s', $step) : '';
    @endphp

    <input
        id="{{ $name }}"
        class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
        type="{{ $type }}"
        name="{{ $name }}"
        value="{{ $value }}"
        {{ $required }}
        {{ $step }}
    />

    <x-admin.form.error name="{{ $name }}" />
</x-admin.form.field>
