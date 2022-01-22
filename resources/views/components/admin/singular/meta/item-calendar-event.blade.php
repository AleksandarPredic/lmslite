@props(['calendarEvent'])

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
