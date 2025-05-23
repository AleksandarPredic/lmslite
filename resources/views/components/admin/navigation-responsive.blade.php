<x-responsive-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.index')">
    {{ __('Users') }}
</x-responsive-nav-link>

<x-responsive-nav-link :href="route('admin.events.index')" :active="request()->routeIs('admin.events.index')">
    {{ __('Events') }}
</x-responsive-nav-link>

<x-responsive-nav-link :href="route('admin.groups.index')" :active="request()->routeIs('admin.groups.index')">
    {{ __('Groups') }}
</x-responsive-nav-link>

<x-responsive-nav-link :href="route('admin.courses.index')" :active="request()->routeIs('admin.courses.index')">
    {{ __('Courses') }}
</x-responsive-nav-link>

<x-responsive-nav-link :href="route('admin.statistics.index')" :active="request()->routeIs('admin.statistics.index')">
    {{ __('Statistics') }}
</x-responsive-nav-link>

<x-responsive-nav-link :href="route('admin.payments.statistics')" :active="request()->routeIs('admin.payments.statistics')">
    {{ __('Payments review') }}
</x-responsive-nav-link>
