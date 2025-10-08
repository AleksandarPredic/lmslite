@props([
    'title',
    'group',
    'groupUsers',
    'userIdsWithAttendedStatus',
    'usersStatuses',
    'calendarEvent',
    'users'
])

{{-- # Group users list, active and inactive users from the group --}}
@if($groupUsers->isNotEmpty())
    <div {!! $attributes->merge(['class' => 'calendar-event-grup-users']) !!}>
        <x-admin.singular.meta.name
            name="{{ $title}}"
        />

        <x-admin.singular.meta.list-wrapper>

            @foreach($groupUsers as $groupUser)
                <x-admin.singular.meta.item-user
                    :user="$groupUser"
                    class="{{ in_array($groupUser->id, $userIdsWithAttendedStatus) ? 'singular-meta__item-user-attended' : null }}"
                >
                    {{-- # Properties --}}
                    <x-slot name="properties">
                        <x-data-property-link
                            href="{{ route('admin.users.show', $groupUser) }}"
                            title="{{ $groupUser->name }}"
                        />

                        <x-admin.action-link-button href="{{ route('admin.users.payments.index', $groupUser) }}" title="{{ __('Payments') }}" />

                        {{-- Link to compensation Calendar event as this is where the compensation is added --}}
                        @if($groupUser->compensations->isNotEmpty())
                            @php
                                $filteredCompensations = $groupUser->compensations->filter(function ($compensation) use ($calendarEvent) {
                                    /* Compare with calendarEventUserStatus->calendar_event_id as this is how we know which compensation status is bined to this calendar event */
                                    return $compensation->calendarEventUserStatus->calendar_event_id === $calendarEvent->id;
                                });
                            @endphp

                            @if($filteredCompensations->isNotEmpty())
                                <x-data-property-compensation-trigger
                                    :compensation="$filteredCompensations->first()"
                                    linkText="{{ __('Compensated on') }}"
                                />
                            @endif
                        @endif

                    </x-slot>

                    {{-- # Links --}}

                    {{--
                        # If this user is already in the calendar event, don't show the status
                        # It is already displayed up in the calenar event users
                    --}}
                    @if(! $users->find($groupUser))
                        <x-admin.calendar-event.user.status
                            :calendarEvent="$calendarEvent"
                            :user="$groupUser"
                            :userStatuses="$usersStatuses"
                        />
                    @else
                        <div>Added to this event while he was not a group member.</div>
                    @endif
                </x-admin.singular.meta.item-user>
            @endforeach

        </x-admin.singular.meta.list-wrapper>
    </div>
@endif
