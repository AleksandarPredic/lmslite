@props(['month', 'group'])

<!-- Add attendance statistics -->
@php
    $monthYearKey = $month['date']->format('Y-m');
    $monthlyStatus = $group->monthlyStatuses[$monthYearKey];
@endphp

<div class="mr-2 mb-2 mt-2 text-sm singular-meta-user-payments__statuses">
    <ul>
        <li class="font-medium text-green-600">
            <span>{{ __('Attended') }}: {{ count($monthlyStatus['attended']['count']) }}</span>
        </li>
        <li class="font-medium text-red-600">
            <span>{{ __('Canceled') }}: {{ count($monthlyStatus['canceled']['count']) }}</span>

            @if($canceledCompensations = $monthlyStatus['canceled']['compensations'] ?? null)
                <ul>
                    @foreach($canceledCompensations as $canceledCompensation)
                        <li>
                            <x-compensation.compensation-trigger
                                :compensation="$canceledCompensation"
                            />
                        </li>
                    @endforeach
                </ul>
            @endif
        </li>
        <li class="font-medium text-yellow-600">
            <span>{{ __('No-show') }}: {{ count($monthlyStatus['no-show']['count']) }}</span>

            @if($noShowCompensations = $monthlyStatus['no-show']['compensations'] ?? null)
                <ul>
                    @foreach($noShowCompensations as $noShowCompensation)
                        <li>
                            <x-compensation.compensation-trigger
                                :compensation="$noShowCompensation"
                            />
                        </li>
                    @endforeach
                </ul>
            @endif
        </li>
    </ul>
</div>
