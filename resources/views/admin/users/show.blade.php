@php
/**
 * Enable autocomplete in blade file
 * @var \App\Models\User $user
 */
@endphp
<x-app-layout>
    <x-admin.header>
        {{ __('Preview user') }}
    </x-admin.header>

    <x-admin.main>
        <div class="flex mb-4">
            <x-admin.redirect-link href="{{ route('admin.users.index') }}" :title="__('Back to all!')" />
        </div>

        <div class="flex justify-end mb-4 px-4">
            <x-admin.action-link-button href="{{ route('admin.users.create') }}" title="{{ __('Create') }}" />
            <x-admin.action-link-button href="{{ route('admin.users.edit', $user) }}" title="{{ __('Edit') }}" />
            <x-admin.action-delete-button action="{{ route('admin.users.destroy', $user) }}" />
        </div>

        <x-admin.singular.wrapper>
            {{-- # Header --}}
            <x-slot name="info">
                <x-admin.singular.item>
                    <img src="{{ $user->imageSrcUrl() }}" alt="User image" />
                </x-admin.singular.item>

                <x-admin.singular.name
                    name="{{ $user->name }}"
                />

                @if($user->groups->isNotEmpty())
                    <x-admin.singular.item
                        class="text-sm text-gray-500"
                    >
                        {{ __('Groups') }}
                        @foreach($user->groups as $group)
                            <a href="{{ route('admin.groups.show', $group) }}">{{ $group->name }}</a>
                        @endforeach
                    </x-admin.singular.item>
                @else
                    <x-admin.singular.info
                        name="{{ __('Groups') }}"
                        value="{{ 'None' }}"
                    />
                @endif

                <x-admin.singular.info
                    name="{{ __('Roles') }}"
                    value="{{ $user->getRolesString() }}"
                />

                <x-admin.singular.info
                    name="{{ __('Active') }}"
                    value="{{ $user->active ? __('Yes') : __('No') }}"
                />

                <x-admin.singular.info
                    name="{{ __('Media consent') }}"
                    value="{{ $user->media_consent ? __('Yes') : __('No') }}"
                />

                <div class="flex justify-end mt-4 px-4">
                    <x-admin.action-link-button href="{{ route('admin.users.payments.index', $user) }}" title="{{ __('Payments') }}" />
                    <x-admin.action-link-button href="{{ route('admin.users.nextCalendarEvents', $user) }}" title="{{ __('Next Events') }}" />
                    <x-admin.action-link-button href="{{ route('admin.users.groups-history', $user) }}" title="{{ __('History') }}" />
                </div>
            </x-slot>

            {{-- # Properties --}}
            <x-slot name="properties">
                <x-admin.singular.property
                    name="{{ __('First parent name') }}"
                    value="{{ $user->parent_1_name }}"
                />

                <x-admin.singular.property
                    name="{{ __('First parent phone') }}"
                    value="{{ $user->parent_1_phone }}"
                />

                <x-admin.singular.property
                    name="{{ __('Second parent name') }}"
                    value="{{ $user->parent_2_name }}"
                />

                <x-admin.singular.property
                    name="{{ __('Second parent phone') }}"
                    value="{{ $user->parent_2_phone }}"
                />

                <x-admin.singular.property
                    name="{{ __('Date of birth') }}"
                    value="{{ $user->date_of_birth ? lmsCarbonDateFormat($user->date_of_birth) : '' }}"
                />

                <x-admin.singular.property
                    name="{{ __('Address') }}"
                    value="{{ $user->address }}"
                />

                <x-admin.singular.property
                    name="{{ __('School') }}"
                    value="{{ $user->school }}"
                />

                <x-admin.singular.property
                    name="{{ __('School info') }}"
                    value="{{ $user->school_info }}"
                />

                <x-admin.singular.property
                    name="{{ __('Sign up date') }}"
                    value="{{ $user->sign_up_date ? lmsCarbonDateFormat($user->sign_up_date) : '' }}"
                />

                <x-admin.singular.property
                    name="{{ __('Note') }}"
                    value="{{ $user->note }}"
                />

                <x-admin.singular.property
                    name="{{ __('Payment note') }}"
                    value="{{ $user->payment_note }}"
                />
            </x-slot>

        </x-admin.singular.wrapper>
    </x-admin.main>
</x-app-layout>
