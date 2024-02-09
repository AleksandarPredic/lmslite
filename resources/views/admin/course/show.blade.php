@php
/**
 * Enable autocomplete in blade file
 * @var \App\Models\Course $course
 * @var \App\Models\CourseMembership $price
 */
@endphp
<x-app-layout>
    <x-admin.header>
        {{ __('Preview course') }}
    </x-admin.header>

    <x-admin.main>
        <div class="flex mb-4">
            <x-admin.redirect-link href="{{ route('admin.courses.index') }}" :title="__('Back to all!')" />
        </div>

        <div class="flex justify-end mb-4 px-4">
            <x-admin.action-link-button href="{{ route('admin.courses.edit', $course) }}" title="{{ __('Edit') }}" />
        </div>

        <x-admin.singular.wrapper>
            {{-- # Header --}}
            <x-slot name="info">
                <x-admin.singular.name
                    name="{{ $course->name }}"
                />
            </x-slot>

            {{-- # Meta --}}
            <x-slot name="meta">
                <x-admin.singular.meta.name
                    name="{{ __('Membership prices') }}"
                />

                <x-admin.singular.meta.list-wrapper>
                    @foreach($prices as $price)
                        <x-admin.singular.meta.item-wrapper>
                            <x-admin.singular.meta.info-normal-value
                                name="{{ $price->getPriceDisplayFormat() }}"
                                value="{{ $price->created_at }}"
                            />
                        </x-admin.singular.meta.item-wrapper>
                    @endforeach
                </x-admin.singular.meta.list-wrapper>
            </x-slot>

        </x-admin.singular.wrapper>
    </x-admin.main>
</x-app-layout>
