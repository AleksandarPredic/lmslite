<x-app-layout>
    <x-admin.header>
        {{ __('Create event') }}
    </x-admin.header>

    <x-admin.main>
        <div class="flex justify-end">
            <x-admin.redirect-link href="{{ route('admin.events.index') }}" :title="__('Back to all!')" />
        </div>

        <x-admin.form.wrapper
            class="admin-form-event"
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

            <x-admin.form.textarea
                name="note"
                :label="__('Note')"
            >{{ old('note') }}</x-admin.form.textarea>

            {{-- TODO: Add group select field here --}}

            <x-admin.form.event.recurring :value="0" />

            <x-admin.form.event.days />

            <x-admin.form.input-date-time
                name="recurring_until"
                :value="old('recurring_until')"
                :label="__('Recurrng until')"
                :required="false"
            />
        </x-admin.form.wrapper>
    </x-admin.main>
</x-app-layout>
