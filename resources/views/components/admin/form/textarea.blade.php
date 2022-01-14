@props(['name', 'label', 'required' => false])

<x-admin.form.field>
    <x-admin.form.label for="{{ $name }}" :value="$label" />

    @php
        $required = $required? 'required' : '';
    @endphp

    <textarea
        id="{{ $name }}"
        class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
        name="{{ $name }}"
        {{ $required }}
    >{{ $slot }}</textarea>

    <x-admin.form.error name="{{ $name }}" />
</x-admin.form.field>
