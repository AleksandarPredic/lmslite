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

        // TODO: Add caching
        $startDate = Carbon::createFromFormat('d/m/Y', '26/12/2022');
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

            // TODO: Use this for more detailed view when the user clicks on the table statuses
            $statusesGroupedByMonth = [];
            foreach ($calendarEventUserStatuses as $calendarEventUserStatus) {
                $statusesGroupedByMonth[] = [
                    'month' => $calendarEventUserStatus->calendarEvent->starting_at->format('m/Y'),
                    'status' => $calendarEventUserStatus->status
                ];
            }

            $countStatusesPreMonth = [];
            foreach ($statusesGroupedByMonth as $oneEventStatus) {
                $countStatusesPreMonth[$oneEventStatus['month']]['status'][] = $oneEventStatus['status'];
            }

            // If the user has no records for some months we have in the HTML table
            foreach (array_keys($datesWithKeysAsMonths) as $month) {
                // Fill in empty columns to avoid having empty td in HTML table
                // This will become below all zeros for statuses
                if (! array_key_exists($month, $countStatusesPreMonth)) {
                    $countStatusesPreMonth[$month]['status'] = [];
                }
            }

            $sortedUserStatuses[$userId] = (object)[
                'user' => $user,
                'months' => $statusesGroupedByMonth,
                'countStatusesPreMonth' => array_map(function ($month) {
                    $values = array_count_values($month['status']);
                    return [
                        'attended' => $values['attended'] ?? 0,
                        'canceled' => $values['canceled'] ?? 0,
                        'no-show' => $values['no-show'] ?? 0,
                    ];
                }, $countStatusesPreMonth)
            ];
        }

        return view('admin.statistics.index', [
            'dates' => $datesWithKeysAsMonths,
            'sortedUserStatuses' => collect($sortedUserStatuses)
        ]);
    }
}
