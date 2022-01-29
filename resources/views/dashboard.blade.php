<x-app-layout>
    <x-admin.header>
        {{ __('Dashboard') }}
    </x-admin.header>

    <x-admin.main>
        @if(Gate::allows('admin'))

            <x-calendar.week />

        @elseif(Gate::allows('student'))

            {{-- # TODO: Still todo this view --}}
            {{ __('Hellow Student') }}

        @elseif(Gate::allows('student-guest'))

            {{-- # TODO: Still todo this view if we are going to use it --}}
            {{ __('Hellow student guest') }}

        @endif
    </x-admin.main>
</x-app-layout>
