<x-app-layout>
    <x-admin.header>
        {{ __('Courses') }}
    </x-admin.header>

    <x-admin.main>
        <x-admin.data-cards.wrapper>
            <x-slot name="cards">
                @foreach($courses as $course)
                    <x-admin.data-cards.card name="{{ $course->name }}">
                        <x-admin.data-cards.link href="#" title="Edit" />
                    </x-admin.data-cards.card>
                @endforeach
            </x-slot>
            <x-slot name="pagination">
                {{ $courses->links() }}
            </x-slot>
        </x-admin.data-cards.wrapper>
    </x-admin.main>
</x-app-layout>
