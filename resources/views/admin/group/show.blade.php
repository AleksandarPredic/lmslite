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
                    name="{{ __('Note') }}"
                    value="{{ $group->note }}"
                />
            </x-slot>

            {{-- # Meta --}}
            <x-slot name="meta">
                <x-admin.singular.meta.name
                    name="{{ __('Users') }}"
                />

                <x-admin.singular.meta.list-wrapper>

                    @foreach($group->users as $user)
                        <x-admin.singular.meta.item-user-group
                            :user="$user"
                            :group="$group"
                        />
                    @endforeach

                </x-admin.singular.meta.list-wrapper>
            </x-slot>

        </x-admin.singular.wrapper>
    </x-admin.main>
</x-app-layout>
