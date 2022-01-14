@props(['background' => 'bg-indigo-50'])
<div class="data-cards_property text-sm {{ $background }} border border-gray-200 mt-2 px-4 rounded text-gray-900 text-sm">
    {{ $slot }}
</div>
