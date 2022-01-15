<x-app-layout>
    <x-admin.header>
        {{ __('Edit course') }}
    </x-admin.header>

    <x-admin.main>
        <div class="flex justify-end">
            <x-admin.redirect-link href="{{ route('admin.courses.index') }}" :title="__('Back to all!')" />
        </div>

        <x-admin.form.wrapper
            action="{{ route('admin.courses.update', [$course]) }}"
            method="post"
            :buttonText="__('Update')"
        >
            @method('patch')

            <x-admin.form.input
                name="name"
                :value="old('name', $course->name)"
                :label="__('Name')"
                :required="true"
            />

        </x-admin.form.wrapper>
    </x-admin.main>
</x-app-layout>
