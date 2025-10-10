<x-app-layout>
    <x-admin.header>
        {{ __('Statistics') }}
    </x-admin.header>

    <x-admin.main>
        <script>
            window.addEventListener('load', () => {
                // Show modal for event attendance
                const showCssClass = 'statistics__details-month--show';
                const allModals = document.querySelectorAll('.statistics__details-month');

                for (const modalTrigger of document.querySelectorAll('.statistics__details-month-trigger')) {
                    modalTrigger.addEventListener('click', (event) => {
                        event.preventDefault();

                        for(const modal of allModals) {
                            modal.classList.remove(showCssClass);
                        }

                        event.currentTarget.parentElement.parentElement.nextElementSibling.classList.add(showCssClass);
                    });
                }

                for (const modalClose of document.querySelectorAll('.statistics__details-month-close')) {
                    modalClose.addEventListener('click', (event) => {
                        event.preventDefault();

                        event.currentTarget.parentElement.parentElement.classList.remove(showCssClass);
                    });
                }

                // Toggle payment detail information css class
                const showPaymentsCssClass = 'statistics__group-payments--show';
                for (const paymentDetails of document.querySelectorAll('.statistics__group-payments')) {
                    paymentDetails.addEventListener('click', (event) => {
                        event.preventDefault();

                        const element = event.currentTarget;

                        if (element.classList.contains(showPaymentsCssClass)) {
                            element.classList.remove(showPaymentsCssClass);
                        } else {
                            element.classList.add(showPaymentsCssClass);
                        }
                    });
                }
            });
        </script>

        <div>
            <strong>Legend: 1st digit -> attended | 2nd -> canceled | 3rd -> no-show | 4th -> compensations</strong>
        </div>
        <br />
        <br />

        {{-- # Filter --}}
        <div class="statistics-filter">

            <x-admin.form.wrapper
                action="{{ route('admin.statistics.index') }}"
                method="get"
                :buttonText="__('Filter')"
            >
                <x-admin.form.input-date-time
                    name="calendar_start"
                    :value="$dateSearchStart"
                    :label="__('Start date')"
                    :required="true"
                />

                <x-admin.form.input-date-time
                    name="calendar_end"
                    :value="$dateSearchEnd"
                    :label="__('End date')"
                    :required="true"
                />

                <x-admin.form.course
                    :value="$selectedCourseId"
                />

                {{-- In the statistics we show all groups if we have the param group_id --}}
                <x-admin.form.group
                    :value="$selectedGroupId"
                />

            </x-admin.form.wrapper>
        </div><!-- / .statistics-filter -->

        @if($formErrorMessage ?? null)
            <h3 class="text-red-600">{{ $formErrorMessage }}</h3>
        @endif

        <br />
        <hr />
        <br />
        <br />

        <div class="statistics__table-wrapper">
            <table>
                <thead>
                <tr>
                    <th>Name</th>
                    @foreach($dates as $date)
                        <th>{{ $date }}</th>
                    @endforeach
                </tr>
                </thead>
                <tbody>
                @foreach($sortedUserStatuses as $sortedUserStatus)
                    @php $userName = $sortedUserStatus->user->name; @endphp
                    <tr>
                        <td class="statistics__table-name"><a href="{{ route('admin.users.payments.index', $sortedUserStatus->user) }}">{{ $userName }}</a></td>
                        @foreach($sortedUserStatus->sortedDataPerMonth as $monthPreviewTimestamp => $monthPreview)
                            <td class="statistics__table-data">
                                @php
                                    $printStatuses = sprintf(
                                        '%s | %s | %s | %s',
                                        $monthPreview['statuses']['attended'],
                                        $monthPreview['statuses']['canceled'],
                                        $monthPreview['statuses']['no-show'],
                                        $monthPreview['statuses']['compensations'],
                                    );

                                    $payments = $monthPreview['payments'];
                                @endphp
                                <div>
                                    @if(! empty($monthPreview['sortedCalendarEventUserStatuses']))
                                        <div>
                                            <a class="statistics__details-month-trigger" href="#">{{ $printStatuses }}</a>
                                        </div>

                                        <x-admin.statistics.payments-month-list
                                            :payments="$payments"
                                        />
                                    @else
                                        <div>
                                            {{ $printStatuses }}
                                        </div>

                                        <x-admin.statistics.payments-month-list
                                            :payments="$payments"
                                        />
                                    @endif
                                </div>

                                @if(! empty($monthPreview['sortedCalendarEventUserStatuses']))
                                    <div class="statistics__details-month border border-indigo-400 px-4">
                                        <div class="mb-4 text-right">
                                            <a class="statistics__details-month-close" href="#">X close</a>
                                        </div>
                                        <div class="statistics__details-month-inner">
                                            {{-- Loop through each event --}}
                                            @foreach($monthPreview['sortedCalendarEventUserStatuses'] as $sortedByEvent)
                                                <div><strong>{{ $userName }}</strong></div>
                                                <div class="mb-4">{{ (new \Illuminate\Support\Carbon($monthPreviewTimestamp))->format('M Y') }}</div>
                                                <hr class="mb-2" />
                                                <div class="mb-4"><strong>{{ $sortedByEvent[0][0]->eventName }}</strong></div>

                                                {{-- Loop through each status --}}
                                                @foreach($sortedByEvent as $sortedByStatus)
                                                    <div class="bg-indigo-50 text-sm border border-gray-200 px-4 rounded text-center font-bold mb-2">
                                                        {{ $sortedByStatus[0]->status }}
                                                    </div>

                                                    {{-- Loop through each status --}}
                                                    <ul class="mb-4 statistics__details-calendar-event-list">
                                                        @foreach($sortedByStatus as $calendarEventUserStatus)
                                                            @php
                                                                /**
                                                                * @var \App\Models\CalendarEventUserStatus $status
                                                                 */
                                                                $status = $calendarEventUserStatus->userStatus;
                                                            @endphp
                                                            <li class="text-sm">
                                                                <a href="{{ route('admin.calendar-events.show', $status->calendarEvent) }}" class="mb-1">
                                                                    {{ $status->calendarEvent->starting_at->format('D, d.m.Y') }} - {{ $status->info ?? 'none'}}
                                                                </a>
                                                                @if($status->compensations->isNotEmpty())
                                                                    <ul class="statistics__details-calendar-event-list-compensations">
                                                                        @foreach($status->compensations as $calendarEventUserStatusCompensation)
                                                                            <li class="text-sm">
                                                                                <x-compensation.compensation-trigger
                                                                                    :compensation="$calendarEventUserStatusCompensation"
                                                                                />
                                                                            </li>
                                                                        @endforeach
                                                                    </ul>
                                                                @endif
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                @endforeach
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </td>
                        @endforeach
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

    </x-admin.main>
</x-app-layout>
