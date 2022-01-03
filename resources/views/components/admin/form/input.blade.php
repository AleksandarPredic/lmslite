@props(['name', 'value', 'label', 'required' => false])

<x-admin.form.field>
    <x-label for="{{ $name }}" :value="$label" />

    <x-input
        id="{{ $name }}"
        class="block mt-1 w-full"
        type="text"
        name="{{ $name }}"
        :value="$value"
        :required="$required"
    />

    <x-admin.form.error name="{{ $name }}" />
</x-admin.form.field>
