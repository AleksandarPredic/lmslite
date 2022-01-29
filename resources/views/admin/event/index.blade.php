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
                @foreach($events as $event)
                    {{-- Event properties --}}
                    <x-data-cards.card :name="$event->name">
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
