@props(['name', 'value', 'label', 'options', 'disabled' => false])

<x-admin.form.field>
    <h5 class="mb-4">{{ $label }}</h5>

    <div class="admin-form__inner-field admin-form__inner-field--flex sm:flex">
        @foreach($options as $checkboxValue => $checkboxName)

            <div class="admin-form__checkbox">
                @php
                    $disabled = $disabled ? 'disabled' : '';
                    $checked = is_array($value) && in_array($checkboxValue, $value) ? 'checked' : '';
                    $nameAttr = sprintf('%s[]', $name);
                    $idAttr = sprintf('%s-%s', $name, $checkboxValue);
                    // TODO: Add frontend js validation for this field
                @endphp

                <x-admin.form.label for="{{ $idAttr }}" :value="$checkboxName" />

                <input
                    id="{{ $idAttr }}"
                    class="ml-2 rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                    type="checkbox"
                    name="{{ $nameAttr }}"
                    value="{{ $checkboxValue }}"
                    {{ $checked }}
                    {{ $disabled }}
                />
            </div>

        @endforeach
    </div>

    <x-admin.form.error name="{{ $name }}" />
</x-admin.form.field>
