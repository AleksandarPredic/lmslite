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
                {{-- # Prices --}}
                <x-admin.singular.meta.name
                    name="{{ __('Membership prices') }}"
                />

                @if($prices->isNotEmpty())
                <x-admin.singular.meta.list-wrapper>
                    @foreach($prices as $order => $price)
                        <x-admin.singular.meta.item-wrapper>
                            <x-admin.singular.meta.info-name-value
                                name="{{ $order === 0 ? __('Current price') : \Carbon\Carbon::make($price->created_at)->format('F Y') }}"
                                value="{{ $price->getPriceDisplayFormat() }}"
                            />
                        </x-admin.singular.meta.item-wrapper>
                    @endforeach
                </x-admin.singular.meta.list-wrapper>
                @else
                    <p class="text-red-600">{{ __('Please add prices in the course edit screen!') }}</p>
                @endif

                <hr class="mt-8 pt-4" />

                {{-- # Discounts --}}
                <x-admin.singular.meta.name
                    name="{{ __('Discounts') }}"
                />

                @if($prices->isNotEmpty())
                    <x-admin.singular.meta.list-wrapper>
                        @foreach($discounts as $discount)
                            <x-admin.singular.meta.item-wrapper>
                                <x-admin.singular.meta.info-name-value
                                    name="{{ $discount->name }}"
                                    value="{{ $discount->getPriceDisplayFormat() }}"
                                />
                            </x-admin.singular.meta.item-wrapper>
                        @endforeach
                    </x-admin.singular.meta.list-wrapper>
                @else
                    <p class="mb-4 text-red-600">{{ __('No discounts for this course!') }}</p>
                @endif

                <br /><br />
                <x-admin.singular.meta.name
                    name="{{ __('Add new discount') }}"
                />

                <x-admin.form.wrapper
                    action="{{ route('admin.courses.discounts.store', $course) }}"
                    method="post"
                    :buttonText="__('Add discount')"
                >

                    <x-admin.form.input
                        name="name"
                        :value="old('name')"
                        :label="__('Name')"
                        :required="true"
                    />

                    <x-admin.form.input
                        name="price"
                        type="number"
                        :value="old('price')"
                        :label="__('Price')"
                        :required="true"
                        :step="0.01"
                    />

                </x-admin.form.wrapper>
            </x-slot>

        </x-admin.singular.wrapper>
    </x-admin.main>
</x-app-layout>
