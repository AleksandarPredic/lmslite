@php
/**
 * @var \App\Models\CalendarEvent $calendarEvent
 */
@endphp
<x-app-layout>
    <x-admin.header>
        {{ __('Edit calendar event') }}
        <div class="mt-4">
            <p class="text-sm"><strong>Parent event</strong></p>
            <p class="text-sm">{{ 'Name' }}: {{ $calendarEvent->event->name }}</p>
            <p class="text-sm">{{ 'Starting at' }}: {{ lmsCarbonDefaultFormat($calendarEvent->event->starting_at) }}</p>
        </div>
    </x-admin.header>

    <x-admin.main>
        <div class="flex mb-4">
            <x-admin.redirect-link href="{{ route('admin.calendar-events.show', [$calendarEvent]) }}" :title="__('Go back!')" />
        </div>

        <x-admin.form.wrapper
            action="{{ route('admin.calendar-events.update', [$calendarEvent]) }}"
            method="post"
            :buttonText="__('Update')"
        >
            @method('patch')

            <x-admin.form.input-date-time
                name="starting_at"
                :value="old('starting_at', $calendarEvent->starting_at)"
                :label="__('Starting at')"
                :required="true"
            />

            <x-admin.form.input-date-time
                name="ending_at"
                :value="old('ending_at', $calendarEvent->ending_at)"
                :label="__('Ending at')"
                :required="true"
            />

            <x-admin.form.textarea
                name="note"
                :label="__('Note')"
            >{{ old('note', $calendarEvent->note) }}</x-admin.form.textarea>

            <p>
                <span style="color: red;">IMPORTANT: Any edit in single Calendar event will be overwritten on the parent event update.</span>
                <br />
                Or, if you change anything here, don't ever update parent calendar event dates or times, as the algorithm may remove done changes.
            </p>

        </x-admin.form.wrapper>
    </x-admin.main>
</x-app-layout>
