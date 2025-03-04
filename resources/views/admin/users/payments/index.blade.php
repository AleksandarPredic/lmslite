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

                    @foreach($user->groups as $group)
                        <x-admin.singular.meta.name
                            name="{{ $group->name }}"
                        />

                        <x-admin.singular.meta.list-wrapper>

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

                            @foreach($months as $month)
                                <x-admin.singular.meta.info
                                    name="{{ $month['name'] }}"
                                    value="{{ __('Payment status: Not paid') }}"
                                />
                            @endforeach

                        </x-admin.singular.meta.list-wrapper>
                    @endforeach

                </x-slot>


        </x-admin.singular.wrapper>
    </x-admin.main>
</x-app-layout>
