@props(['name', 'value'])

<li class="pl-3 pr-4 py-2 flex items-center justify-between text-sm border-b border-gray-200 bg-gray-100">
    {{ $name }} <small>{{ $value }}</small>
</li>
