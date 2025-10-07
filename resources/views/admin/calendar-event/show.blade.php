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
                    name="{{ __('Number of attendees for this event') }}"
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

            {{-- # USER COMPENSATION START --}}
            <h2 class="mb-1">Add compensation user</h2>
            <div class="mb-4 text-sm text-gray-500">Search users which are eligible for compensation between now and last {{ \App\Http\Controllers\UserController::COMPENSATION_SEARCH_RANGE_IN_MONTHS }} months.</div>
            <div class="cal-event-compensation mb-12">
                <div class="cal-event-compensation__find-user">
                    <x-admin.form.field>
                        <x-admin.form.label for="find-compensation-user" :value="__('Type user name')" />
                        <input
                            id="find-compensation-user"
                            class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                            type="text"
                            placeholder="{{ __('Type user name here...') }}"
                            value=""
                            data-routeusers="{{ route('admin.users.find') }}"
                            data-routestatuses="{{ route('admin.users.find-statuses-eligible-for-compensation') }}"
                            data-exclude="{{ ! empty($excludeCompensation) ? implode(',', $excludeCompensation) : '' }}"
                            data-calendareventid="{{ $calendarEvent->id }}"
                            required
                        />
                    </x-admin.form.field>
                </div>

                <div class="cal-event-compensation__user-select">
                    <x-admin.form.field>
                        <select
                            class="block mt-1 w-full"
                            required
                        >
                            <option>{{ __('Waiting...') }}</option>
                        </select>
                    </x-admin.form.field>
                </div>

                <div class="mb-4 mt-4 py-1 text text-red-600 cal-event-compensation__ajax-error-msg"></div>

                {{-- display list of statusses to select for compensation --}}
                <div class="cal-event-compensation__statuses-list"></div>

                {{-- # Form to add compensation --}}
                <x-admin.form.wrapper
                    action="{{ route('admin.calendar-events.compensations.store', $calendarEvent) }}"
                    method="POST"
                    button-text=""
                    class="cal-event-compensation__form"
                >
                    <x-admin.form.field>
                        <x-admin.form.input
                            name="cal_event_compensation_user_id"
                            label=""
                            type="hidden"
                            value=""
                            required
                        />

                        <x-admin.form.input
                            name="cal_event_compensation_calendar_event_user_status_id"
                            label=""
                            type="hidden"
                            value=""
                            required
                        />

                        @if($errors->compensation->any())
                            @foreach($errors->compensation->toArray() as $field => $messages)
                                @foreach($messages as $message)
                                    <p class="text-red-600 text-xs mt-2">{{ $message }}</p>
                                @endforeach
                            @endforeach
                        @endif

                    </x-admin.form.field>
                </x-admin.form.wrapper>
            </div>

            {{-- # Compensation users --}}
            @if($compensationUsers->isNotEmpty())
                <div class="mb-12 pb-3">
                    <x-admin.singular.meta.name
                        name="{{ __('Compensation users') }}"
                    />

                    <x-admin.singular.meta.list-wrapper>
                        @foreach($compensationUsers as $compensationUser)
                            @php($compensationForThisCalendarEvent = $compensationUser->compensations()->where('calendar_event_id', $calendarEvent->id)->first())

                            <x-admin.singular.meta.item-user
                                :user="$compensationUser"
                                {{-- // TODO: make this green if it is marked as attended --}}
                                class="{{ $compensationForThisCalendarEvent->status === 'attended' ? 'singular-meta__item-user-attended' : '' }}"
                            >
                                {{-- # Properties --}}
                                <x-slot name="properties">
                                    <x-data-property-link
                                        href="{{ route('admin.users.show', $compensationUser) }}"
                                        title="{{ $compensationUser->name }}"
                                    />

                                    {{-- // This is list of added compensation users below the add form. Below we ling to compensation -> linked calendar event user status -> calendar event --}}
                                    @if($compensationUser->compensations->isNotEmpty() && $compensationForThisCalendarEvent)
                                        <x-data-property-compensation
                                            :calendarEvent="$compensationForThisCalendarEvent->calendarEventUserStatus->calendarEvent"
                                            linkText="{{ __('From ') }}"
                                        />
                                    @endif

                                </x-slot>

                                <x-admin.action-delete-button
                                    class="px-2 py-1"
                                    action="{{ route('admin.calendar-events.compensations.destroy', [$calendarEvent, $compensationUser->pivot]) }}"
                                    button-text="{{ __('Remove')}}"
                                />
                            </x-admin.singular.meta.item-user>
                            {{-- Compensation status and note Form --}}
                            <div class="mt-4 p-3 mb-12">
                                <x-admin.form.wrapper
                                    action="{{ route('admin.calendar-events.compensations.update', [$calendarEvent, $compensationForThisCalendarEvent]) }}"
                                    method="POST"
                                    button-text="{{ __('Update') }}"
                                    class="flex items-center"
                                >
                                    @method('PUT')

                                    <x-admin.form.select
                                        name="status"
                                        value="{{ $compensationForThisCalendarEvent->status ?? '' }}"
                                        label="{{ __('Status') }}"
                                        :options="$compensationStatusEnumValues"
                                        class="mr-2"
                                    />

                                    <x-admin.form.input
                                        name="note"
                                        label="{{ __('Note (max 300 chars)') }}"
                                        type="text"
                                        value="{{ $compensationForThisCalendarEvent->note ?? '' }}"
                                        class="mr-2 flex-1"
                                    />

                                </x-admin.form.wrapper>
                            </div>
                        @endforeach

                    </x-admin.singular.meta.list-wrapper>
                </div>
            @endif
            {{-- # USER COMPENSATION END --}}

            <h2>Add non group user</h2>
            <div class="text-sm text-gray-500">Add users that will be only on this event.</div>
            <div class="text-sm mb-4 text-gray-500">Do not add compensation user here.</div>
            {{-- # Slot - add users form --}}
            <x-admin.user.add-user
                route="{{ route('admin.calendar-events.users.store', [$calendarEvent, $group]) }}"
                :exclude="$exclude"
            />

            {{-- # Meta --}}
            @if(($groupUsers->isNotEmpty()) || $users->isNotEmpty() || $legacyUsers->isNotEmpty())
                <x-slot name="meta">

                    {{-- # Calendar event users not belonging to groups --}}
                    @if($users->isNotEmpty())
                        <div class="mb-4 pb-3">
                            <x-admin.singular.meta.name
                                name="{{ __('Event users') }}"
                            />

                            <x-admin.singular.meta.list-wrapper>
                                @foreach($users as $user)
                                    <x-admin.singular.meta.item-user
                                        :user="$user"
                                        class="{{ in_array($user->id, $userIdsWithAttendedStatus) ? 'singular-meta__item-user-attended' : null }}"
                                    >
                                        {{-- # Properties --}}
                                        <x-slot name="properties">
                                            <x-data-property-link
                                                href="{{ route('admin.users.show', $user) }}"
                                                title="{{ $user->name }}"
                                            />
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
                    <x-admin.calendar-event.group-users-list
                        title="{{ $group->name }} {{ __('group users') }}"
                        :group="$group"
                        :groupUsers="$groupUsers"
                        :userIdsWithAttendedStatus="$userIdsWithAttendedStatus"
                        :usersStatuses="$usersStatuses"
                        :calendarEvent="$calendarEvent"
                        :users="$users"
                        class="pt-6 px-2"
                    />

                    {{-- # Group inactive users --}}
                    <x-admin.calendar-event.group-users-list
                        title="{{ $group->name }} {{ __('group INACTIVE users') }}"
                        :group="$group"
                        :groupUsers="$groupInactiveUsers"
                        :userIdsWithAttendedStatus="$userIdsWithAttendedStatus"
                        :usersStatuses="$usersStatuses"
                        :calendarEvent="$calendarEvent"
                        :users="$users"
                        class="mt-8 bg-indigo-50 pt-6 px-2"
                    />

                    {{-- # Legacy users - users that have calendar event user status but were removed from the group --}}
                    <div class="mt-8 pt-6 px-2">
                        @if($legacyUsers->isNotEmpty())
                            <x-admin.singular.meta.name
                                name="{{ __('(LEGACY FEATURE) Users removed from group, but they have statuses for this event') }}"
                            />

                            <x-admin.singular.meta.list-wrapper>

                                @foreach($legacyUsers as $legacyUser)
                                    <x-admin.singular.meta.item-user
                                        :user="$legacyUser"
                                    >
                                        {{-- # Properties --}}
                                        <x-slot name="properties">
                                            <x-data-property-link
                                                href="{{ route('admin.users.show', $legacyUser) }}"
                                                title="{{ $legacyUser->name }}"
                                            />
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
                    </div>

                </x-slot>
            @endif

        </x-admin.singular.wrapper>
    </x-admin.main>
</x-app-layout>
