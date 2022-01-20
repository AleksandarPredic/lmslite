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

        {{-- # https://tailwindui.com/components/application-ui/data-display/description-lists --}}
        <div class="bg-white shadow overflow-hidden sm:rounded-lg event-preview">
            <div class="px-4 py-6 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    {{ $event->name }}
                </h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">
                    {{ __('Recurring') }}: {{ $event->recurring ? __('Yes') : __('No') }}
                </p>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">
                    {{ __('Group') }}: {{ $event->group ? $event->group->name : __('none') }}
                </p>
            </div>
            <div class="border-t border-gray-200 pb-3">
                <dl>
                    <div class="bg-white px-4 py-2">
                        <dt class="text-sm font-medium text-gray-500">
                            {{ __('Starting') }}
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            {{ lmsCarbonPublicFormat($event->starting_at) }}
                        </dd>
                    </div>
                    <div class="bg-white px-4 py-2">
                        <dt class="text-sm font-medium text-gray-500">
                            {{ __('Ending') }}
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            {{ lmsCarbonPublicFormat($event->ending_at) }}
                        </dd>
                    </div>

                    @if($event->recurring)
                        <div class="bg-white px-4 py-2">
                            <dt class="text-sm font-medium text-gray-500">
                                {{ __('Recurring until') }}
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                {{ lmsCarbonPublicFormat($event->recurring_until) }}
                            </dd>
                        </div>
                        <div class="bg-white px-4 py-2">
                            <dt class="text-sm font-medium text-gray-500">
                                {{ __('Days') }}
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                {{ implode(', ', $event->getDaysAsNames()) }}
                            </dd>
                        </div>
                    @endif

                    <div class="bg-white px-4 py-2">
                        <dt class="text-sm font-medium text-gray-500">
                            {{ __('Note') }}
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            {{ $event->note }}
                        </dd>
                    </div>
                </dl>
            </div>

            @if ($event->calendarEvents)
            <div class="border-t border-gray-200 py-6 px-4 event-preview__calendar_events">
                <h3 class="mb-4">{{ __('Calendar events') }}</h3>

                <ul role="list" class="border border-gray-200 rounded-md">

                    @php($separatedOld = false)
                    @foreach($calendarEvents as $calendarEvent)

                        {{-- # Mark where active CalendarEvent starts, from Event start_at. We may have old ones for history. --}}
                        @if($calendarEvent->starting_at >= $event->starting_at && ! $separatedOld)
                            <li class="pl-3 pr-4 py-2 flex items-center justify-between text-sm border-b border-gray-200 bg-gray-100">
                                {{ __('Current events') }} <small>{{ __('If there are events before this line, those are for history keeping') }}</small>
                            </li>
                            @php($separatedOld = true)
                        @endif

                        <li class="pl-3 pr-4 py-2 flex items-center justify-between text-sm border-b border-gray-200">
                            <div class="w-0 flex-1 flex items-center">
                                <span class="ml-2 flex-1 w-0 truncate">
                                  <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="#000000"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M19 4h-1V2h-2v2H8V2H6v2H5c-1.11 0-1.99.9-1.99 2L3 20c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V10h14v10zm0-12H5V6h14v2zm-7 5h5v5h-5z"/></svg>
                                </span>
                            </div>
                            <div class="ml-4 flex-shrink-0 sm:flex">
                                <x-admin.data-property>
                                    {{ __('Starting') }}: {{ lmsCarbonPublicFormat($calendarEvent->starting_at) }}
                                </x-admin.data-property>

                                <x-admin.data-property class="ml-4">
                                    {{ __('Ending') }}: {{ lmsCarbonPublicFormat($calendarEvent->ending_at) }}
                                </x-admin.data-property>

                                {{-- // TODO: Add delete button for single calendar event as a way to cancel only one and remove it from calendar + this must cascade delete overrides --}}
                            </div>
                        </li>
                    @endforeach

                </ul>
            </div>
            @endif

        </div>

    </x-admin.main>
</x-app-layout>
