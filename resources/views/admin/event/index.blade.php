<x-app-layout>
    <x-admin.header>
        {{ __('Events') }}
    </x-admin.header>

    <x-admin.main>
        <div class="flex justify-end mb-4 px-4">
            <x-admin.action-link-button href="{{ route('admin.events.create') }}" title="{{ __('Create') }}" />
        </div>

        <x-admin.data-cards.wrapper>
            <x-slot name="cards">
                @foreach($events as $event)
                    {{-- Event properties --}}
                    <x-admin.data-cards.card :name="$event->name">
                        <x-slot name="properties">
                            <x-admin.data-property class="{{ $event->recurring ? 'bg-gray-100' : 'bg-white' }}">
                                {{ __('Recurring') }}: {{ $event->recurring ? __('Yes') : __('No') }}
                            </x-admin.data-property>

                            <x-admin.data-property>
                                {{ __('Starting') }}: {{ lmsCarbonPublicFormat($event->starting_at) }}
                            </x-admin.data-property>

                            <x-admin.data-property>
                                {{ __('Ending') }}: {{ lmsCarbonPublicFormat($event->ending_at) }}
                            </x-admin.data-property>

                            @if($event->recurring)
                                <x-admin.data-property>
                                    {{ __('Recurring until') }}: {{ lmsCarbonPublicFormat($event->recurring_until) }}
                                </x-admin.data-property>
                            @endif

                            <x-admin.data-property>
                                {{ __('Group') }}: {{ $event->group ? $event->group->name : 'None' }}
                            </x-admin.data-property>
                        </x-slot>

                        {{-- Event action links --}}
                        <x-admin.data-cards.link
                            href="{{ route('admin.events.show', [$event]) }}"
                            title="{{ __('Manage') }}" />

                        <x-admin.data-cards.link
                            href="{{ route('admin.events.edit', [$event]) }}"
                            title="{{ __('Edit') }}" />

                        <x-admin.form.delete-button
                            action="{{ route('admin.events.destroy', [$event]) }}"
                        />
                    </x-admin.data-cards.card>
                @endforeach
            </x-slot>

            <x-slot name="pagination">
                {{ $events->links() }}
            </x-slot>
        </x-admin.data-cards.wrapper>
    </x-admin.main>
</x-app-layout>
