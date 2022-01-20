<x-app-layout>
    <x-admin.header>
        {{ __('Edit event') }}
    </x-admin.header>

    <x-admin.main>
        <div class="flex mb-4">
            <x-admin.redirect-link href="{{ route('admin.events.index') }}" :title="__('Back to all!')" />
        </div>

        <div class="flex justify-end mb-4 px-4">
            <x-admin.action-link-button href="{{ route('admin.events.show', $event) }}" title="{{ __('Preview') }}" />
            <x-admin.action-delete-button action="{{ route('admin.courses.destroy', $event) }}" />
        </div>

        <x-admin.form.wrapper
            class="admin-form-event"
            action="{{ route('admin.events.update', $event) }}"
            method="post"
            :buttonText="__('Update')"
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

            <x-admin.form.group :value="$event->group_id" />

            <x-admin.form.textarea
                name="note"
                :label="__('Ending at')"
            >{{ old('note', $event->note) }}</x-admin.form.textarea>

            {{-- TODO: Add group select field here --}}

            {{-- # we are hiding recurring fields and setting recurring to input hidden, as conversion is not allowed --}}
            <x-admin.form.input
                name="recurring"
                type="hidden"
                :value="(int)$event->recurring"
                :label=false
            />

            @if($event->recurring)
                <x-admin.form.event.days
                    :value="$event->days"
                />

                <x-admin.form.input-date-time
                    name="recurring_until"
                    :value="old('recurring_until', $event->recurring_until)"
                    :label="__('Recurrng until')"
                    :required="false"
                />
            @endif
        </x-admin.form.wrapper>
    </x-admin.main>
</x-app-layout>
