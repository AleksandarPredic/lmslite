<x-responsive-nav-link :href="route('admin.courses.index')" :active="request()->routeIs('admin.courses.index')">
    {{ __('Courses') }}
</x-responsive-nav-link>

<x-responsive-nav-link :href="route('admin.events.index')" :active="request()->routeIs('admin.events.index')">
    {{ __('Events') }}
</x-responsive-nav-link>
