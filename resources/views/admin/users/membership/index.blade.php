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
            <x-admin.redirect-link href="{{ route('admin.users.index') }}" :title="__('Back to all!')" />
        </div>

        <div class="flex justify-end mb-4 px-4">
            <x-admin.action-link-button href="{{ route('admin.users.show', $user) }}" title="{{ __('Back to profile') }}" />
        </div>

        <x-admin.singular.wrapper>
            {{-- # Header --}}
            <x-slot name="info">
                <x-admin.singular.name
                    name="User membership"
                />

                <x-admin.singular.info
                    name="{{ __('User') }}"
                    value="{{ $user->name }}"
                />
            </x-slot>

            {{-- # Meta --}}
            <x-slot name="meta">
                <x-admin.singular.meta.name
                    name="{{ __('Groups') }}"
                />

                <hr class="mb-4"/>

                @php
                    /**
                    * @var array{id:int, name:string, months:array, course_prices:array, course_price_latest:float} $groups_mapped
                    */
                @endphp
                @foreach($groups_mapped as $group)
                    <x-admin.singular.meta.list-wrapper>

                        <x-admin.singular.meta.info-big
                            name="{{ $group['name'] }}"
                            :value="sprintf('Course latest price: <strong>%s</strong>', $group['course_price_latest'])"
                        />

                        @php
                            /**
                             * @var array{name:string, date:\Illuminate\Support\Carbon} $month
                             */
                        @endphp
                        @foreach($group['months'] as $month)
                            <x-admin.singular.meta.item-with-icon-properties-actions>
                                {{-- # Group name --}}
                                <x-slot name="properties">
                                    <x-admin.singular.meta.name
                                        name="{{ $month['name'] }}"
                                    />

                                    {{-- # Course prices --}}
                                    <div>
                                        <h4>Course prices:</h4>
                                        <div class="flex">
                                            @php
                                                /**
                                                 * @var array{price:float, created_at:\Illuminate\Support\Carbon} $coursePrice
                                                 */
                                            @endphp
                                            @foreach($group['course_prices'] as $coursePrice)
                                                @if($month['date']->isSameMonth($coursePrice['created_at']))
                                                    <x-admin.singular.property
                                                        name="{{ $coursePrice['created_at']->format('dS F Y') }}"
                                                        value="{{ $coursePrice['price'] }}"
                                                    />
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>

                                </x-slot>

                                {{-- # Form --}}
                                <x-admin.action-delete-button
                                    class="px-2 py-1"
                                    action="{{ route('admin.groups.destroy', $group['id']) }}"
                                    button-text="{{ __('Remove')}}"
                                />

                            </x-admin.singular.meta.item-with-icon-properties-actions>
                        @endforeach

                    </x-admin.singular.meta.list-wrapper>

                @endforeach
            </x-slot>

        </x-admin.singular.wrapper>
    </x-admin.main>
</x-app-layout>
