<x-app-layout>
    <x-admin.header>
        {{ __('Payments review') }}
    </x-admin.header>

    <x-admin.main>
        <x-admin.singular.wrapper>
            @php
                $paymentsFromTextString = __('Payments from');
                $paymentRangeString = sprintf(
                    __('%s to %s'),
                    lmsCarbonDateFormat(Carbon\Carbon::parse($startDate)),
                    lmsCarbonDateFormat(Carbon\Carbon::parse($endDate))
                );
            @endphp
            {{-- # Header --}}
            <x-slot name="info">
                <x-admin.singular.info
                    name="{{ $paymentsFromTextString }}"
                    value="{{ $paymentRangeString }}"
                />
            </x-slot>

            <div class="payments-filter">
                <x-admin.form.wrapper
                    action="{{ route('admin.payments.statistics') }}"
                    method="get"
                    :buttonText="__('Filter')"
                >
                    <x-admin.form.input-date-time
                        name="start_date"
                        :value="$startDate"
                        :label="__('Start date')"
                        :required="true"
                    />

                    <x-admin.form.input-date-time
                        name="end_date"
                        :value="$endDate"
                        :label="__('End date')"
                        :required="true"
                    />
                </x-admin.form.wrapper>
            </div><!-- / .payments-filter -->

            @if($payments->isEmpty())
                <h3>{{ __('There are not payments for the selected period.') }}</h3>
            @else

            {{-- # Meta --}}
            <x-slot name="meta">
                <x-admin.singular.meta.name
                    name="{{ __('Users') }}"
                />

                <x-admin.singular.meta.list-wrapper>

                    @foreach($payments as $index => $payment)
                        <x-admin.singular.meta.item-user>
                            {{-- # Properties --}}
                            <x-slot name="properties">
                                <x-data-property-link
                                    href="{{ route('admin.users.show', $payment->user) }}"
                                    title="{{ $payment->user->name }}"
                                />
                            </x-slot>


                            <x-admin.singular.meta.item-properties-wrapper class="flex-1 justify-end">
                                <div class="lg:flex justify-between items-center">
                                    <x-data-property class="font-bold mb-2 mt-2">
                                        {{ __('Amount') }}: {{ lmsPricePublicFormat($payment->amount) }}
                                    </x-data-property>

                                    <x-data-property class="mb-2 mt-2">
                                        {{ __('Date') }}: {{ lmsCarbonDateFormat($payment->payment_date) }}
                                    </x-data-property>

                                    @if($payment->note)
                                        <x-data-property class="mb-2 mt-2">
                                            {{ __('Note') }}: {{ $payment->note }}
                                        </x-data-property>
                                    @endif

                                    <x-data-property class="mb-2 mt-2">
                                        {{ __('Group') }}: {{ $payment->group->name ?? 'N/A' }}
                                    </x-data-property>
                                </div>
                            </x-admin.singular.meta.item-properties-wrapper>

                        </x-admin.singular.meta.item-user>
                    @endforeach

                </x-admin.singular.meta.list-wrapper>

                <div class="border mt-5 p-6 shadow-md">
                    <p class="mb-4 text-right">{{ $paymentsFromTextString }}: {{ $paymentRangeString }}</p>
                    <div class="flex justify-end">
                        <span class="text-xl">{{ __('Total:') }}</span>
                        <span class="text-xl"><strong>{{ lmsPricePublicFormat($payments->sum('amount')) }}</strong></span>
                    </div>
                </div>
            </x-slot>
            @endif

        </x-admin.singular.wrapper>
    </x-admin.main>
</x-app-layout>
