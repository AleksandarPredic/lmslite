<x-app-layout>
    <x-admin.header>
        {{ __('Statistics') }}
    </x-admin.header>

    <x-admin.main>

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

            .statistics__details-month-trigger:hover {
                color: blue;
                font-weight: bold;
            }

            .statistics__details-month {
                display: none;
                position: fixed;
                top: 50px;
                left: 30px;
                width: 300px;
                height: auto;
                background-color: white;
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
                    <tr>
                        <td>{{ $sortedUserStatus->user->name }}</td>
                        @foreach($sortedUserStatus->sortedDataPerMonth as $monthPreview)
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
                                                <strong><p class="mb-4">{{ $sortedByEvent[0][0]->eventName }}</p></strong>

                                                {{-- Loop through each status --}}
                                                @foreach($sortedByEvent as $sortedByStatus)
                                                    <div class="bg-indigo-50 text-sm border border-gray-200 px-4 rounded text-center font-bold mb-2">
                                                        {{ $sortedByStatus[0]->status }}
                                                    </div>

                                                    {{-- Loop through each status --}}
                                                    <ul class="mb-4">
                                                        @foreach($sortedByStatus as $calendarEventUserStatus)
                                                            @php
                                                                /**
                                                                * @var \App\Models\CalendarEventUserStatus $status
                                                                 */
                                                                $status = $calendarEventUserStatus->userStatus
                                                            @endphp
                                                            <li class="text-sm">
                                                                {{ $status->calendarEvent->starting_at->format('D, d.m.Y') }} - {{ $status->info ?? 'none'}}
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
