<?php

namespace App\Http\Controllers;

use App\Models\CalendarEventUserStatus;
use App\Models\User;
use App\Services\PaymentService;
use Carbon\CarbonPeriod;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Carbon;

class UserPaymentsController extends Controller
{
    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    /**
     *
     * @param  User  $user
     * @return View
     */
    public function index(User $user): View
    {
        // Define the period for filtering
        $startDate = Carbon::now()->subYear(); // Example: groups that started within the last year
        $endDate = Carbon::now()->addYear(); // Example: groups that will start within the next year

        // Get groups without 'active' filter, but filter by starting_at date
        $groups = $user->groups()
                       ->withPivot('price_type')
                       ->whereBetween('starting_at', [$startDate, $endDate])
                        ->orderBy('starting_at', 'desc')
                       ->get();

        // Get all calendar events with user statuses for this user
        $calendarEventUserStatuses = CalendarEventUserStatus::with(['calendarEvent.event.group'])
                                                            ->where('user_id', $user->id)
                                                            ->get();

        foreach ($groups as $group) {
            // Get all payments for this user and group
            $payments = $this->paymentService->getUserGroupPayments($user, $group->id);

            // Index payments by month and year
            $paymentsByMonth = [];
            foreach ($payments as $payment) {
                $key = $payment->payment_year . '-' . $payment->payment_month;
                $paymentsByMonth[$key] = $payment;
            }

            // Assign to group for easy access in the view
            $group->paymentsByMonth = $paymentsByMonth;

            // Add user price type selected for this group
            $group->user_price = $group->{$group->pivot->price_type};

            // Calculate attendance statistics for this group
            $groupEventStatuses = $calendarEventUserStatuses->filter(function ($status) use ($group) {
                return $status->calendarEvent->event->group_id === $group->id;
            });

            // Group by month
            $monthlyStatuses = [];

            // Get the months for this group
            $startDate = Carbon::parse($group->starting_at)->startOfMonth();
            $endDate = Carbon::parse($group->ending_at)->endOfMonth();
            $period = CarbonPeriod::create($startDate, '1 month', $endDate);

            // Initialize all months with default values
            foreach ($period as $date) {
                $monthKey = $date->format('Y-m');
                $monthlyStatuses[$monthKey] = [
                    'attended' => [],
                    'canceled' => [],
                    'no-show' => [],
                    'compensation' => []
                ];
            }

            // Now count the actual statuses
            foreach ($groupEventStatuses as $status) {
                $month = $status->calendarEvent->starting_at->format('Y-m');

                // Count regular statuses
                if ($status->status === 'attended') {
                    $monthlyStatuses[$month]['attended'][] = $status->calendar_event_id;
                } elseif ($status->status === 'canceled') {
                    $monthlyStatuses[$month]['canceled'][] = $status->calendar_event_id;
                } elseif ($status->status === 'no-show') {
                    $monthlyStatuses[$month]['no-show'][] = $status->calendar_event_id;
                }

                // Count compensation info separately
                if ($status->info === 'compensation') {
                    if ($status->status === 'attended') {
                        $monthlyStatuses[$month]['compensation']['attended'][] = $status->calendar_event_id;
                    } elseif ($status->status === 'canceled') {
                        $monthlyStatuses[$month]['compensation']['canceled'][] = $status->calendar_event_id;
                    } elseif ($status->status === 'no-show') {
                        $monthlyStatuses[$month]['compensation']['no-show'][] = $status->calendar_event_id;
                    }
                }
            }

            // Add to group object
            $group->monthlyStatuses = $monthlyStatuses;
        }

        return view('admin.users.payments.index', [
            'user' => $user,
            'groups' => $groups,
            'statistics_filter_date_format' => StatisticsController::getDateFormat()
        ]);
    }

    /**
     * Store a payment for a user
     */
    public function store(User $user): RedirectResponse
    {
        $data = request()->validate([
            'group_id' => ['required', 'exists:groups,id'],
            'amount' => ['required', 'numeric', 'min:0', 'max:99999999.99'],
            'payment_month' => ['required', 'integer', 'min:1', 'max:12'],
            'payment_year' => ['required', 'integer', 'min:2000', 'max:' . (date('Y') + 10)],
            'payment_date' => ['nullable', 'date'],
            'note' => ['nullable', 'string', 'max:255'],
        ]);

        $this->paymentService->recordPayment($user, $data);

        return redirect()->back()->with('admin.message.success', 'Payment recorded successfully');
    }
}
