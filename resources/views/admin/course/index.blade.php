<x-app-layout>
    <x-admin.header>
        {{ __('Courses') }}
    </x-admin.header>

    <x-admin.main>
        <div class="flex justify-end mb-4 px-4">
            <x-admin.action-link-button href="{{ route('admin.courses.create') }}" title="{{ __('Create') }}" />
        </div>

        <x-data-cards.wrapper>
            <x-slot name="cards">
                @php
                /**
                 * @var \App\Models\Course $course
                 */

                $icon = '<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="#000000"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M18 2H6c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zM9 4h2v5l-1-.75L9 9V4zm9 16H6V4h1v9l3-2.25L13 13V4h5v16z"/></svg>';
                @endphp
                @foreach($courses as $course)
                    <x-data-cards.card
                        :name="$course->name"
                        :svg="$icon"
                    >
                        <x-link
                            href="{{ route('admin.courses.edit', [$course]) }}"
                            title="Edit" />

                        <x-admin.form.delete-button action="{{ route('admin.courses.destroy', [$course]) }}" />
                    </x-data-cards.card>
                @endforeach
            </x-slot>
            <x-slot name="pagination">
                {{ $courses->links() }}
            </x-slot>
        </x-data-cards.wrapper>
    </x-admin.main>
</x-app-layout>
