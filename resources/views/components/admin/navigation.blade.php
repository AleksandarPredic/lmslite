<x-nav-link
    :href="route('admin.courses.index')"
    :active="request()->routeIs('admin.courses.index')"
    class="mr-2"
>
    {{ __('Courses') }}
</x-nav-link>

