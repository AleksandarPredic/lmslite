<?php

namespace App\Http\Controllers;

use App\Services\PaymentService;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Carbon;

class PaymentsStatisticsController extends Controller
{
    private const FILTER_DATE_FORMAT = 'Y-m-d\TH:i';

    protected PaymentService $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    /**
     * Display all payments within a date range
     *
     * @return View
     */
    public function index(): View
    {
        // Default to current month if no dates provided
        $startDate = request('start_date')
            ? Carbon::createFromFormat(self::FILTER_DATE_FORMAT, request('start_date'))
            : Carbon::now()->startOfDay();

        $endDate = request('end_date')
            ? Carbon::createFromFormat(self::FILTER_DATE_FORMAT, request('end_date'))
            : Carbon::now()->endOfDay();

        // Get all payments within the date range using the payment service
        $payments = $this->paymentService->getPaymentsInDateRange(
            $startDate->startOfDay(),
            $endDate->endOfDay()
        );

        return view('admin.payments.index', [
            'payments' => $payments,
            'startDate' => $startDate->format(self::FILTER_DATE_FORMAT),
            'endDate' => $endDate->format(self::FILTER_DATE_FORMAT)
        ]);
    }
}
