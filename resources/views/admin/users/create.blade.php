<x-app-layout>
    <x-admin.header>
        {{ __('Create a user') }}
    </x-admin.header>

    <x-admin.main>
        <div class="flex mb-4">
            <x-admin.redirect-link href="{{ route('admin.users.index') }}" :title="__('Back to all!')" />
        </div>

        <x-admin.form.wrapper
            class="admin-form-event"
            action="{{ route('admin.users.store') }}"
            method="post"
            :buttonText="__('Create')"
        >
            <x-admin.form.input
                name="name"
                :value="old('name')"
                :label="__('Name')"
                :required="true"
            />

            <x-admin.form.input
                name="email"
                type="email"
                :value="old('email')"
                :label="__('Email')"
                :required="false"
            />

            <x-admin.form.role
                :value="old('role_id', 2)"
            />

            <x-admin.form.input
                name="parent_1_name"
                :value="old('parent_1_name')"
                :label="__('First parent name')"
            />

            <x-admin.form.input
                name="parent_1_phone"
                :value="old('parent_1_phone')"
                :label="__('First parent phone')"
            />

            <x-admin.form.input
                name="parent_2_name"
                :value="old('parent_2_name')"
                :label="__('Second parent name')"
            />

            <x-admin.form.input
                name="parent_2_phone"
                :value="old('parent_2_phone')"
                :label="__('Second parent phone')"
            />

            <x-admin.form.input-date-time
                name="date_of_birth"
                :value="old('date_of_birth')"
                :label="__('Date of birth')"
            />

            <x-admin.form.input
                name="address"
                :value="old('address')"
                :label="__('Address')"
            />

            <x-admin.form.input
                name="school"
                :value="old('school')"
                :label="__('School')"
            />

            <x-admin.form.input
                name="school_info"
                :value="old('school_info')"
                :label="__('School info')"
            />

            <x-admin.form.input-date-time
                name="sign_up_date"
                :value="old('sign_up_date')"
                :label="__('Sign up date')"
            />

            <x-admin.form.select
                name="active"
                :value="(int)old('active', 1)"
                :label="__('Status')"
                :options="[1 => __('Active'), 0 => __('Inactive')]"
                :required="true"
            />

            <x-admin.form.textarea
                name="note"
                :label="__('Note')"
            >{{ old('note') }}</x-admin.form.textarea>

            <x-admin.form.textarea
                name="payment_note"
                :label="__('Payment note')"
            >{{ old('payment_note') }}</x-admin.form.textarea>

            <x-admin.form.select
                name="media_consent"
                :value="(int)old('media_consent', 0)"
                :label="__('Consent for Photo and Video Use')"
                :options="[0 => __('No'), 1 => __('Yes')]"
            />
        </x-admin.form.wrapper>
    </x-admin.main>
</x-app-layout>
