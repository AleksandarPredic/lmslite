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

            {{-- # https://developer.mozilla.org/en-US/docs/Web/HTML/Element/input/datetime-local --}}
            <x-admin.form.input-date-time
                name="starting_at"
                :value="old('starting_at')"
                :label="__('Starting at')"
                :required="true"
            />

            <x-admin.form.input-date-time
                name="ending_at"
                :value="old('ending_at')"
                :label="__('Ending at')"
                :required="true"
            />

            <x-admin.form.input-date-time
                name="recurring_until"
                :value="old('recurring_until')"
                :label="__('Recurrng until')"
                :required="true"
            />
        </x-admin.form.wrapper>
    </x-admin.main>
</x-app-layout>
