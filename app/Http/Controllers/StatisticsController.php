<?php

namespace App\Http\Controllers;

use App\Models\CalendarEventUserStatus;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Carbon;

class StatisticsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index()
    {
        /**
         * TODO: Continue here
         * http://localhost/admin/events/3
         * http://localhost/admin/statistics
         */

        // TODO: Check with client if we need to show the event name or group name

        // TODO: Add caching

        // TODO: use this for testing
        $startDate = Carbon::createFromFormat('d/m/Y', '01/12/2022');
        $endDate = Carbon::createFromFormat('d/m/Y', '31/02/2023');

        $calenarEventUserStatuses = CalendarEventUserStatus::with('user')
            ->with('calendarEvent')
            ->whereHas('calendarEvent', function($query) use ($startDate, $endDate) {
                $query->whereBetween('starting_at', [$startDate, $endDate])->orderBy('starting_at');
            })
            ->get();

        $dates = $calenarEventUserStatuses
            ->groupBy('calendar_event_id')
            // As we have a collection of same calendar events, get the first one
            ->map(fn($statuses) => $statuses->first()->calendarEvent->starting_at);

        // This is used for the table headings preview
        $datesWithKeysAsMonths = [];
        foreach ($dates as $date) {
            $datesWithKeysAsMonths[$date->format('m/Y')] = $date->format('M Y');
        }

        $usersWithCalendarEventUserStatuses = $calenarEventUserStatuses->groupBy('user_id');

        $sortedUserStatuses = [];
        foreach ($usersWithCalendarEventUserStatuses as $userId => $calendarEventUserStatuses) {
            $user = $calendarEventUserStatuses->first()->user;

            // Count all the statuses by month so we can group them after
            $statusesGroupedByMonth = [];
            foreach ($calendarEventUserStatuses as $calendarEventUserStatus) {
                $statusesGroupedByMonth[] = [
                    'month' => $calendarEventUserStatus->calendarEvent->starting_at->format('m/Y'),
                    'status' => $calendarEventUserStatus->status,
                    'calendarEventUserStatus' => $calendarEventUserStatus
                ];
            }

            $sortedDataPerMonth = [];
            foreach ($statusesGroupedByMonth as $oneEventStatus) {
                $sortedDataPerMonth[$oneEventStatus['month']]['statuses'][] = $oneEventStatus['status'];
                $sortedDataPerMonth[$oneEventStatus['month']]['calendarEventUserStatuses'][] = $oneEventStatus['calendarEventUserStatus'];
            }

            // If the user has no records for some months we have in the HTML table
            foreach (array_keys($datesWithKeysAsMonths) as $month) {
                // Fill in empty columns to avoid having empty td in HTML table
                // This will become below all zeros for statuses
                if (! array_key_exists($month, $sortedDataPerMonth)) {
                    $sortedDataPerMonth[$month]['statuses'] = [];
                    $sortedDataPerMonth[$month]['calendarEventUserStatuses'] = [];
                }
            }

            $sortedUserStatuses[$userId] = (object)[
                'user' => $user,
                'months' => $statusesGroupedByMonth,
                'sortedDataPerMonth' => array_map(function ($month) {
                    $values = array_count_values($month['statuses']);

                    // Re-map calendar event statuses by Event and by status
                    $reMappedCalendarEventUserStatuses = [];
                    if (! empty($month['calendarEventUserStatuses'])) {
                        foreach ($month['calendarEventUserStatuses'] as $calendarEventUserStatus) {
                            // Sort all by the same event id and the same status, but we will have to loop through it again below
                            $reMappedCalendarEventUserStatuses[$calendarEventUserStatus->calendarEvent->event->id][$calendarEventUserStatus->status][] = (object)[
                                'eventName' => $calendarEventUserStatus->calendarEvent->event->name,
                                'status' => $calendarEventUserStatus->status,
                                'userStatus' => $calendarEventUserStatus,
                            ];
                        }
                    }

                    // After sorting it, just remove status keys, so we can access the items more easily in te view
                    $reMappedCalendarEventUserStatuses = array_map(
                        fn($item) => array_values($item),
                        $reMappedCalendarEventUserStatuses
                    );

                    //dd($reMappedCalendarEventUserStatuses);

                    return [
                        'statuses' => [
                            'attended' => $values['attended'] ?? 0,
                            'canceled' => $values['canceled'] ?? 0,
                            'no-show' => $values['no-show'] ?? 0,
                        ],
                        'sortedCalendarEventUserStatuses' => $reMappedCalendarEventUserStatuses
                    ];
                }, $sortedDataPerMonth)
            ];
        }

       // dd(collect($sortedUserStatuses)->get(19));

        return view('admin.statistics.index', [
            'dates' => $datesWithKeysAsMonths,
            'sortedUserStatuses' => collect($sortedUserStatuses)
        ]);
    }
}
