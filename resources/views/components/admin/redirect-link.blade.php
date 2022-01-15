@props(['href', 'title'])

<a
    href="{{ $href }}"
    {!! $attributes->merge(['class' => 'text-base underline flex']) !!}
>
    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="#000000"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M21 11H6.83l3.58-3.59L9 6l-6 6 6 6 1.41-1.41L6.83 13H21v-2z"/></svg>
    <span class="ml-2">{{ $title }}</span>
</a>
