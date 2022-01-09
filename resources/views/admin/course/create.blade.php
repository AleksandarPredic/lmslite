<x-app-layout>
    <x-admin.header>
        {{ __('Create course') }}
    </x-admin.header>

    <x-admin.main>
        <x-admin.form.wrapper
            action="{{ route('admin.courses.store') }}"
            method="post"
            :buttonText="__('Create')"
        >

            <x-admin.form.input
                name="name"
                :value="old('name')"
                :label="__('Name')"
                :required="true"
            />

        </x-admin.form.wrapper>
    </x-admin.main>
</x-app-layout>
