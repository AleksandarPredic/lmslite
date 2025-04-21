<x-app-layout>
    <x-admin.header>
        {{ __('Statistics') }}
    </x-admin.header>

    <x-admin.main>

        {{-- TODO: Move this to css --}}
        <style>
            .statistics__table-wrapper {
                overflow-x: auto;
                width: 100%;
                -webkit-overflow-scrolling: touch; /* Smooth scrolling on iOS devices */
            }

            table {
                border: 1px solid;
                border-collapse: collapse;
                width: auto; /* Allow table to expand based on content */
            }

            table th,
            table td {
                width: 300px;
                border: 1px solid;
                text-align: center;
                padding: 10px;
            }

            td.statistics__table-data {
                min-width: 280px;
                width: 250px;
            }

            table td hr {
                border-color: darkgrey;
            }

            .statistics__group-payments ul li {
                display: flex;
                margin-bottom: 8px;
                text-align: left;
                justify-content: center;
            }

            .statistics__group-payments ul li > span:last-child{
                font-weight: bold;
                color: green;
            }

            .statistics__group-payments:not(.statistics__group-payments--show) ul li > span:first-child{
                width: 175px;
                text-overflow: ellipsis;
                overflow: hidden;
                white-space: nowrap;
                color: gray;
            }

            .statistics__group-payments--show ul li {
                display: block;
                padding: 5px 10px;
                background-color: #e0e8f7;
                border-radius: 5px;
            }

            /* Add the zebra striping for alternate rows */
            table tbody tr:nth-child(even) {
                background-color: #e0e8f7;
            }

            .statistics__details-calendar-event-list a:hover,
            .statistics__details-month-trigger:hover {
                color: blue;
                font-weight: bold;
            }

            .statistics__details-month {
                display: none;
                position: fixed;
                top: 50px;
                left: 50%;
                transform: translate(-50%, 0);
                width: 300px;
                height: auto;
                max-height: calc(100vh - 80px);
                background-color: white;
                overflow-y: auto;
            }

            .statistics__details-month--show {
                display: block;
            }
        </style>

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
            <strong>Legend: 1st digit -> attended, 2nd -> canceled, 3rd -> no-show</strong>
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
                        @foreach($sortedUserStatus->sortedDataPerMonth as $monthDate => $monthPreview)
                            <td class="statistics__table-data">
                                @php
                                    $printStatuses = sprintf(
                                        '%s | %s | %s',
                                        $monthPreview['statuses']['attended'],
                                        $monthPreview['statuses']['canceled'],
                                        $monthPreview['statuses']['no-show']
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
                                                <div class="mb-4">{{ $monthDate }}</div>
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
                                                                $status = $calendarEventUserStatus->userStatus
                                                            @endphp
                                                            <li class="text-sm">
                                                                <a href="{{ route('admin.calendar-events.show', $status->calendarEvent) }}" class="mb-1">
                                                                    {{ $status->calendarEvent->starting_at->format('D, d.m.Y') }} - {{ $status->info ?? 'none'}}
                                                                </a>
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
