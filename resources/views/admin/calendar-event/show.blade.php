@php
/**
 * Enable autocomplete in blade file
 * @var \App\Models\CalendarEvent $calendarEvent
 */
@endphp
<x-app-layout>
    <x-admin.header>
        {{ __('Preview calendar event') }}
    </x-admin.header>

    <x-admin.main>
        <div class="flex mb-4">
            <x-admin.redirect-link href="{{ route('admin.events.show', $event) }}" :title="__('Back to all!')" />
        </div>

        <div class="flex justify-end mb-4 px-4">
            <x-admin.action-link-button href="{{ route('admin.calendar-events.edit', $calendarEvent) }}" title="{{ __('Edit') }}" />
            <x-admin.action-delete-button action="{{ route('admin.calendar-events.destroy', $calendarEvent) }}" />
        </div>

        <x-admin.singular.wrapper>
            {{-- # Header --}}
            <x-slot name="info">
                <x-admin.singular.name
                    name="{{ __('Calendar event:') }} {{ $event->name }}"
                />

                @php($grupLink = $group ? sprintf('<a href="%2$s">%1$s</a>', $group->name, route('admin.groups.show', $group)) : __('none'))
                <x-admin.singular.info
                    name="{{ __('Group') }}"
                    :value="$grupLink"
                />
            </x-slot>

            {{-- # Properties --}}
            <x-slot name="properties">
                <x-admin.singular.property
                    name="{{ __('Starting at') }}"
                    value="{{ lmsCarbonPublicFormat($calendarEvent->starting_at) }}"
                />

                <x-admin.singular.property
                    name="{{ __('Ending at') }}"
                    value="{{ lmsCarbonPublicFormat($calendarEvent->ending_at) }}"
                />

                <x-admin.singular.property
                    name="{{ __('Note') }}"
                    value="{{ $calendarEvent->note }}"
                />
            </x-slot>

            {{-- # TODO: add add/remove users here using add-user.blade.php + add another select to add/remove operation --}}

            {{-- # Meta --}}
            @if($group && $group->users)
                <x-slot name="meta">
                    <x-admin.singular.meta.name
                        name="{{ __('Users attending') }}"
                    />

                    <x-admin.singular.meta.list-wrapper>

                        @foreach($group->users as $user)
                            <x-admin.singular.meta.item-user
                                :user="$user"
                                remove-route="{{ route('admin.groups.users.destroy', [$user->pivot->id, $user]) }}"
                            />
                        @endforeach

                    </x-admin.singular.meta.list-wrapper>
                </x-slot>
            @endif

        </x-admin.singular.wrapper>
    </x-admin.main>
</x-app-layout>
