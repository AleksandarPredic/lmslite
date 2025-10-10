{{--
# Search users via Alpine js x-on https://alpinejs.dev/directives/on
# Use in any blade file
--}}
@props(['route', 'exclude' => []])

<div x-data="searchUsers({url: '{{ route('admin.users.find') }}'})">
    <x-admin.form.field>
        <x-admin.form.label for="find-user" :value="__('Type user name')" />
        <input
            id="find-user"
            class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
            type="text"
            placeholder="{{ __('Type user name here...') }}"
            value=""
            required
            @input.debounce.500ms="getResults"
        />
    </x-admin.form.field>

    {{-- # Form to add users --}}
    <x-admin.form.wrapper
        action="{{ $route }}"
        method="post"
        button-text="{{ __('Add user') }}"
    >
        <x-admin.form.field>
            <select
                name="user_id"
                class="block mt-1 w-full"
                required
                x-html="options"
            ></select>

            <x-admin.form.error name="user_id" />
        </x-admin.form.field>
    </x-admin.form.wrapper>

    <p class="mb-4 mt-4 py-1 text" x-text="message"></p>

    <script>
        // TODO: Rewrite this in plan JS or move it to js file, currently using ES6 but this is fine as we will use only supported browsers
        // Uses app/Http/Controllers/UserController.php@findUsers on the backend
        function searchUsers(routeUrl) {
            const optionInitValue = '<option value="">{{ __('Waiting...') }}</option>';

            return {
                routeUrl: routeUrl.url,
                message: '',
                name: '',
                optionsDefault: optionInitValue,
                options: optionInitValue,
                exclude: JSON.parse('{{ json_encode($exclude) }}'),

                getResults(event) {
                    const that = this;
                    that.name = event.target.value;

                    if (this.name.length < 3) {
                        that.message = '{{ __('Please type at least 3 characters.') }}';
                        return false;
                    }

                    that.message = 'Loading users...'
                    // Reset options from previous search
                    that.options = that.optionsDefault;

                    axios.post(this.routeUrl, {
                        name: that.name,
                        exclude: that.exclude
                    })
                        .then(function (response) {
                            if (response.data.length > 0) {
                                that.options = [`<option value="">{{ __('Click here to select user...') }}</option>`]
                                    .concat(
                                        response.data
                                            .map((user) => `<option value="${user.id}">${user.name} - ID:${user.id}</option>`)
                                    );

                                that.message = '{{ __('Select the user below and click the add user button!') }}';
                                return;
                            }

                            that.message = '{{ __('No users found or user is already added. Please try another name.') }}';
                        })
                        .catch(function (error) {
                            // Check if we failed validation
                            var errorData = error.response.data;

                            if (errorData.errors && errorData.errors.name) {
                                that.message = errorData.errors.name;
                                return;
                            }

                            console.log(error.response);
                            that.message = 'Ooops! Something went wrong!';
                        });
                }
            }
        }
    </script>
</div>
