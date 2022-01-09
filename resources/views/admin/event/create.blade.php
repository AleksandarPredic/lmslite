<x-app-layout>
    <x-admin.header>
        {{ __('Create event') }}
    </x-admin.header>

    <x-admin.main>
        <x-admin.form.wrapper
            action="{{ route('admin.events.store') }}"
            method="post"
            :buttonText="__('Create')"
        >
            <x-admin.form.input
                name="name"
                :value="old('name')"
                :label="__('Name')"
                :required="true"
            />

            <x-admin.form.event.recurring :value="0" />

            <x-admin.form.event.days />

            <x-admin.form.event.occurrence />

        </x-admin.form.wrapper>
    </x-admin.main>
</x-app-layout>
