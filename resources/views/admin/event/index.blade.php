<x-app-layout>
    <x-admin.header>
        {{ __('Events') }}
    </x-admin.header>

    <x-admin.main>
        <div class="flex justify-end mb-4 px-4">
            <x-admin.action-link-button href="{{ route('admin.events.create') }}" title="{{ __('Create') }}" />
        </div>

        <x-data-cards.wrapper>
            <x-slot name="cards">
                @php
                /**
                 * @var \App\Models\Event $event
                 */

                $icon = '<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="#000000"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M20 3h-1V1h-2v2H7V1H5v2H4c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 18H4V10h16v11zm0-13H4V5h16v3z"/></svg>';
                @endphp
                @foreach($events as $event)
                    {{-- Event properties --}}
                    <x-data-cards.card
                        :name="$event->name"
                        :svg="$icon"
                    >
                        <x-slot name="properties">
                            <x-data-property class="{{ $event->recurring ? 'bg-gray-100' : 'bg-white' }}">
                                {{ __('Recurring') }}: {{ $event->recurring ? __('Yes') : __('No') }}
                            </x-data-property>

                            <x-data-property>
                                {{ __('Starting') }}: {{ lmsCarbonPublicFormat($event->starting_at) }}
                            </x-data-property>

                            <x-data-property>
                                {{ __('Ending') }}: {{ lmsCarbonPublicFormat($event->ending_at) }}
                            </x-data-property>

                            @if($event->recurring)
                                <x-data-property>
                                    {{ __('Recurring until') }}: {{ lmsCarbonPublicFormat($event->recurring_until) }}
                                </x-data-property>
                            @endif

                            <x-data-property>
                                {{ __('Group') }}: {{ $event->group ? $event->group->name : 'None' }}
                            </x-data-property>
                        </x-slot>

                        {{-- Event action links --}}
                        <x-link
                            href="{{ route('admin.events.show', [$event]) }}"
                            title="{{ __('Manage') }}" />

                        <x-link
                            href="{{ route('admin.events.edit', [$event]) }}"
                            title="{{ __('Edit') }}" />

                        <x-admin.form.delete-button
                            action="{{ route('admin.events.destroy', [$event]) }}"
                        />
                    </x-data-cards.card>
                @endforeach
            </x-slot>

            <x-slot name="pagination">
                {{ $events->links() }}
            </x-slot>
        </x-data-cards.wrapper>
    </x-admin.main>
</x-app-layout>
