<x-app-layout>
    <x-admin.header>
        {{ __('Events') }}
    </x-admin.header>

    <x-admin.main>
        <div class="flex justify-end mb-4 px-4">
            <x-admin.data-cards.link-button href="{{ route('admin.events.create') }}" title="Create" />
        </div>
        <x-admin.data-cards.wrapper>
            <x-slot name="cards">
                @foreach($events as $event)
                    <x-admin.data-cards.card :name="$event->name">
                        <x-slot name="properties">
                            <x-admin.data-cards.property background="{{ $event->recurring ? 'bg-gray-100' : 'bg-white' }}">
                                {{ __('Recurring') }}: {{ $event->recurring ? __('Yes') : __('No') }}
                            </x-admin.data-cards.property>

                            <x-admin.data-cards.property>
                                {{ __('Starting') }}: {{ lmsCarbonPublicFormat($event->starting_at) }}
                            </x-admin.data-cards.property>

                            <x-admin.data-cards.property>
                                {{ __('Ending') }}: {{ lmsCarbonPublicFormat($event->ending_at) }}
                            </x-admin.data-cards.property>

                            @if($event->recurring)
                                <x-admin.data-cards.property>
                                    {{ __('Recurring until') }}: {{ lmsCarbonPublicFormat($event->recurring_until) }}
                                </x-admin.data-cards.property>
                            @endif
                        </x-slot>

                        <x-admin.data-cards.link
                            href="{{ route('admin.events.edit', [$event]) }}"
                            title="Edit" />

                        <x-admin.form.delete-button action="{{ route('admin.events.destroy', [$event]) }}" />
                    </x-admin.data-cards.card>
                @endforeach
            </x-slot>
            <x-slot name="pagination">
                {{ $events->links() }}
            </x-slot>
        </x-admin.data-cards.wrapper>
    </x-admin.main>
</x-app-layout>
