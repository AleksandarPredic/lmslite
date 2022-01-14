@props(['href', 'title'])

<a
    href="{{ $href }}"
    class="border border-indigo-400 hover:bg-gray-100 hover:border-gray-300 hover:text-gray-400 px-6 py-2 rounded text-indigo-600 ml-4"
>
    {{ $title }}
</a>
