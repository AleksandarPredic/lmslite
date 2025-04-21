<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;

class PaymentService
{
    /**
     * Record a payment for a user
     */
    public function recordPayment(User $user, array $data): Payment
    {
        // Get the currently logged-in user
        $loggedInUserId = auth()->id();

        // Check if payment exists for this month/year/group
        $existingPayment = Payment::where('user_id', $user->id)
                                  ->where('group_id', $data['group_id'])
                                  ->where('payment_month', $data['payment_month'])
                                  ->where('payment_year', $data['payment_year'])
                                  ->first();

        if ($existingPayment) {
            // Update existing payment
            $existingPayment->update([
                'amount' => $data['amount'],
                'payment_date' => $data['payment_date'],
                'note' => $data['note'] ?? $existingPayment->note,
                'created_by_id' => $loggedInUserId,
            ]);

            return $existingPayment;
        }

        // Create new payment
        return Payment::create([
            'user_id' => $user->id,
            'group_id' => $data['group_id'],
            'amount' => $data['amount'],
            'payment_date' => $data['payment_date'],
            'payment_month' => $data['payment_month'],
            'payment_year' => $data['payment_year'],
            'note' => $data['note'] ?? null,
            'created_by_id' => $loggedInUserId,
        ]);
    }

    /**
     * Get user payments for a specific group
     */
    public function getUserGroupPayments(User $user, int $groupId): Collection
    {
        return Payment::where('user_id', $user->id)
                      ->where('group_id', $groupId)
                      ->orderBy('payment_year')
                      ->orderBy('payment_month')
                      ->get();
    }

    /**
     * Get all payments for a user
     */
    public function getUserPayments(User $user): Collection
    {
        return Payment::where('user_id', $user->id)
                      ->orderBy('payment_year')
                      ->orderBy('payment_month')
                      ->get();
    }

    /**
     * Get payments for multiple users within a date range
     *
     * @param int[] $userIds Array of user IDs
     * @param \Carbon\Carbon $startDate Start date for filtering payments
     * @param \Carbon\Carbon $endDate End date for filtering payments
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getUsersPaymentsBetweebDates(array $userIds, $startDate, $endDate): Collection
    {
        return Payment::whereIn('user_id', $userIds)
               ->whereBetween('payment_date', [$startDate, $endDate])
               ->get();
    }

    /**
     * Get all payments within a specific date range
     *
     * @param Carbon $startDate
     * @param Carbon $endDate
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getPaymentsInDateRange(Carbon $startDate, Carbon $endDate)
    {
        return Payment::with(['user', 'group'])
                      ->whereBetween('payment_date', [$startDate, $endDate])
                      ->orderBy('payment_date', 'desc')
                      ->get();
    }
}
