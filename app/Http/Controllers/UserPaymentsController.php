<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\PaymentService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

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
        $groups = $user->groups()->withPivot('price_type')->where('active', true)->orderBy('name')->get();

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
        }

        return view('admin.users.payments.index', [
            'user' => $user,
            'groups' => $groups
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
