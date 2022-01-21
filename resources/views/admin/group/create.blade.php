<x-app-layout>
    <x-admin.header>
        {{ __('Create group') }}
    </x-admin.header>

    <x-admin.main>
        <div class="flex mb-4">
            <x-admin.redirect-link href="{{ route('admin.groups.index') }}" :title="__('Back to all!')" />
        </div>

        <x-admin.form.wrapper
            class="admin-form-event"
            action="{{ route('admin.groups.store') }}"
            method="post"
            :buttonText="__('Create')"
        >
            <x-admin.form.input
                name="name"
                :value="old('name')"
                :label="__('Name')"
                :required="true"
            />

            <x-admin.form.input-date-time
                name="starting_at"
                :value="old('starting_at')"
                :label="__('Starting at')"
                :required="true"
            />

            <x-admin.form.input-date-time
                name="ending_at"
                :value="old('ending_at')"
                :label="__('Ending at')"
                :required="true"
            />

            <x-admin.form.textarea
                name="note"
                :label="__('Note')"
            >{{ old('note') }}</x-admin.form.textarea>
        </x-admin.form.wrapper>
    </x-admin.main>
</x-app-layout>
