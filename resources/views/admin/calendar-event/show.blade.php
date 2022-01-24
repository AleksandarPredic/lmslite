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
 * @var \Illuminate\Database\Eloquent\Collection $groupUsers
 * @var \App\Models\User $groupUser
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

            {{-- # Meta --}}
            @if(($groupUsers->isNotEmpty()) || $usersAdded->isNotEmpty() || $usersRemoved->isNotEmpty())
                <x-slot name="meta">

                    {{-- # Added users --}}
                    @if($usersAdded->isNotEmpty())
                        <div class="mb-4 pb-3">
                            <x-admin.singular.meta.name
                                name="{{ __('Calendar event users attending') }}"
                            />

                            <x-admin.singular.meta.list-wrapper>
                                @foreach($usersAdded as $userAdded)
                                    <x-admin.singular.meta.item-user
                                        :user="$userAdded"
                                    >
                                        <x-admin.action-delete-button
                                            class="px-2 py-1"
                                            action="{{-- route('admin.groups.users.destroy', [$user->pivot->id, $user]) --}}"
                                            button-text="{{ __('Remove')}}"
                                        />
                                    </x-admin.singular.meta.item-user>
                                @endforeach

                            </x-admin.singular.meta.list-wrapper>
                        </div>
                    @endif

                    {{-- # Removed users --}}
                    @if($usersRemoved->isNotEmpty())
                        <div class="mb-4 pb-3">
                            <x-admin.singular.meta.name
                                name="{{ $group->name }} {{ __('group users not attending') }}"
                            />

                            <x-admin.singular.meta.list-wrapper>
                                @foreach($usersRemoved as $userRemoved)
                                    <x-admin.singular.meta.item-user
                                        :user="$userRemoved"
                                    >
                                        <x-admin.action-delete-button
                                            class="px-2 py-1"
                                            action="{{-- route('admin.groups.users.destroy', [$user->pivot->id, $user]) --}}"
                                            button-text="{{ __('Add user back')}}"
                                        />
                                    </x-admin.singular.meta.item-user>
                                @endforeach

                            </x-admin.singular.meta.list-wrapper>
                        </div>
                    @endif

                    {{-- # Group users --}}
                    @if($groupUsers->isNotEmpty())
                        <x-admin.singular.meta.name
                            name="{{ $group->name }} {{ __('group users attending') }}"
                        />

                        <x-admin.singular.meta.list-wrapper>

                            @foreach($groupUsers as $groupUser)
                                <x-admin.singular.meta.item-user
                                    :user="$groupUser"
                                >
                                    <x-admin.action-delete-button
                                        class="px-2 py-1"
                                        action="{{-- route('admin.groups.users.destroy', [$user->pivot->id, $user]) --}}"
                                        button-text="{{ __('Remove')}}"
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
