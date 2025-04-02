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
                        {{ __('Groups (In last 2 years)') }}
                        @foreach($groups as $group)
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

                @if ($user->payment_note)
                    <section class="mb-4 pb-4 px-2">
                        <h3>User Payment Note</h3>
                        <p class="pl-3 pr-4 py-2 bg-indigo-50 text-lg">{{ $user->payment_note }}</p>
                    </section>

                    <hr class="mb-4 mt-4" />
                    <br />
                @endif

                <x-admin.singular.meta.name
                    name="{{ __('User groups in last 2 years') }}"
                />

                @if($groups->isNotEmpty())
                    @foreach($groups as $group)

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

                        <x-admin.singular.meta.item-wrapper class="bg-gray-100 text-lg">
                            {{ $group->name }}
                            <div class="flex-1 text-right">
                                <a href="{{ route('admin.statistics.index', [
                                'group_id' => $group->id,
                                'calendar_start' => $startDate->startOfDay()->format($statistics_filter_date_format),
                                'calendar_end' => $endDate->startOfDay()->format($statistics_filter_date_format)
                            ]) }}"
                                   class="text-blue-600 hover:underline"
                                   target="_blank"
                                >
                                    {{ __('View Statistics') }}
                                </a>
                            </div>
                        </x-admin.singular.meta.item-wrapper>

                        <x-admin.singular.meta.list-wrapper>

                            @foreach($months as $month)
                                <x-admin.singular.meta.item-wrapper>
                                    <x-admin.singular.meta.item-icon>
                                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="#000000"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M19 4h-1V2h-2v2H8V2H6v2H5c-1.11 0-1.99.9-1.99 2L3 20c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V10h14v10zm0-12H5V6h14v2zm-7 5h5v5h-5z"/></svg>
                                    </x-admin.singular.meta.item-icon>

                                    <div class="font-bold text-lg">{{ $month['name'] }} | {{ lmsPricePublicFormat($group->user_price) }}</div>

                                    <x-admin.user-payments.statusses
                                        :month="$month"
                                        :group="$group"
                                    />

                                    @php
                                        $monthKey = $month['date']->format('Y-n');
                                        $payment = $group->paymentsByMonth[$monthKey] ?? null;
                                    @endphp

                                    <x-admin.singular.meta.item-properties-wrapper class="flex-1 justify-end">
                                        @if($payment)
                                            <div class="flex justify-between items-center">
                                                <x-data-property class="mb-2 mt-2">
                                                    {{ __('Date') }}: {{ lmsCarbonDateFormat($payment->payment_date) }}
                                                </x-data-property>

                                                @if($payment->note)
                                                    <x-data-property class="mb-2 mt-2">
                                                        {{ __('Note') }}: {{ $payment->note }}
                                                    </x-data-property>
                                                @endif

                                                <x-data-property class="font-bold mb-2 mt-2">
                                                    {{ __('Amount') }}: {{ lmsPricePublicFormat($payment->amount) }}
                                                </x-data-property>

                                                <form action="{{ route('admin.users.payments.destroy', ['user' => $user, 'payment' => $payment]) }}"
                                                      method="POST"
                                                      onsubmit="return confirm('Are you sure you want to delete this payment' + ' for {{ $month['name'] }}?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 ml-4 mr-4">{{ __('Delete') }}</button>
                                                </form>
                                            </div>
                                        @else
                                            <!-- Payment form -->
                                            <form
                                                action="{{ route('admin.users.payments.store', $user) }}"
                                                method="POST"
                                                class="lg:flex"
                                                onsubmit="return confirm('Are you sure you want to record payment of ' + this.elements.amount.value + ' for {{ $month['name'] }}?');"
                                            >
                                                @csrf
                                                <input type="hidden" name="group_id" value="{{ $group->id }}">
                                                <input type="hidden" name="payment_month" value="{{ $month['date']->month }}">
                                                <input type="hidden" name="payment_year" value="{{ $month['date']->year }}">

                                                <div class="mr-2 mb-2 mt-2">
                                                    <input type="date" name="payment_date" class="text-sm rounded-md border-gray-300" value="{{ date('Y-m-d') }}" placeholder="Payment Date">
                                                </div>

                                                <div class="mr-2 mb-2 mt-2">
                                                    <input type="number" name="amount" class="text-sm rounded-md border-gray-300" placeholder="Amount" step="0.01" min="0" max="99999999.99" required>
                                                </div>

                                                <div class="mr-2 mb-2 mt-2">
                                                    <input type="text" name="note" class="text-sm rounded-md border-gray-300" placeholder="Note (optional)">
                                                </div>

                                                <button type="submit" class="font-bold py-2 px-4 rounded bg-gray-100 mb-2 mt-2">
                                                    {{ __('Record Payment') }}
                                                </button>
                                            </form>
                                        @endif
                                    </x-admin.singular.meta.item-properties-wrapper>
                                </x-admin.singular.meta.item-wrapper>

                            @endforeach

                        </x-admin.singular.meta.list-wrapper>
                    @endforeach
                @else
                    <h4 class="pl-3 pr-4 py-2 text-gray-500">{{ __('No Groups') }}</h4>
                @endif

            </x-slot>


        </x-admin.singular.wrapper>
    </x-admin.main>
</x-app-layout>
