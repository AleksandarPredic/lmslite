@php
/**
 * Enable autocomplete in blade file
 * @var \App\Models\CalendarEvent $calendarEvent
 * @var \Illuminate\Database\Eloquent\Collection $usersAdded
 * @var \App\Models\User $userAdded
 * @var \Illuminate\Database\Eloquent\Collection $usersRemoved
 * @var \App\Models\User $userRemoved
 * @var \App\Models\Event $event
 * @var \App\Models\Group $group
 * @var \Illuminate\Database\Eloquent\Collection $usersStatuses
 * @var \App\Models\User $groupUser
 */
@endphp
<x-app-layout>
    <x-admin.header>
        {{ __('Preview calendar event') }}
    </x-admin.header>

    <x-admin.main>
        <div class="flex mb-4">
            <x-admin.redirect-link href="{{ url()->previous() }}" :title="__('Back')" />
        </div>

        <div class="flex justify-end mb-4 px-4">
            <x-admin.action-link-button href="{{ route('admin.events.show', $calendarEvent->event->id) }}" title="{{ __('Parent event') }}" />
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
                <x-admin.singular.info
                    name="{{ __('Number of attendees') }}"
                    :value="$numberOfusers"
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

            {{-- # Slot - add users form --}}
            <x-admin.user.add-user
                route="{{ route('admin.calendar-events.users.store', [$calendarEvent, $group]) }}"
                :exclude="$exclude"
            />

            {{-- # Meta --}}
            @if(($groupUsers) || $users->isNotEmpty() || $legacyUsers->isNotEmpty())
                <x-slot name="meta">

                    {{-- # Calendar event users --}}
                    @if($users->isNotEmpty())
                        <div class="mb-4 pb-3">
                            <x-admin.singular.meta.name
                                name="{{ __('Event users') }}"
                            />

                            <x-admin.singular.meta.list-wrapper>
                                @foreach($users as $user)
                                    <x-admin.singular.meta.item-user
                                        :user="$user"
                                    >
                                        {{-- # Properties --}}
                                        <x-slot name="properties">
                                            <x-data-property>
                                                {{ $user->name }}
                                            </x-data-property>

                                            <x-data-property>
                                                {{ __('Added') }}: {{ lmsCarbonDefaultFormat($user->created_at) }}
                                            </x-data-property>
                                        </x-slot>

                                        {{-- # Links --}}
                                        <x-admin.calendar-event.user.status
                                            :calendarEvent="$calendarEvent"
                                            :user="$user"
                                            :userStatuses="$usersStatuses"
                                        />

                                        <x-admin.action-delete-button
                                            class="px-2 py-1"
                                            action="{{ route('admin.calendar-events.users.destroy', [$calendarEvent, $user]) }}"
                                            button-text="{{ __('Remove')}}"
                                        />

                                        {{-- // TODO: Add show link to user profile --}}
                                    </x-admin.singular.meta.item-user>
                                @endforeach

                            </x-admin.singular.meta.list-wrapper>
                        </div>
                    @endif

                    {{-- # Group users --}}
                    @if($groupUsers)
                        <x-admin.singular.meta.name
                            name="{{ $group->name }} {{ __('group users') }}"
                        />

                        <x-admin.singular.meta.list-wrapper>

                            @foreach($groupUsers as $groupUser)
                                <x-admin.singular.meta.item-user
                                    :user="$groupUser"
                                >
                                    {{-- # Properties --}}
                                    <x-slot name="properties">
                                        <x-data-property>
                                            {{ $groupUser->name }}
                                        </x-data-property>
                                    </x-slot>

                                    {{-- # Links --}}
                                    <x-admin.calendar-event.user.status
                                        :calendarEvent="$calendarEvent"
                                        :user="$groupUser"
                                        :userStatuses="$usersStatuses"
                                    />

                                    {{-- // TODO: Add show link to user profile --}}
                                </x-admin.singular.meta.item-user>
                            @endforeach

                        </x-admin.singular.meta.list-wrapper>
                    @endif

                    {{-- # Legacy users - users that have calendar event user status but were removed from the group --}}
                    @if($legacyUsers->isNotEmpty())
                        <x-admin.singular.meta.name
                            name="{{ __('Users removed from group meanwhile') }}"
                        />

                        <x-admin.singular.meta.list-wrapper>

                            @foreach($legacyUsers as $legacyUser)
                                <x-admin.singular.meta.item-user
                                    :user="$legacyUser"
                                >
                                    {{-- # Properties --}}
                                    <x-slot name="properties">
                                        <x-data-property>
                                            {{ $legacyUser->name }}
                                        </x-data-property>
                                    </x-slot>

                                    {{-- # Links --}}
                                    <x-admin.calendar-event.user.status
                                        :calendarEvent="$calendarEvent"
                                        :user="$legacyUser"
                                        :userStatuses="$usersStatuses"
                                    />

                                    {{-- // TODO: Add show link to user profile --}}
                                </x-admin.singular.meta.item-user>
                            @endforeach

                        </x-admin.singular.meta.list-wrapper>
                    @endif

                </x-slot>
            @endif

        </x-admin.singular.wrapper>
    </x-admin.main>
</x-app-layout>
