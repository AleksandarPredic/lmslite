<x-app-layout>
    <x-admin.header>
        {{ __('Courses') }}
    </x-admin.header>

    <x-admin.main>
        <div class="flex justify-end mb-4 px-4">
            <x-admin.action-link-button href="{{ route('admin.courses.create') }}" title="Create" />
        </div>

        <x-admin.data-cards.wrapper>
            <x-slot name="cards">
                @foreach($courses as $course)
                    <x-admin.data-cards.card :name="$course->name">
                        <x-admin.data-cards.link
                            href="{{ route('admin.courses.edit', [$course]) }}"
                            title="Edit" />

                        <x-admin.form.delete-button action="{{ route('admin.courses.destroy', [$course]) }}" />
                    </x-admin.data-cards.card>
                @endforeach
            </x-slot>
            <x-slot name="pagination">
                {{ $courses->links() }}
            </x-slot>
        </x-admin.data-cards.wrapper>
    </x-admin.main>
</x-app-layout>
