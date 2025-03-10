<x-app-layout>
    <x-admin.header>
        {{ __('Statistics') }}
    </x-admin.header>

    <x-admin.main>

        {{-- TODO: Move this to css --}}
        <style>
            table {
                border: 1px solid;
                border-collapse: collapse;
            }

            table th,
            table td {
                width: 150px;
                border: 1px solid;
                text-align: center;
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
                const showCssClass = 'statistics__details-month--show';
                const allModals = document.querySelectorAll('.statistics__details-month');

                for (const modalTrigger of document.querySelectorAll('.statistics__details-month-trigger')) {
                    modalTrigger.addEventListener('click', (event) => {
                        event.preventDefault();

                        for(const modal of allModals) {
                            modal.classList.remove(showCssClass);
                        }

                        event.currentTarget.parentElement.nextElementSibling.classList.add(showCssClass);
                    });
                }

                for (const modalClose of document.querySelectorAll('.statistics__details-month-close')) {
                    modalClose.addEventListener('click', (event) => {
                        event.preventDefault();

                        event.currentTarget.parentElement.parentElement.classList.remove(showCssClass);
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

        <br />
        <hr />
        <br />
        <br />

        <table class="table-auto">
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
                        <td>{{ $userName }}</td>
                        @foreach($sortedUserStatus->sortedDataPerMonth as $monthDate => $monthPreview)
                            <td>
                                @php
                                    $printStatuses = sprintf(
                                        '%s | %s | %s',
                                        $monthPreview['statuses']['attended'],
                                        $monthPreview['statuses']['canceled'],
                                        $monthPreview['statuses']['no-show']
                                    );
                                @endphp
                                <div>
                                    @if(! empty($monthPreview['sortedCalendarEventUserStatuses']))
                                        <a class="statistics__details-month-trigger" href="#">{{ $printStatuses }}</a>
                                    @else
                                        {{ $printStatuses }}
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

    </x-admin.main>
</x-app-layout>
