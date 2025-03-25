@props(['payments'])

@php
    /**
     * @var \Illuminate\Database\Eloquent\Collection $payments
     */
@endphp
@if($payments->isNotEmpty())
    <hr class="mt-4 mb-4"/>
    <div class="statistics__group-payments">
        <ul>
            @php
                /**
                 * @var \App\Models\Payment $payment
                 */
            @endphp
            @foreach($payments as $payment)
                <li><span>{{ $payment->group->name }}, {{ lmsCarbonDateFormat($payment->payment_date) }}</span> <span>- {{ lmsPricePublicFormat($payment->amount) }}</span></li>
            @endforeach
        </ul>
    </div>
@endif
