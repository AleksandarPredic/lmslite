<x-app-layout>
    <x-admin.header>
        {{ __('Edit course') }} - <a href="{{ route('admin.courses.index') }}" class="text-base underline" >Back to all!</a>
    </x-admin.header>

    <x-admin.main>
        <x-admin.form.wrapper
            action="{{ route('admin.courses.update', [$course]) }}"
            method="post"
            :buttonText="__('Update')"
        >
            @method('patch')

            <!-- Name -->
            <x-admin.form.input
                name="name"
                :value="old('name', $course->name)"
                :label="__('Name')"
                :required="true"
            />

        </x-admin.form.wrapper>
    </x-admin.main>
</x-app-layout>
