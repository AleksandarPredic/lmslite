<x-app-layout>
    <x-admin.header>
        {{ __('Users') }}
    </x-admin.header>

    <x-admin.main>
        <div class="flex justify-end mb-4 px-4">
            <x-admin.action-link-button href="{{ route('admin.users.create') }}" title="{{ __('Create') }}" />
        </div>

        <x-admin.resource-index-search
            route="{{ route('admin.users.index') }}"
        />

        <x-data-cards.wrapper>
            <x-slot name="cards">
                @php
                /**
                 * @var \App\Models\User $user
                 */
                @endphp
                @foreach($users as $user)
                    {{-- Event properties --}}
                    <x-data-cards.card
                        :name="$user->name"
                        imageSrc="{{ $user->imageSrcUrl() }}"
                    >
                        <x-slot name="properties">
                            <x-data-property>
                                {{ __('Name') }}: {{ $user->name }}
                            </x-data-property>

                            <x-data-property>
                                {{ __('Groups') }}: {{ $user->groups->isNotEmpty() ? $user->groups->implode('name', ', ') : 'None' }}
                            </x-data-property>

                            @if($user->date_of_birth)
                                <x-data-property>
                                    {{ __('Date of birth') }}: {{ lmsCarbonDateFormat($user->date_of_birth) }}
                                </x-data-property>
                            @endif

                            @if($user->sign_up_date)
                                <x-data-property>
                                    {{ __('Sign up date') }}: {{ lmsCarbonDateFormat($user->sign_up_date) }}
                                </x-data-property>
                            @endif

                            <x-data-property>
                                {{ __('Roles') }}: {{ $user->getRolesString() }}
                            </x-data-property>

                            <x-data-property class="{{ $user->active ? 'bg-white' : 'bg-gray-100'  }}">
                                {{ __('Active') }}: {{ $user->active ? __('Yes') : __('No') }}
                            </x-data-property>
                        </x-slot>

                        {{-- Event action links --}}
                        <x-link
                            href="{{ route('admin.users.show', [$user]) }}"
                            title="{{ __('View') }}" />

                        <x-link
                            href="{{ route('admin.users.edit', [$user]) }}"
                            title="{{ __('Edit') }}" />

                        <x-admin.form.delete-button
                            action="{{ route('admin.users.destroy', [$user]) }}"
                        />
                    </x-data-cards.card>
                @endforeach
            </x-slot>

            <x-slot name="pagination">
                {{ $users->links() }}
            </x-slot>
        </x-data-cards.wrapper>
    </x-admin.main>
</x-app-layout>
