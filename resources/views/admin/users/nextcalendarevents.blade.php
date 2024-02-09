@php
/**
 * Enable autocomplete in blade file
 * @var \App\Models\User $user
 * @var \App\Models\Group $group
 * @var \Illuminate\Database\Eloquent\Collection $calendarEvents
 * @var \App\Models\CalendarEvent $calendarEvent
 */
@endphp
<x-app-layout>
    <x-admin.header>
        {{ __('Preview user') }}
    </x-admin.header>

    <x-admin.main>
        <div class="flex mb-4">
            <x-admin.redirect-link href="{{ route('admin.users.index') }}" :title="__('Back to all users!')" />
        </div>

        <div class="flex justify-end mb-4 px-4">
            <x-admin.action-link-button href="{{ route('admin.users.show', $user) }}" title="{{ __('Back to user') }}" />
        </div>

        <x-admin.singular.wrapper>
            {{-- # Header --}}
            <x-slot name="info">
                <x-admin.singular.item>
                    <img src="{{ $user->imageSrcUrl() }}" alt="User image" />
                </x-admin.singular.item>

                <x-admin.singular.name
                    name="{{ $user->name }}"
                />

                @if($user->groups->isNotEmpty())
                    <x-admin.singular.item
                        class="text-sm text-gray-500"
                    >
                        {{ __('Groups') }}
                        @foreach($user->groups as $group)
                            <a href="{{ route('admin.groups.show', $group) }}">{{ $group->name }}</a>
                        @endforeach
                    </x-admin.singular.item>
                @else
                    <x-admin.singular.info
                        name="{{ __('Groups') }}"
                        value="{{ 'None' }}"
                    />
                @endif

                <x-admin.singular.info
                    name="{{ __('Roles') }}"
                    value="{{ $user->getRolesString() }}"
                />

                <x-admin.singular.info
                    name="{{ __('Active') }}"
                    value="{{ $user->active ? __('Yes') : __('No') }}"
                />
            </x-slot>

            {{-- # Meta --}}
            <x-slot name="meta">

                {{-- # Next calendar events --}}
                <div class="mb-4 px-2">
                    <h2 class="text-lg"><strong>{{ __('User next 5 calendar events') }}</strong></h2>
                    <small>(Results are cached for 10 minutes)</small>

                    <div>
                        @if($calendarEvents->isNotEmpty())
                            @foreach($calendarEvents as $calendarEvent)
                                <x-admin.singular.meta.item-calendar-event
                                    :calendar-event="$calendarEvent"
                                />
                            @endforeach
                        @endif
                    </div>
                </div>
            </x-slot>

        </x-admin.singular.wrapper>
    </x-admin.main>
</x-app-layout>
