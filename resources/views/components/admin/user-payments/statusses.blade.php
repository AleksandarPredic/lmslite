@props(['month', 'group'])

<!-- Add attendance statistics -->
@php
    $monthYearKey = $month['date']->format('Y-m');
    $monthlyStatus = $group->monthlyStatuses[$monthYearKey];
@endphp
<div class="mr-2 mb-2 mt-2 ml-12 text-sm singular-meta-user-payments__statuses">
    <ul>
        <li class="font-medium text-green-600">{{ __('Attended') }}: {{ count($monthlyStatus['attended']) }}</li>
        <li class="font-medium text-red-600">{{ __('Canceled') }}: {{ count($monthlyStatus['canceled']) }}</li>
        <li class="font-medium text-yellow-600">{{ __('No-show') }}: {{ count($monthlyStatus['no-show']) }}</li>
        <li class="font-medium text-blue-600">{{ __('Compensation') }}:
            @if(! empty($monthlyStatus['compensation']))
                <ul class="ml-4">
                    @foreach($monthlyStatus['compensation'] as $status => $compensationsCollection)
                        <li class="font-medium">
                            <span>{{ $status }}: {{ count($compensationsCollection) }}</span>
                            @foreach($compensationsCollection as $compensation)
                                <x-data-property-compensation-trigger
                                    :compensation="$compensation"
                                    linkText="{{ __('Compensated on') }}"
                                />
                            @endforeach
                        </li>
                    @endforeach
                </ul>
            @else 0 @endif
        </li>
    </ul>
</div>
