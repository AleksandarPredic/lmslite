@php
/**
 * @var \App\Models\User $user
 */
@endphp

<x-app-layout>
    <x-admin.header>
        {{ __('Edit a user') }}
    </x-admin.header>

    <x-admin.main>
        <div class="flex mb-4">
            <x-admin.redirect-link href="{{ route('admin.users.index') }}" :title="__('Back to all!')" />
        </div>

        <div class="flex justify-end mb-4 px-4">
            <x-admin.action-link-button href="{{ route('admin.users.membership.index', $user) }}" title="{{ __('Membership') }}" />
            <x-admin.action-link-button href="{{ route('admin.users.nextcalendarevents', $user) }}" title="{{ __('Next events') }}" />
            <x-admin.action-link-button href="{{ route('admin.users.show', $user) }}" title="{{ __('View') }}" />
            <x-admin.action-delete-button action="{{ route('admin.users.destroy', $user) }}" />
        </div>

        <x-admin.form.wrapper
            class="admin-form-event"
            action="{{ route('admin.users.update', $user) }}"
            method="post"
            :buttonText="__('Update')"
        >
            @method('patch')

            <x-admin.form.input
                name="name"
                :value="old('name', $user->name)"
                :label="__('Name')"
                :required="true"
            />

            <x-admin.form.input
                name="email"
                type="email"
                :value="old('email', $user->email)"
                :label="__('Email')"
                :required="false"
            />

            <x-admin.form.role
                :value="$user->role->isNotEmpty() ? $user->role->first()->id : 2"
            />

            <x-admin.form.input
                name="parent_1_name"
                :value="old('parent_1_name', $user->parent_1_name)"
                :label="__('First parent name')"
            />

            <x-admin.form.input
                name="parent_1_phone"
                :value="old('parent_1_phone', $user->parent_1_phone)"
                :label="__('First parent phone')"
            />

            <x-admin.form.input
                name="parent_2_name"
                :value="old('parent_2_name', $user->parent_2_name)"
                :label="__('Second parent name')"
            />

            <x-admin.form.input
                name="parent_2_phone"
                :value="old('parent_2_phone', $user->parent_2_phone)"
                :label="__('Second parent phone')"
            />

            <x-admin.form.input-date-time
                name="date_of_birth"
                :value="old('date_of_birth', $user->date_of_birth)"
                :label="__('Date of birth')"
            />

            <x-admin.form.input
                name="address"
                :value="old('address', $user->address)"
                :label="__('Address')"
            />

            <x-admin.form.input
                name="school"
                :value="old('school', $user->school)"
                :label="__('School')"
            />

            <x-admin.form.input
                name="school_info"
                :value="old('school_info', $user->school_info)"
                :label="__('School info')"
            />

            <x-admin.form.input-date-time
                name="sign_up_date"
                :value="old('sign_up_date', $user->sign_up_date)"
                :label="__('Sign up date')"
            />

            <x-admin.form.select
                name="active"
                :value="(int)old('active', $user->active)"
                :label="__('Status')"
                :options="[1 => __('Active'), 0 => __('Inactive')]"
            />

            <x-admin.form.textarea
                name="note"
                :label="__('Note')"
            >{{ old('note', $user->note) }}</x-admin.form.textarea>

        </x-admin.form.wrapper>
    </x-admin.main>
</x-app-layout>
