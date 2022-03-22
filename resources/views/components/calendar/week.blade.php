{{--
# @see app/View/Components/Calendar/Week.php
--}}

@php
/**
 * @var \Carbon\CarbonPeriod $carbonPeriod
 * @var \Carbon\Carbon $day
 * @var \App\Models\CalendarEvent $calendarEvent
 * @var string $calendarStart
 * @var string $calendarEnd
 * @var string $calendarEnd
 * @var string $dateTimeFormatDay
 * @var string $dateTimeFormatWeek
 */
@endphp
<div class="calendar-week">
    <h2 class="calendar-week__title text-xl tracking-tight font-medium text-gray-900 flex items-center">
        <span class="text-indigo-400">
            <svg xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24" height="24px" viewBox="0 0 24 24" width="24px" fill="#000000"><g><path d="M0,0h24v24H0V0z" fill="none"/></g><g><path d="M20,4H4C2.9,4,2,4.9,2,6v12c0,1.1,0.9,2,2,2h16c1.1,0,2-0.9,2-2V6C22,4.9,21.1,4,20,4z M13,6h2.5v12H13V6z M11,18H8.5V6H11 V18z M4,6h2.5v12H4V6z M20,18h-2.5V6H20V18z"/></g></svg>
        </span>

        <span>
            <span>{{ __('Weekly') }}</span>
            <span class="text-indigo-400">{{ __('calendar') }}</span>
        </span>
    </h2>

    {{-- # Filter --}}
    <div class="calendar-week__filter">
        <form method="get">
            @csrf

            <div class="calendar-week__filter-date">
                <x-admin.form.input-date-time
                    name="calendar_start"
                    :value="$calendarStart"
                    :label="__('Start date')"
                    :required="true"
                />
            </div>

            <div class="calendar-week__filter-date">
                <x-admin.form.input-date-time
                    name="calendar_end"
                    :value="$calendarEnd"
                    :label="__('End date')"
                    :required="true"
                />
            </div>

            <x-button>{{ __('Filter') }}</x-button>
        </form>
    </div>

    {{-- # Calendar --}}
    <div class="calendar-week__days">
        @foreach($carbonPeriod as $day)

            {{-- # Mark new week --}}
            @if($day->isDayOfWeek(\Carbon\Carbon::MONDAY))
                <div class="calendar-week__week mb-2 mt-2 rounded py-6 text-lg bg-indigo-100 px-4 ">
                    <span class="calendar-week__week-icon text-indigo-400">
                        <svg xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24" height="24px" viewBox="0 0 24 24" width="24px" fill="#000000"><rect fill="none" height="24" width="24"/><path d="M20,4H4C2.9,4,2,4.9,2,6v12c0,1.1,0.9,2,2,2h16c1.1,0,2-0.9,2-2V6C22,4.9,21.1,4,20,4z M8,18H4V6h4V18z M14,18h-4V6h4V18z M20,18h-4V6h4V18z"/></svg>
                    </span>
                    <span class="calendar-week__week-dates">
                        <span>{{ $day->copy()->startOfWeek()->format($dateTimeFormatWeek) }}</span>
                        <span>{{ $day->copy()->endOfWeek()->format($dateTimeFormatWeek) }}</span>
                    </span>
                </div>
            @endif

            {{-- # All calendar events for day --}}
            <section class="calendar-week__day mb-4 mt-4 border-b border-gray-300 py-4">
                <header class="mb-4">
                    <h3 class="text-lg font-medium">{{ $day->format($dateTimeFormatWeek) }}</h3>
                </header>

                <main>
                    <x-data-cards.wrapper>
                        <x-slot name="cards">
                            @foreach($calendarEvents->filter(fn($item) => $item->starting_at->isSameDay($day)) as $calendarEvent)
                                {{-- Event properties --}}
                                <x-data-cards.card :name="$calendarEvent->event->name">
                                    <x-slot name="properties">
                                        <x-data-property>
                                            {{ $calendarEvent->starting_at->format($dateTimeFormatEvent) }}
                                        </x-data-property>

                                        <x-data-property>
                                            {{ __('Duration') }}: {{ $calendarEvent->ending_at->diffForHumans($calendarEvent->starting_at, true) }}
                                        </x-data-property>
                                    </x-slot>

                                    {{-- Event action links --}}
                                    <x-link
                                        href="{{ route('admin.calendar-events.show', [$calendarEvent]) }}"
                                        title="{{ __('Manage') }}" />
                                </x-data-cards.card>
                            @endforeach
                        </x-slot>
                    </x-data-cards.wrapper>
                </main>
            </section>

        @endforeach
    </div>
</div>
