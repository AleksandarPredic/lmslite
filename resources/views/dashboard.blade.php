<x-app-layout>
    <x-admin.header>
        {{ __('Dashboard') }}
    </x-admin.header>

    <x-admin.main>
        @can('admin')
            {{ __('Hellow admin') }}
        @endcan

        @can('student')
            {{ __('Hellow Student') }}
        @endcan

        @can('student-guest')
            {{ __('Hellow student guest') }}
        @endcan
    </x-admin.main>
</x-app-layout>
