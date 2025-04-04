@php
    /**
     * Enable autocomplete in blade file
     * @var \App\Models\User $user
     * @var \App\Models\Group $group
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
            <x-admin.action-link-button href="{{ route('admin.users.show', $user) }}" title="{{ __('Back to user') }}" />
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

                <x-admin.singular.info
                    name="{{ __('Roles') }}"
                    value="{{ $user->getRolesString() }}"
                />

                <x-admin.singular.info
                    name="{{ __('Active') }}"
                    value="{{ $user->active ? __('Yes') : __('No') }}"
                />
            </x-slot>


            {{-- # Meta --}}
            <x-slot name="meta">
                <x-admin.singular.meta.name
                    name="{{ __('User groups history, sorted by courses') }}"
                />

                @if($groupsSortedByCourseName->isNotEmpty())
                    @foreach($groupsSortedByCourseName as $courseName => $groupsCollection)
                        <x-admin.singular.meta.item-wrapper class="bg-gray-100 text-lg">
                           {{ __('Course') }}: {{ $courseName }}
                        </x-admin.singular.meta.item-wrapper>

                        <x-admin.singular.meta.list-wrapper>
                            @foreach($groupsCollection as $group)
                                @php
                                    $startDate = Carbon\Carbon::parse($group->starting_at)->startOfMonth();
                                    $endDate = Carbon\Carbon::parse($group->ending_at)->endOfMonth();
                                @endphp

                                <x-admin.singular.meta.item-wrapper>
                                    <x-admin.singular.meta.item-icon>
                                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000"><path d="M411-480q-28 0-46-21t-13-49l12-72q8-43 40.5-70.5T480-720q44 0 76.5 27.5T597-622l12 72q5 28-13 49t-46 21H411Zm24-80h91l-8-49q-2-14-13-22.5t-25-8.5q-14 0-24.5 8.5T443-609l-8 49ZM124-441q-23 1-39.5-9T63-481q-2-9-1-18t5-17q0 1-1-4-2-2-10-24-2-12 3-23t13-19l2-2q2-19 15.5-32t33.5-13q3 0 19 4l3-1q5-5 13-7.5t17-2.5q11 0 19.5 3.5T208-626q1 0 1.5.5t1.5.5q14 1 24.5 8.5T251-596q2 7 1.5 13.5T250-570q0 1 1 4 7 7 11 15.5t4 17.5q0 4-6 21-1 2 0 4l2 16q0 21-17.5 36T202-441h-78Zm676 1q-33 0-56.5-23.5T720-520q0-12 3.5-22.5T733-563l-28-25q-10-8-3.5-20t18.5-12h80q33 0 56.5 23.5T880-540v20q0 33-23.5 56.5T800-440ZM0-240v-63q0-44 44.5-70.5T160-400q13 0 25 .5t23 2.5q-14 20-21 43t-7 49v65H0Zm240 0v-65q0-65 66.5-105T480-450q108 0 174 40t66 105v65H240Zm560-160q72 0 116 26.5t44 70.5v63H780v-65q0-26-6.5-49T754-397q11-2 22.5-2.5t23.5-.5Zm-320 30q-57 0-102 15t-53 35h311q-9-20-53.5-35T480-370Zm0 50Zm1-280Z"/></svg>
                                    </x-admin.singular.meta.item-icon>

                                    <x-data-property-link
                                        class="text-base mx-2 sm--lms-mb-10"
                                        href="{{ route('admin.groups.show', [$group]) }}"
                                        title="{{ $group->name }}"
                                        style="margin: 0;"
                                    />

                                    <x-admin.singular.meta.item-properties-wrapper class="flex-1 justify-end">
                                        <div class="lg:flex justify-between items-center">
                                            <x-data-property>
                                                {{ __('Joined') }}: {{ lmsCarbonDateFormat($group->pivot->created_at) }}
                                            </x-data-property>
                                        </div>
                                    </x-admin.singular.meta.item-properties-wrapper>

                                </x-admin.singular.meta.item-wrapper>

                            @endforeach

                        </x-admin.singular.meta.list-wrapper>
                    @endforeach
                @else
                    <h4 class="pl-3 pr-4 py-2 text-gray-500">{{ __('User has no groups membership') }}</h4>
                @endif

            </x-slot>


        </x-admin.singular.wrapper>
    </x-admin.main>
</x-app-layout>
