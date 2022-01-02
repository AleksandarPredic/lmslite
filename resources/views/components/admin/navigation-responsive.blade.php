<x-responsive-nav-link :href="route('admin.courses.index')" :active="request()->routeIs('admin.courses.index')">
    {{ __('Courses') }}
</x-responsive-nav-link>
