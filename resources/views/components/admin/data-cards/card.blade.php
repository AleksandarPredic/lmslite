@props(['name'])

<div class="sm:flex sm:justify-between bg-white shadow overflow-hidden border border-gray-200 sm:rounded-lg mb-4">
    <div class="px-6 py-4">
        <h5 class="text-gray-900 font-semibold">{{ $name }}</h5>
        <div class="flex data-cards_properties">
            {{ $properties ?? null }}
        </div>
    </div>
    <div class="flex justify-end px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
        {{ $slot }}
    </div>
</div>
