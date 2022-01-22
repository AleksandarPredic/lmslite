@props(['name', 'value'])

<div class="bg-white px-4 py-2">
    <dt class="text-sm font-medium text-gray-500">
        {{ $name }}
    </dt>
    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
        {{ $value }}
    </dd>
</div>
