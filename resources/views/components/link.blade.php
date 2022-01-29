@props(['href', 'title'])

<a
    href="{{ $href }}"
    {!! $attributes->merge(['class' => 'text-indigo-600 hover:text-indigo-900']) !!}
>
    {{ $title }}
</a>
