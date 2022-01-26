@php
/**
 * @var \App\Models\CalendarEvent $calendarEvent
 */
@endphp
<x-app-layout>
    <x-admin.header>
        {{ __('Edit calendar event') }}
        <p class="text-sm">{{ 'Parent event' }}: {{ $calendarEvent->event->name }}</p>
    </x-admin.header>

    <x-admin.main>
        <div class="flex justify-end">
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
                :label="__('Ending at')"
            >{{ old('note', $calendarEvent->note) }}</x-admin.form.textarea>

        </x-admin.form.wrapper>
    </x-admin.main>
</x-app-layout>
