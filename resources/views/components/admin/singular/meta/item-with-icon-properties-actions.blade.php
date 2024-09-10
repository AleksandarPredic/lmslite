{{-- # Use in any show model view to display list of items with icon, some properties and inputs as actions --}}

<x-admin.singular.meta.item-wrapper class="{{ $class ?? null }}">
    <x-admin.singular.meta.item-icon>
        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000"><path d="M400-280v-400l200 200-200 200Z"/></svg>
    </x-admin.singular.meta.item-icon>

    <x-admin.singular.meta.item-properties-wrapper>
        {{ $properties ?? null }}
    </x-admin.singular.meta.item-properties-wrapper>

    <x-admin.singular.meta.item-links-wrapper>
        {{ $slot }}
    </x-admin.singular.meta.item-links-wrapper>
</x-admin.singular.meta.item-wrapper>
