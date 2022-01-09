<x-nav-link
    :href="route('admin.courses.index')"
    :active="request()->routeIs('admin.courses.index')"
    class="mr-2"
>
    {{ __('Courses') }}
</x-nav-link>

<x-nav-link
    :href="route('admin.events.index')"
    :active="request()->routeIs('admin.events.index')"
    class="mr-2"
>
    {{ __('Events') }}
</x-nav-link>

