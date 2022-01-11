@props(['action', 'method', 'buttonText'])

<form {{ $attributes->merge(['class' => 'admin-form']) }} action="{{ $action }}" method="{{ $method }}">
    @csrf

    {{ $slot }}

    <div class="admin-form__button flex items-center justify-end mt-4">
        <x-button class="ml-4">
            {{ $buttonText }}
        </x-button>
    </div>
</form>
