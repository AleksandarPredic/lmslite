@props(['name', 'value'])

<x-admin.singular.meta.item-wrapper class="bg-gray-100">
    {{ $name }} <span class="flex text-right bg-indigo-50" style="margin-left: auto">{{ $value }}</span>
</x-admin.singular.meta.item-wrapper>
