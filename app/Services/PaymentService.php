<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class PaymentService
{
    /**
     * Record a payment for a user
     */
    public function recordPayment(User $user, array $data): Payment
    {
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
                'payment_date' => $data['payment_date'] ?? now(),
                'note' => $data['note'] ?? $existingPayment->note,
            ]);

            return $existingPayment;
        }

        // Create new payment
        return Payment::create([
            'user_id' => $user->id,
            'group_id' => $data['group_id'],
            'amount' => $data['amount'],
            'payment_date' => $data['payment_date'] ?? now(),
            'payment_month' => $data['payment_month'],
            'payment_year' => $data['payment_year'],
            'note' => $data['note'] ?? null,
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
}
