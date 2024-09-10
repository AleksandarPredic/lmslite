<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\View\View;

class UserMembershipController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index(User $user): View
    {
        // Sort by groups whith prices.
        $groupsMapped = [];
        foreach ($user->groups as $group) {
            $period = \Carbon\CarbonPeriod::create($group->starting_at, $group->ending_at);

            $months = [];
            foreach ($period as $day) {
                // Let the days with the same month and year overwrite themselves as we only need info about month and year, not exact dates
                $months[strtolower($day->format('F-Y'))] = [
                    'name' => $day->format('F Y'),
                    'date' => $day
                ];
            }

            $coursePricesMapped = [];
            $coursePrices = $group->course->getAllPricesSordedFromOldest();
            foreach ($coursePrices as $courseMembership) {
                $coursePricesMapped[] = [
                    'price' => $this->formatPrice($courseMembership->price),
                    'created_at' => $courseMembership->created_at
                ];
            }

            $groupsMapped[] = [
                'id' => $group->id,
                'name' => $group->name,
                'months' => $months,
                'course_prices' => $coursePricesMapped,
                'course_price_latest' => $this->formatPrice($coursePrices->last()->price)
            ];
        }

        return view('admin.users.membership.index', [
            'user' => $user,
            'groups_mapped' => $groupsMapped
        ]);
    }

    private function formatPrice($price)
    {
        return number_format($price, 2);
    }
}
