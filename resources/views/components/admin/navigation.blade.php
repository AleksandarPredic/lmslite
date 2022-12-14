<x-nav-link
    :href="route('admin.users.index')"
    :active="request()->routeIs('admin.users.index')"
    class="mr-2"
>
    {{ __('Users') }}
</x-nav-link>

<x-nav-link
    :href="route('admin.events.index')"
    :active="request()->routeIs('admin.events.index')"
    class="mr-2"
>
    {{ __('Events') }}
</x-nav-link>

<x-nav-link
    :href="route('admin.groups.index')"
    :active="request()->routeIs('admin.groups.index')"
    class="mr-2"
>
    {{ __('Groups') }}
</x-nav-link>

<x-nav-link
    :href="route('admin.courses.index')"
    :active="request()->routeIs('admin.courses.index')"
    class="mr-2"
>
    {{ __('Courses') }}
</x-nav-link>

<x-nav-link
    :href="route('admin.statistics.index')"
    :active="request()->routeIs('admin.statistics.index')"
    class="mr-2"
>
    {{ __('Statistics') }}
</x-nav-link>
