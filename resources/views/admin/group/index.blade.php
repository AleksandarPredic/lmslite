<x-app-layout>
    <x-admin.header>
        {{ __('Groups') }}
    </x-admin.header>

    <x-admin.main>
        <div class="flex justify-end mb-4 px-4">
            <x-admin.action-link-button href="{{ route('admin.groups.create') }}" title="{{ __('Create') }}" />
        </div>

        <x-data-cards.wrapper>
            <x-slot name="cards">
                @foreach($groups as $group)
                    <x-data-cards.card :name="$group->name">
                        <x-slot name="properties">
                            <x-data-property>
                                {{ __('Starting') }}: {{ lmsCarbonPublicFormat($group->starting_at) }}
                            </x-data-property>

                            <x-data-property>
                                {{ __('Ending') }}: {{ lmsCarbonPublicFormat($group->ending_at) }}
                            </x-data-property>
                        </x-slot>

                        {{-- Group action links --}}
                        <x-link
                            href="{{ route('admin.groups.show', [$group]) }}"
                            title="{{ __('Manage') }}" />

                        <x-link
                            href="{{ route('admin.groups.edit', [$group]) }}"
                            title="{{ __('Edit') }}" />

                        <x-admin.form.delete-button action="{{ route('admin.groups.destroy', [$group]) }}" />
                    </x-data-cards.card>
                @endforeach
            </x-slot>

            <x-slot name="pagination">
                {{ $groups->links() }}
            </x-slot>
        </x-data-cards.wrapper>
    </x-admin.main>
</x-app-layout>
