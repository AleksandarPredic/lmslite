@props(['name', 'value'])

<p class="mt-1 max-w-2xl text-sm text-gray-500">
    {{ $name }}: {!! strip_tags($value, ['<a>']) !!}
</p>
