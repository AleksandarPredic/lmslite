@props(['route'])

<div class="mb-4">
    <h5 class="mb-2">{{ __('Search by name') }}</h5>

    <div class="flex items-center">
        <x-admin.form.wrapper
            class="flex items-center user-index-search"
            action="{{ $route }}"
            method="GET"
            :buttonText="__('Search')"
        >

            <x-admin.form.input
                name="name"
                :value="request()->get('name')"
                :label="null"
                :required="true"
            />

        </x-admin.form.wrapper>

        <x-link
            class="ml-4"
            href="{{ $route }}"
            title="{{ __('Reset') }}" />
    </div>
</div>
