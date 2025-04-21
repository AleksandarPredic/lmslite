<?php

namespace App\Http\Controllers;

use App\Models\CalendarEventUserStatus;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Carbon;

class StatisticsController extends Controller
{
    private const FILTER_DATE_FORMAT = 'Y-m-d\TH:i';

    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index()
    {
        /**
         * Here we will show all user payments, for all groups, even if he has no statusses for them recorded. This way we show all
         * payments, not just payments for groups related to CalendarEventUserStatus
         *
         * http://localhost/admin/statistics
         * http://localhost/admin/statistics?group_id=2&calendar_start=2025-03-20T00%3A00&calendar_end=2025-09-20T00%3A00
         */

        // TODO: Add caching

        if (request()->exists('calendar_start') && request()->exists('calendar_end')) {
            $startDate = Carbon::createFromFormat(self::FILTER_DATE_FORMAT, request()->get('calendar_start'))->startOfDay();
            $endDate = Carbon::createFromFormat(self::FILTER_DATE_FORMAT, request()->get('calendar_end'))->endOfDay();
        } else {
            $startDate = Carbon::now()->subMonths(2)->startOfMonth();
            $endDate = Carbon::now()->endOfMonth();
        }

        $courseId = request()->exists('course_id') ? intval(request()->get('course_id')) : 0;
        $groupId = request()->exists('group_id') ? intval(request()->get('group_id')) : 0;

        // Return early with empty data if neither course nor group is selected
        if ($courseId === 0 && $groupId === 0) {
            return view('admin.statistics.index', [
                'dateSearchStart' => $startDate->format(self::FILTER_DATE_FORMAT),
                'dateSearchEnd' => $endDate->format(self::FILTER_DATE_FORMAT),
                'selectedCourseId' => $courseId,
                'selectedGroupId' => $groupId,
                'dates' => [],
                'sortedUserStatuses' => collect([]),
                'formErrorMessage' => __('Please select either a course or a group to view statistics.')
            ]);
        }


        $calenarEventUserStatuses = CalendarEventUserStatus::with('user')
            // Eager load all user payments but only for the dates selected in the statistics filter
            ->with(['user.payments' => function($query) use ($startDate, $endDate) {
                $query->whereBetween('payment_date', [$startDate, $endDate])
                      ->with('group');
            }])
           ->with('calendarEvent.event.group')
           ->whereHas('calendarEvent', function($query) use ($startDate, $endDate, $courseId, $groupId) {
               $query->whereBetween('starting_at', [$startDate, $endDate])
                     ->whereHas('event.group', function($query) use ($courseId, $groupId) {
                         if (0 !== $courseId) {
                             $query->where('course_id', '=', $courseId);
                         }

                         if (0 !== $groupId) {
                             $query->where('id', '=', $groupId);
                         }
                     });
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

        // Group users and sort them by months so we get them aligned just fine for the table printing in as we need to keep the order of the months for TD with TH
        $usersWithCalendarEventUserStatuses = $calenarEventUserStatuses
            ->sortBy(function($status) {
                return $status->calendarEvent->starting_at->timestamp;
            })
            ->groupBy('user_id');

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

            // Prepare all the data sorted per month
            $processedMonths = [];
            foreach ($sortedDataPerMonth as $monthAsMonthSlashYearString => $month) {
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

                // Extract year and month from the "05/2025" format
                list($monthNum, $year) = explode('/', $monthAsMonthSlashYearString);

                // Add payments per month for this user, but filter only for this month from the Collection
                /**
                 * @var \Illuminate\Database\Eloquent\Collection $payments
                 */
                $payments = $user->payments->filter(function($payment) use ($monthNum, $year) {


                    // Check if payment date is within this month
                    return $payment->payment_month === (int)$monthNum && $payment->payment_year === (int)$year;
                })->sortBy('payment_date'); // Sort by payment date

                $keyForSortingMonthsAsTimestamp = (new Carbon())
                    ->setYear($year)
                    ->setMonth($monthNum)
                    ->setDay(1);

                $processedMonths[$keyForSortingMonthsAsTimestamp->timestamp] = [
                    'month' => $monthAsMonthSlashYearString,
                    'statuses' => [
                        'attended' => $values['attended'] ?? 0,
                        'canceled' => $values['canceled'] ?? 0,
                        'no-show' => $values['no-show'] ?? 0,
                    ],
                    'sortedCalendarEventUserStatuses' => $reMappedCalendarEventUserStatuses,
                    'payments' => $payments
                ];
            }

            // Sort the processed months by key (month/year) in ascending order as this can cause printing months in different column
            // We must use timestamp as ksort can not suport (month/year), so we switched to timestamp instead of the (month/year) string
            ksort($processedMonths);

            $sortedUserStatuses[$userId] = (object)[
                'user' => $user,
                'months' => $statusesGroupedByMonth,
                'sortedDataPerMonth' => $processedMonths,
            ];
        }

        return view('admin.statistics.index', [
            'dateSearchStart' => $startDate->format(self::FILTER_DATE_FORMAT),
            'dateSearchEnd' => $endDate->format(self::FILTER_DATE_FORMAT),
            'selectedCourseId' => $courseId,
            'selectedGroupId' => $groupId,
            'dates' => $datesWithKeysAsMonths,
            'sortedUserStatuses' => collect($sortedUserStatuses)
        ]);
    }

    public static function getDateFormat()
    {
        return self::FILTER_DATE_FORMAT;
    }
}
