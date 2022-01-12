<x-app-layout>
    <x-admin.header>
        {{ __('Edit event') }}
    </x-admin.header>

    <x-admin.main>
        <x-admin.form.wrapper
            class="admin-form-event"
            action="{{ route('admin.events.store') }}"
            method="post"
            :buttonText="__('Create')"
        >
            @method('patch')

            <x-admin.form.input
                name="name"
                :value="old('name', $event->name)"
                :label="__('Name')"
                :required="true"
            />

            <x-admin.form.input-date-time
                name="starting_at"
                :value="old('starting_at', $event->starting_at)"
                :label="__('Starting at')"
                :required="true"
            />

            <x-admin.form.input-date-time
                name="ending_at"
                :value="old('ending_at', $event->ending_at)"
                :label="__('Ending at')"
                :required="true"
            />

            <x-admin.form.event.recurring :value="$event->recurring" />

            <x-admin.form.event.occurrence :value="$event->occurrence" />

            <x-admin.form.event.days :value="$event->days"/>

            <x-admin.form.input-date-time
                name="recurring_until"
                :value="old('recurring_until', $event->recurring_until)"
                :label="__('Recurrng until')"
                :required="false"
            />
        </x-admin.form.wrapper>
    </x-admin.main>
</x-app-layout>
