<?php

namespace App\View\Components\Calendar;

use App\Models\CalendarEvent;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\Component;

/**
 * Component to display a period of time with calendar events, in the week calendar view form
 */
class Week extends Component
{
    protected $startDate;
    protected $endDate;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        $url = parse_url(
            request()->fullUrl()
        );

        $attributes = [];
        if (isset($url['query'])) {
            parse_str($url['query'], $attributes);
        }

        // Cover the case where in the string we have &amp; encoded from url()->previous()
        $validate = [];
        if ($attributes) {
            foreach ($attributes as $key => $attribute) {
                $validate[trim($key, 'amp;')] = $attribute;
            }
        }

        $calendarStart = Validator::make($validate, [
            'calendar_start' => ['required', 'date'],
        ]);

        $calendarEnd = Validator::make($validate, [
            'calendar_end' => ['required', 'date'],
        ]);

        $this->startDate = $calendarStart->passes()
            ? Carbon::parse($calendarStart->getData()['calendar_start'])
            : Carbon::now()->setHours(8)->setMinutes(0);
        $this->endDate = $calendarEnd->passes() ?? null
                ? Carbon::parse($calendarEnd->getData()['calendar_end'])
                : Carbon::now()->addDays(6);
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $calendarEvents = CalendarEvent::with('event')
                                       ->whereBetween('starting_at', [$this->startDate, $this->endDate])
                                       ->get()
                                       ->sortBy('starting_at');

        return view('components.calendar.week', [
            'calendarStart' => $this->startDate->format('Y-m-d\TH:i'),
            'calendarEnd' => $this->endDate->format('Y-m-d\TH:i'),
            'calendarEvents' => $calendarEvents,
            'carbonPeriod' => CarbonPeriod::create($this->startDate, $this->endDate),
            'dateTimeFormatEvent' => 'l | h:i A',
            'dateTimeFormatWeek' => 'l | d.m.Y'
        ]);
    }
}
