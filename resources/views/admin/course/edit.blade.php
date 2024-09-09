<x-app-layout>
    <x-admin.header>
        {{ __('Edit course') }}
    </x-admin.header>

    <x-admin.main>
        <div class="flex mb-4">
            <x-admin.redirect-link href="{{ route('admin.courses.index') }}" :title="__('Back to all!')" />
        </div>

        <div class="flex justify-end mb-4 px-4">
            <x-admin.action-link-button href="{{ route('admin.courses.show', $course) }}" title="{{ __('Manage') }}" />
            <x-admin.action-delete-button action="{{ route('admin.courses.destroy', $course) }}" />
        </div>

        <x-admin.form.wrapper
            action="{{ route('admin.courses.update', [$course]) }}"
            method="post"
            :buttonText="__('Update')"
        >
            @method('patch')

            <x-admin.form.input
                name="name"
                :value="old('name', $course->name)"
                :label="__('Name')"
                :required="true"
            />

            <x-admin.form.input
                name="price"
                type="number"
                :value="old('price', $price)"
                :label="__('Price')"
                :required="true"
                :step="0.01"
            />

        </x-admin.form.wrapper>
    </x-admin.main>
</x-app-layout>
