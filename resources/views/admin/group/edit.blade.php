<x-app-layout>
    <x-admin.header>
        {{ __('Edit group') }}
    </x-admin.header>

    <x-admin.main>
        <div class="flex mb-4">
            <x-admin.redirect-link href="{{ route('admin.groups.index') }}" :title="__('Back to all!')" />
        </div>

        <div class="flex justify-end mb-4 px-4">
            <x-admin.action-link-button href="{{ route('admin.groups.show', $group) }}" title="{{ __('Manage') }}" />
            <x-admin.action-delete-button action="{{ route('admin.groups.destroy', $group) }}" />
        </div>

        <x-admin.form.wrapper
            class="admin-form-event"
            action="{{ route('admin.groups.update', $group) }}"
            method="post"
            :buttonText="__('Update')"
        >
            @method('patch')

            <x-admin.form.input
                name="name"
                :value="old('name', $group->name)"
                :label="__('Name')"
                :required="true"
            />

            <x-admin.form.input-date-time
                name="starting_at"
                :value="old('starting_at', $group->starting_at)"
                :label="__('Starting at')"
                :required="true"
            />

            <x-admin.form.input-date-time
                name="ending_at"
                :value="old('ending_at', $group->ending_at)"
                :label="__('Ending at')"
                :required="true"
            />
            <x-admin.form.course :value="$group->course->id ?? 0" />

            <x-admin.form.textarea
                name="note"
                :label="__('Note')"
            >{{ old('note', $group->note) }}</x-admin.form.textarea>

            <x-admin.form.select
                name="active"
                :value="(int)old('active', $group->active)"
                :label="__('Active')"
                :options="$activeOptions"
            />
            <p><small>* If you set group to non-active it will be hidden in some select fields or queries. Usually used for old Groups no longer in use.</small></p>
        </x-admin.form.wrapper>
    </x-admin.main>
</x-app-layout>
