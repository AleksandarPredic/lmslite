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
            </x-slot>


                {{-- # Meta --}}
                <x-slot name="meta">

                    <x-admin.singular.meta.name
                        name="{{ __('User groups') }}"
                    />

                    @foreach($user->groups as $group)

                        @php
                            // Use group's starting_at and ending_at dates
                            $startDate = Carbon\Carbon::parse($group->starting_at)->startOfMonth();
                            $endDate = Carbon\Carbon::parse($group->ending_at)->endOfMonth();

                            // Use CarbonPeriod to create a period with 1 month interval
                            $period = Carbon\CarbonPeriod::create($startDate, '1 month', $endDate);

                            // Generate array of months
                            $months = [];
                            foreach ($period as $date) {
                                $months[] = [
                                    'date' => $date->copy(),
                                    'name' => $date->format('F Y')
                                ];
                            }
                        @endphp

                        <br />
                        <x-admin.singular.meta.info
                            name="{{ $group->name }}"
                            value=""
                        />

                        <x-admin.singular.meta.list-wrapper>

                            @foreach($months as $month)
                                <x-admin.singular.meta.item-wrapper>
                                    <x-admin.singular.meta.item-icon>
                                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="#000000"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M19 4h-1V2h-2v2H8V2H6v2H5c-1.11 0-1.99.9-1.99 2L3 20c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V10h14v10zm0-12H5V6h14v2zm-7 5h5v5h-5z"/></svg>
                                    </x-admin.singular.meta.item-icon>


                                    <div class="font-bold text-lg">{{ $month['name'] }}</div>


                                    <x-admin.singular.meta.item-properties-wrapper class="flex-1 justify-end">
                                        <x-data-property>
                                            {{ __('Status') }}: {{ __('Not paid') }}
                                        </x-data-property>

                                        <x-data-property>
                                            {{ __('Date') }}: {{ '14.02.2024.' }}
                                        </x-data-property>

                                        <x-data-property>
                                            {{ __('Amount') }}: {{ '3500' }}
                                        </x-data-property>
                                    </x-admin.singular.meta.item-properties-wrapper>

                                </x-admin.singular.meta.item-wrapper>

                            @endforeach

                        </x-admin.singular.meta.list-wrapper>
                    @endforeach

                </x-slot>


        </x-admin.singular.wrapper>
    </x-admin.main>
</x-app-layout>
