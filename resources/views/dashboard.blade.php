<x-app-layout>
    <x-admin.header>
        {{ __('Dashboard') }}
    </x-admin.header>

    <x-admin.main>
        @if(Gate::allows('admin'))
            {{ __('Hellow admin') }}
        @elseif(Gate::allows('student'))
            {{ __('Hellow Student') }}
        @elseif(Gate::allows('student-guest'))
            {{ __('Hellow student guest') }}
        @endif
    </x-admin.main>
</x-app-layout>
