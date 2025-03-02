@php
/**
 * Enable autocomplete in blade file
 * @var \App\Models\Group $group
 */
@endphp
<x-app-layout>
    <x-admin.header>
        {{ __('Preview group') }}
    </x-admin.header>

    <x-admin.main>
        <div class="flex mb-4">
            <x-admin.redirect-link href="{{ route('admin.groups.index') }}" :title="__('Back to all!')" />
        </div>

        <div class="flex justify-end mb-4 px-4">
            <x-admin.action-link-button href="{{ route('admin.groups.edit', $group) }}" title="{{ __('Edit') }}" />
            <x-admin.action-delete-button action="{{ route('admin.groups.destroy', $group) }}" />
        </div>

        <x-admin.singular.wrapper>
            {{-- # Header --}}
            <x-slot name="info">
                <x-admin.singular.name
                    name="{{ $group->name }}"
                />

                <x-admin.singular.info
                    name="{{ __('Number of users') }}"
                    value="{{ $group->users ? $group->users->count() : 0 }}"
                />
            </x-slot>

            {{-- # Properties --}}
            <x-slot name="properties">
                <x-admin.singular.property
                    name="{{ __('Starting at') }}"
                    value="{{ lmsCarbonPublicFormat($group->starting_at) }}"
                />

                <x-admin.singular.property
                    name="{{ __('Ending at') }}"
                    value="{{ lmsCarbonPublicFormat($group->ending_at) }}"
                />

                <x-admin.singular.property
                    name="{{ __('Price 1') }}"
                    value="{{ lmsPricePublicFormat($group->price_1) }}"
                />

                <x-admin.singular.property
                    name="{{ __('Price 2') }}"
                    value="{{ lmsPricePublicFormat($group->price_2) }}"
                />

                <x-admin.singular.property
                    name="{{ __('Note') }}"
                    value="{{ $group->note }}"
                />

                <x-admin.singular.property
                    name="{{ __('Active') }}"
                    value="{{ $group->active ? __('Yes') : __('No') }}"
                />
            </x-slot>

            {{-- # Slot - add users form --}}
            <x-admin.user.add-user
                route="{{ route('admin.groups.users.store', $group) }}"
                :exclude="$exclude"
            />

            {{-- # Meta --}}
            <x-slot name="meta">
                <x-admin.singular.meta.name
                    name="{{ __('Users') }}"
                />

                <x-admin.singular.meta.list-wrapper>

                    @foreach($users as $user)
                        @php
                            /**
                             * @var \App\Models\User $user
                             */
                        @endphp
                        <x-admin.singular.meta.item-user
                            :user="$user"
                        >
                            {{-- # Properties --}}
                            <x-slot name="properties">
                                <x-data-property-link
                                    href="{{ route('admin.users.show', $user) }}"
                                    title="{{ $user->name }}"
                                />
                            </x-slot>

                            {{-- Price assignment form --}}
                            <form method="POST" action="{{ route('admin.groups.users.update-price-type', [$group, $user]) }}">
                                @csrf
                                @method('PATCH')
                                @php
                                    $selectedPriceType = $user->getUserPivotPriceType();
                                    $priceSelectBackground = $selectedPriceType === 'price_1' ? 'white' : '#f2dbdb';
                                @endphp
                                <select name="price_type" class="text-sm border-gray-300 rounded-md shadow-sm" onchange="this.form.submit()" style="background-color: {{ $priceSelectBackground }};">
                                    <option value="price_1" {{ $selectedPriceType === 'price_1' || empty($selectedPriceType) ? 'selected' : '' }}>
                                        {{ __('Price 1') }}: {{ lmsPricePublicFormat($group->price_1) }}
                                    </option>
                                    <option value="price_2" {{ $selectedPriceType === 'price_2' ? 'selected' : '' }}>
                                        {{ __('Price 2') }}: {{ lmsPricePublicFormat($group->price_2) }}
                                    </option>
                                </select>
                            </form>

                            {{-- # Links --}}
                            <x-admin.action-delete-button
                                class="px-2 py-1"
                                action="{{ route('admin.groups.users.destroy', [$group, $user]) }}"
                                button-text="{{ __('Remove')}}"
                            />
                        </x-admin.singular.meta.item-user>
                    @endforeach

                </x-admin.singular.meta.list-wrapper>
            </x-slot>

        </x-admin.singular.wrapper>
    </x-admin.main>
</x-app-layout>
