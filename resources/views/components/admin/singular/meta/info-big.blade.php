@props(['name', 'value'])

<x-admin.singular.meta.item-wrapper class="bg-gray-100">
    {{ $name }} <span class="flex-1 text-right text-xl">{!! strip_tags($value, ['<a>', 'strong']) !!}</span>
</x-admin.singular.meta.item-wrapper>
