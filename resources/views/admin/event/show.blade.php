@php
/**
 * Enable autocomplete in blade file
 * @var \App\Models\Event $event
 */
@endphp
<x-app-layout>
    <x-admin.header>
        {{ __('Preview event') }}
    </x-admin.header>

    <x-admin.main>
        <div class="flex mb-4">
            <x-admin.redirect-link href="{{ route('admin.events.index') }}" :title="__('Back to all!')" />
        </div>

        <div class="flex justify-end mb-4 px-4">
            <x-admin.action-link-button href="{{ route('admin.events.edit', $event) }}" title="{{ __('Edit') }}" />
            <x-admin.action-delete-button action="{{ route('admin.courses.destroy', $event) }}" />
        </div>

        <x-admin.singular.wrapper>
            {{-- # Header --}}

            <x-slot name="info">
                <x-admin.singular.name
                    name="{{ $event->name }}"
                />
                <x-admin.singular.info
                    name="{{ __('Recurring') }}"
                    value="{{ $event->recurring ? __('Yes') : __('No') }}"
                />

                <x-admin.singular.info
                    name="{{ __('Group') }}"
                    value="{{ $event->group ? $event->group->name : __('none') }}"
                />
            </x-slot>

            {{-- # Properties --}}
            <x-slot name="properties">
                <x-admin.singular.property
                    name="{{ __('Starting at') }}"
                    value="{{ lmsCarbonPublicFormat($event->starting_at) }}"
                />

                <x-admin.singular.property
                    name="{{ __('Ending at') }}"
                    value="{{ lmsCarbonPublicFormat($event->ending_at) }}"
                />

                @if($event->recurring)
                    <x-admin.singular.property
                        name="{{ __('Recurring until') }}"
                        value="{{ lmsCarbonPublicFormat($event->recurring_until) }}"
                    />

                    <x-admin.singular.property
                        name="{{ __('Days') }}"
                        value="{{ implode(', ', $event->getDaysAsNames()) }}"
                    />
                @endif

                <x-admin.singular.property
                    name="{{ __('Note') }}"
                    value="{{ $event->note }}"
                />
            </x-slot>

            {{-- # Meta --}}
            <x-slot name="metalist">
                <x-slot name="metalistname">
                    <x-admin.singular.meta.name
                        name="{{ __('Calendar events') }}"
                    />
                </x-slot>

                @php($separatedOld = false)
                @foreach($calendarEvents as $calendarEvent)

                    {{-- # Mark where active CalendarEvent starts, from Event start_at. We may have old ones for history. --}}
                    @if($calendarEvent->starting_at >= $event->starting_at && ! $separatedOld)
                        <x-admin.singular.meta.info
                            name="{{ __('Current events') }}"
                            value="{{ __('If there are events before this line, those are for history keeping') }}"
                        />
                        @php($separatedOld = true)
                    @endif

                    <x-admin.singular.meta.item-calendar-event
                        :calendar-event="$calendarEvent"
                    />
                @endforeach
            </x-slot>

        </x-admin.singular.wrapper>
    </x-admin.main>
</x-app-layout>
