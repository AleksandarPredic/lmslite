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
                    name="{{ __('Course') }}"
                    value="{{ $group->course ? $group->course->name : __('No course assigned') }}"
                />

                <x-admin.singular.property
                    name="{{ __('Price') }}"
                    value="{{ lmsPricePublicFormat($group->price) }}"
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

                <x-admin.singular.meta.list-wrapper class="group-users-list">

                    @php
                        $showHeadlineForInactiveUsers = true;
                    @endphp
                    @foreach($users as $user)
                        @php
                            $userInactive = $user->pivot->inactive;
                        @endphp

                        @if($userInactive && $showHeadlineForInactiveUsers)
                            <li class="group-users-list__inactive-separator mb-4 px-2">
                                <div>{{ __('Inactive users') }}</div>
                            </li>
                            @php
                                $showHeadlineForInactiveUsers = false;
                            @endphp
                        @endif

                        @php
                            /**
                             * @var \App\Models\User $user
                             */
                        @endphp
                        <x-admin.singular.meta.item-user
                            :user="$user"
                            class="group-users-list__user {{ $userInactive ? 'group-users-list__user--inactive bg-indigo-50 border-indigo-400' : 'group-users-list__user--active' }}"
                        >
                            {{-- # Properties --}}
                            <x-slot name="properties">
                                <x-data-property-link
                                    href="{{ route('admin.users.show', $user) }}"
                                    title="{{ $user->name }}"
                                />
                            </x-slot>

                            <x-admin.group.action-toogle-inactive-user
                                :group="$group"
                                :user="$user"
                            />

                            {{-- Discount assignment form --}}
                            <form
                                method="POST"
                                action="{{ route('admin.groups.users.update-discount', [$group, $user]) }}"
                                class="inline-flex items-center gap-2 border rounded"
                                onsubmit="return confirm('Are you sure you want to add discount for {{ $user->name }}?');"
                            >
                                @csrf
                                @method('PATCH')
                                @php
                                    $currentDiscount = $user->pivot->discount_amount ?? 0;
                                    $hasDiscount = $currentDiscount > 0;
                                @endphp
                                <div class="flex items-center gap-2 px-2" style="background-color: {{ $hasDiscount ? '#f2dbdb' : 'white' }};">
                                    <label class="text-xs text-gray-600 mr-2">{{ __('Discount:') }}</label>
                                    <input
                                        type="number"
                                        name="discount_amount"
                                        step="0.01"
                                        min="0"
                                        max="{{ $group->price }}"
                                        value="{{ $currentDiscount }}"
                                        class="text-sm border-gray-300 rounded-md shadow-sm w-24 mr-4"
                                    />

                                    <button type="submit" class="font-bold py-2 px-2 rounded bg-gray-100 mb-2 mt-2">
                                        {{ __('Update discount') }}
                                    </button>
                                </div>
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
